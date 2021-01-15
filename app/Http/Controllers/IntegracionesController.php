<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Functions\ConnHelper;
use App\Functions\Helper;
use App\Http\Controllers\Integraciones\SOMA;
use App\Http\Controllers\Integraciones\RUAF;

class IntegracionesController extends Controller
{
    public function postSoma()
    {
        return SOMA::download();
    }

    public function postSomaSend()
    {
        return SOMA::send();
    }

     public function postRuaf()
    {
        return RUAF::upload();
    }


    public function postSolgein()
    {
        set_time_limit(5*60);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $folder   = 'temp';
        $filename = 'Import_SOLGEIN.xls';
        request()->file('file')->move($folder, $filename);
        
        $BaseRegs = Helper::readTableFile($folder.'/'.$filename, [
            'col_ini' => 1, 'col_fin' => 7, 'row_ini' => 7, 'row_fin' => null,
            'headers' => ['Variable','Nivel','Nombre del nivel','Mes','Valor Real', 'errR', 'Meta']
        ]);

        $Procesos = \App\Models\Proceso::whereNotNull('Op1')->where('Op1', '<>', '')->get()->keyBy('Op1')->toArray();
        $ProcesosIds     = array_column($Procesos, 'id');
        $ProcesosNiveles = array_keys($Procesos);
        $Indicadores = \App\Models\Indicador::whereIn('proceso_id', $ProcesosIds)->get([ 'id', 'Indicador', 'TipoDato', 'proceso_id' ])
        ->keyBy(function($I){
                return $I->proceso_id .'___'. strtolower(trim($I->Indicador));
            })->toArray();
        $IndicadoresIds = array_column($Indicadores, 'id');

        $Variables   = \App\Models\Variable::where('Tipo', 'Manual')
            ->whereIn('proceso_id', $ProcesosIds)
            ->get(['id', 'Variable', 'proceso_id', 'TipoDato', 'Filtros'])
            ->keyBy(function($V){
                return $V->proceso_id .'___'. strtolower(trim($V->Variable));
            })->toArray();
        $VariablesIds = array_column($Variables, 'id');

        $Regs = [];
        $Periodos = [];

        foreach ($BaseRegs as $reg) {
            if( is_null($reg['Valor Real']) OR !in_array($reg['Nivel'], $ProcesosNiveles) ) continue;

            $reg['indicador_id'] = null;
            $reg['variable_id'] = null;

            $Proceso = $Procesos[$reg['Nivel']];
            $reg['proceso_id'] = $Proceso['id'];

            $uid = $Proceso['id'] .'___'. strtolower(trim($reg['Variable']));

            if(array_key_exists($uid, $Variables)){
                $Variable = $Variables[$uid];

                $reg['variable_id'] = $Variable['id'];
                $reg['variable_tipo'] = $Variable['TipoDato'];

                if($Variable['TipoDato'] == 'Porcentaje'){
                    $reg['Valor Real'] = round(($reg['Valor Real']/100), 4);
                }
            }

            if(array_key_exists($uid, $Indicadores)){
                $Indicador = $Indicadores[$uid];
                $reg['indicador_id'] = $Indicador['id'];
                $reg['indicador_tipo'] = $Indicador['TipoDato'];

                if($Indicador['TipoDato'] == 'Porcentaje'){
                    $reg['Meta'] = round(($reg['Meta']/100), 4);
                }
            }

            if( is_null($reg['variable_id']) AND is_null($reg['indicador_id']) ) continue;

            $Regs[] = $reg;
            $Periodos[$reg['Mes']] = 0;
        }

        $PeriodoMin = min(array_keys($Periodos));
        $PeriodoMax = max(array_keys($Periodos));

        unset($BaseRegs);

        $E = [
            'variables' => 0,
            'variables_cargadas' => 0,
            'metas_cargadas' => 0,
            'periodo_min' => $PeriodoMin,
            'periodo_max' => $PeriodoMax,
            //'regs' => $Regs
        ];

        if(count($Regs) == 0) return $E;

        $VariableValores = \App\Models\VariableValor::whereIn('variable_id', $VariablesIds)->whereBetween('Periodo', [$PeriodoMin, $PeriodoMax])
            ->get()->keyBy(function($VV){
                return $VV['variable_id'] .'_'. $VV['Periodo'];
            })->toArray();

        $IndicadoresMetas = \App\Models\IndicadorMeta::whereIn('indicador_id', $IndicadoresIds)->where('PeriodoDesde', '<=', $PeriodoMax)->orderBy('PeriodoDesde')->get()->groupBy('indicador_id')->toArray();

        //return $IndicadoresMetas;

        $E['variables_valores'] = count($VariableValores);

        //Cargar
        $VariablesValoresNew = [];
        $MetasNew = [];
        $MetasUpdate = [];
        $Ahora = Carbon::now();
        foreach ($Regs as $reg) {
            if($reg['variable_id']){

                $E['variables']++;
                $vv_uid = $reg['variable_id'] .'_'. $reg['Mes'];

                if(!array_key_exists($vv_uid, $VariableValores)){
                    
                    $VariablesValoresNew[] = [
                        'variable_id' => $reg['variable_id'],
                        'Periodo'     => $reg['Mes'],
                        'Valor'       => $reg['Valor Real'],
                        'created_at'  => $Ahora,
                        'updated_at'  => $Ahora
                    ];
                    $E['variables_cargadas']++;
                }
            }

            //Cargue de metas
            if($reg['indicador_id'] AND !is_null($reg['Meta'])){

                $reg['Meta'] = round($reg['Meta'], 4);

                if(!array_key_exists($reg['indicador_id'], $IndicadoresMetas)){
                    $MetasNew[($reg['indicador_id'].'_200001')] = [
                        'indicador_id' => $reg['indicador_id'],
                        'PeriodoDesde' => 200001,
                        'Meta' => $reg['Meta']
                    ];
                    $E['metas_cargadas']++;
                }else{

                    /*if(count($IndicadoresMetas[$reg['indicador_id']]) > 1){
                        dd($IndicadoresMetas[$reg['indicador_id']]);
                    }*/
                    $DaMeta = null;
                    foreach ($IndicadoresMetas[$reg['indicador_id']] as $Meta) {
                        if($Meta['PeriodoDesde'] > $reg['Mes']) break;
                        $DaMeta = $Meta;
                    }

                    if($DaMeta['Meta'] != $reg['Meta']){
                        if($DaMeta['PeriodoDesde'] == $reg['Mes']){
                            $MetasUpdate[$reg['indicador_id'].'_'.$reg['Mes']] = [
                                'id' => $DaMeta['id'],
                                'Periodo' => $reg['Mes'],
                                'MetaAnt' => $DaMeta['Meta'],
                                'Meta' => $reg['Meta']
                            ];
                        }else if($DaMeta['PeriodoDesde'] < $reg['Mes']){
                            $NewMeta = [
                                'indicador_id' => $reg['indicador_id'],
                                'PeriodoDesde' => $reg['Mes'],
                                'Meta' => $reg['Meta']
                            ];
                            $MetasNew[($reg['indicador_id'].'_'.$reg['Mes'])] = $NewMeta;
                            $IndicadoresMetas[$reg['indicador_id']][] = $NewMeta;
                            $E['metas_cargadas']++;

                        }
                    }
                    //dd();

                }

            }
        }

        if($E['variables_cargadas'] > 0){
            \App\Models\VariableValor::insert($VariablesValoresNew);
            Helper::touchIndicadores();
        }

        if($E['metas_cargadas'] > 0){
            \App\Models\IndicadorMeta::insert(array_values($MetasNew));
            $E['metas'] = $MetasNew;
            Helper::touchIndicadores();
        }

        if(!empty($MetasUpdate)){
            foreach ($MetasUpdate as $MU) {
                \App\Models\IndicadorMeta::where('id', $MU['id'])->update([ 'Meta' => $MU['Meta'] ]);
            }
        }

        return $E;
    }

    public function getSolgeinComentarios()
    {
        set_time_limit(5*60);
        $folder   = 'temp';
        $filename = 'Análisis 2020-09.xls';
        //request()->file('file')->move($folder, $filename);
        
        $BaseRegs = Helper::readTableFile($folder.'/'.$filename, [
            'col_ini' => 3, 'col_fin' => 12, 'row_ini' => 7, 'row_fin' => null,
            'headers' => ['Periodo','Nivel','Proceso','Persp','Objetivo', 'Indicador', 'Cumplio', 'Usuario', 'Fecha', 'Comentario']
        ]);

        $NamesList = collect([
            'jhonfredypatinoramirez' => 2133,
            'claudialuciaquinteroperez' => 2809,
            'lilianandreacastromiranda' => 1461,
            'luzadrianaloaizagomez' => 594,
            'monicajhoanacardona' => 738,
            'ximenasilva' => 3055,
            'beatrizcardonat' => 2061,
            'paolaandreaecheverri' => 892,
            'gloriamilenahernandezcifuentes' => 422, //Bonilla
        ]);

        $ProcessList = collect([
            'auditoriainterna' => 21,
            'controllegaladtvoygestionderiesgo' => 11,
            'subsidiofamiliar' => 26,
            'credito' => 28,
            'parqueaderocomfamiliar' => 18,
            'granja' => 123,
            'atencionintegralalaninez' => 38,
            'jornadasescolarescomplementarias' => 37,
            'consultaexternamedicayespecializada' => 89,
            'serviciotransfusional' => 66,
            'hemato-oncologiaambulatoria' => 110,
            'unidaddecardiologiainvasiva' => 112,
            'hospitalizacionginecobstetricia' => 57,
            'unidaddecuidadosintensivosadultos' => 63,
            'nefrologiahospitalaria' => 59,
            'mejoramientocontinuo' => 20,
            'defensoriadelusuario' => 20,
            'ipsodontologica' => 51,
            'administraciondeaportes' => 26,
            'programadeatencionaladiscapacidad' => 36
        ]);

        $Regs = [];
        $Usuarios = \App\Models\Usuario::get()->transform(function($U){
            $U['nombre_comp'] = Helper::prepValComp($U['Nombres']);
            return $U;
        })->keyBy('nombre_comp')->transform(function($U){ return $U['id']; });

        $Procesos = \App\Models\Proceso::get()->transform(function($U){
            $U['nombre_comp'] = Helper::prepValComp($U['Proceso']);
            return $U;
        })->keyBy('nombre_comp')->transform(function($U){ return $U['id']; });

        $Indicadores = \App\Models\Indicador::get([ 'id', 'Indicador', 'TipoDato', 'proceso_id' ])
        ->keyBy(function($I){
            return $I->proceso_id .'___'. strtolower(trim(str_replace(['s', ' Acum', '  '], '', $I->Indicador)));
        });

        //return $Procesos->all();
        $MissingNames = [];
        $MissingProcesos = [];
        $MissingInds = [];

        foreach ($BaseRegs as &$R) {
            if(is_null($R['Usuario'])) continue;

            //Buscar el Usuario
            $Nombre = Helper::prepValComp($R['Usuario']);
            $usuario_id = $NamesList->get($Nombre);
            if(!$usuario_id) $usuario_id =  $Usuarios->get($Nombre);

            if(!$usuario_id){
                $MissingNames[] = $Nombre;
            }

            //Buscar el proceso
            $Proceso = Helper::prepValComp($R['Proceso']);
            $proceso_id = $ProcessList->get($Proceso);
            if(!$proceso_id)  $proceso_id = $Procesos->get($Proceso);

            if(!$proceso_id){
                $MissingProcesos[] = $Proceso;
            }

            if(!$usuario_id) continue;
            if(!$proceso_id) continue;

            //Buscar el Indicador
            $indicador_uid = $proceso_id .'___'. strtolower(trim(str_replace(['s', ' Acum', '  '], '', $R['Indicador'])));
            $Indicador = $Indicadores->get($indicador_uid);

            if(!$Indicador){
                $MissingInds[] = $R['Proceso'] .' - '. $R['Indicador'];
                continue;
            }

            $R['nombre'] = $Nombre;
            $R['usuario_id'] = $usuario_id;
            $R['proceso_id'] = $proceso_id;
            $R['Indicador_sara'] = $Indicador;

            $R['Comentario'] = str_replace(
                ['¿', '_x000D_'],
                ["'", ''],
                $R['Comentario']
            );

            $UNIX_DATE = ($R['Fecha'] - 25569) * 86400;
            $R['created_at'] = gmdate("Y-m-d H:i:s", $UNIX_DATE);

            $Regs[] = $R;
        }

        if(!empty($MissingNames))    return collect($MissingNames)->unique()->toArray();
        if(!empty($MissingProcesos)) return collect($MissingProcesos)->unique()->toArray();
        //if(!empty($MissingInds)) return $MissingInds;

        foreach ($Regs as $R) {

            $DaComment = new \App\Models\Comentario([
                'Entidad' => 'Indicador', 'Entidad_id' => $R['Indicador_sara']['id'],
                'Grupo' => 'Comentario',
                'usuario_id' => $R['usuario_id'],
                'Comentario' => $R['Comentario'],
                'Op1' => $R['Periodo'],
                'created_at' => $R['created_at'],
                'updated_at' => $R['created_at']
            ]);
            $DaComment->save();
        }

        return count($Regs);
    }


}
