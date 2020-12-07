<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;
use App\Models\Indicador;
use App\Models\IndicadorVariable;
use App\Models\IndicadorMeta;
use App\Models\Variable;
use App\Models\VariableValor;
use App\Models\Scorecard;
use App\Models\Proceso;
use App\Functions\Helper;

class IndicadoresController extends Controller
{
    //Variables
    public function postIndex()
    {
        $CRUD = new CRUD('App\Models\Indicador');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postAddIndicador()
    {
        extract(request()->all()); //$newInd

        $DaInd = new Indicador();
        $DaInd->fillit_columns($newInd);
        $DaInd->save();

        foreach ($newInd['variables'] as $k => $Var ) {
            
            $DaVar = new Variable();
            $DaVar->fillit_columns($Var);
            $DaVar->proceso_id = $DaInd->proceso_id;
            $DaVar->save();

            $DaIndVar = new IndicadorVariable([
                'indicador_id' => $DaInd->id,
                'Letra'        => strtolower(\PHPExcel_Cell::stringFromColumnIndex(($k))),
                'Tipo'         => 'Variable',     
                'variable_id'  => $DaVar->id,
            ]);
            $DaIndVar->save();
        }

        if($newInd['Meta']){
            $DaMeta = new IndicadorMeta([
                'indicador_id' => $DaInd->id,
                'PeriodoDesde' => 200001,
                'Meta'         => $newInd['Meta']
            ]);
            $DaMeta->save();
        }

        //return $DaInd;
    }

    public function postGet()
    {
        $Anio = request('Anio');

        $Indicador = Indicador::where('id', request('id'))->first();
        
        $AnioAnt = $Indicador->calcVals($Anio-1);
        $AnioAct = $Indicador->calcVals($Anio);

        $def = array_fill_keys(['varMoM','varMoM_val','varYoY','varYoY_val'],null);
        foreach ($AnioAct as $periodo => &$v) {
            $v = array_merge($v,$def);

            $mes  = $v['mes'];

            $lastMes = ($mes == 1) ? $AnioAnt[(($Anio-1)*100)+12] : $AnioAct[($Anio*100)+($mes-1)];
            $lastYear = $AnioAnt[(($Anio-1)*100)+$mes];

            if(!is_null($lastYear['Valor'])){
                $v['anioAnt']       = $lastYear['Valor'];
                $v['anioAnt_val']   = Helper::formatVal($v['anioAnt'],$Indicador->TipoDato,$Indicador->Decimales);
                $v['anioAnt_color'] = $lastYear['color'];;
                $v['anioAnt_meta_val'] = $lastYear['meta_val'];;
            };

            if(is_null($v['Valor'])) continue;

            if(!is_null($lastMes['Valor'])){
                $v['varMoM']     = round($v['Valor']-$lastMes['Valor'],($Indicador->Decimales + 2));
                $v['varMoM_val'] = Helper::formatVal($v['varMoM'],$Indicador->TipoDato,$Indicador->Decimales);
            };
            
            if(!is_null($lastYear['Valor'])){
                $v['varYoY']     = round($v['Valor']-$lastYear['Valor'],($Indicador->Decimales + 2));
                $v['varYoY_val'] = Helper::formatVal($v['varYoY'],$Indicador->TipoDato,$Indicador->Decimales);
            };

        };

        $Indicador->valores = $AnioAct;
        return $Indicador;
    }

    public function postGetUsuario()
    {
        extract(request()->all()); //Usuario, Anio
        $ProcesosIds = collect($Usuario['Procesos'])->pluck('id')->toArray();
        $Indicadores = Indicador::whereIn('proceso_id', $ProcesosIds)->get();

        foreach ($Indicadores as $I) {
            $I['valores'] = $I->calcVals($Anio);
        }

        return $Indicadores;
    }



    public function postGetDesagregacion()
    {
        extract(request()->all()); //Indicador, Anio, desag_campos
        
        $Variables = [];
        $Resultados = [];

        $PeriodoIni = ($Anio*100)+1;
        $PeriodoFin = ($Anio*100)+12;
        $Periodos = Helper::getPeriodos( $PeriodoIni, $PeriodoFin );
        $decimales = ($Indicador['TipoDato'] == 'Porcentaje') ? $Indicador['Decimales'] + 2 : $Indicador['Decimales'];

        foreach ($Indicador['variables'] as $V) {
            $Var = Variable::where('id', $V['variable_id'])->first();
            $Desagregated = $Var->getDesagregated( $PeriodoIni, $PeriodoFin, $desag_campos, false);
            $Variables[] = $Desagregated;

            foreach ($Desagregated as $key => $Grupo) {
                if(!array_key_exists($key, $Resultados)) $Resultados[$key] = [ 'Llave' => $key, 'valores' => [] ];
            }
        }

        //return $Variables;

        foreach ($Resultados as $keyArr => &$Arr) {
            foreach ($Periodos as $Periodo) {

                //Obtener Comps
                $comps = [];
                $comps_vals = [];
                foreach ($Indicador['variables'] as $kV => $V) {


                    if(is_null($Variables[$kV]->get($keyArr))){
                        $Valor = null;
                        $val = null;
                    }else{
                        $ValoresArr = $Variables[$kV][$keyArr]['valores'];
                        $Valor = array_key_exists($Periodo, $ValoresArr) ? $ValoresArr[$Periodo]['Valor'] : null;
                        $val   = array_key_exists($Periodo, $ValoresArr) ? $ValoresArr[$Periodo]['val']   : null;
                    }

                    $comps[$V['Letra']] = $Valor;
                    $comps_vals[] = $val;
                }

                $Meta  = $Indicador['valores'][$Periodo]['meta_Valor'];
                $Meta2 = $Indicador['valores'][$Periodo]['meta2_Valor'];

                $Valor      = Helper::calcFormula( $Indicador['Formula'], $comps, $decimales );
                $val        = Helper::formatVal($Valor, $Indicador['TipoDato'], $Indicador['Decimales']);
                $cump       = Helper::calcCump($Valor, $Meta, $Indicador['Sentido'], 'bool', $Meta2);
                $cump_porc  = Helper::calcCump($Valor, $Meta, $Indicador['Sentido'], 'porc', $Meta2);
                $color      = Helper::getIndicatorColor($cump_porc);


                $Arr['valores'][$Periodo] = compact('Periodo', 'Valor', 'val', 'comps_vals', 'cump', 'cump_porc', 'color');
            }
        }

        return [ 'valores' => $Resultados, 'Variables' => $Variables ];

    }




    //Variables
    public function postVariables()
    {
        $CRUD = new CRUD('App\Models\IndicadorVariable');
        return $CRUD->call(request()->fn, request()->ops);
    }

    //Metas
    public function postMetas()
    {
        $CRUD = new CRUD('App\Models\IndicadorMeta');
        return $CRUD->call(request()->fn, request()->ops);
    }






    public function getLoadSolgein()
    {
        set_time_limit(5*60);
        \Excel::setDelimiter(';');

        $regs = \Excel::selectSheetsByIndex(0)->load('temp/Indicadores Faltantes20200917.xlsx', function($reader){   
        })->get()->transform(function($row){
            $row['indicador'] = trim($row['indicador']);
            return $row;
        });

        //return $regs;

        $inds = collect($regs)->filter(function($i){
            return true;
            //return ($i['estado'] == 'A');
        })->map(function($i){
            
            $Ind = [
                'proceso_id' => $i['proceso_id'],
                'Indicador'  => $i['indicador'],
                'Definicion' => $i['definicion'],
                'TipoDato'   => $i['tipodato'],
                'Decimales'  => $i['decimales'],
                'Formula'    => $i['formula'],
                'Sentido'    => $i['sentido'],
                'componentes' => [],
            ];

            foreach (['a', 'b'] as $letra) {
                $variable = $i['variable_'.$letra];

                if(is_null($variable)) continue;

                $TipoDato = ( $i['tipodato'] == 'Moneda' ) ? 'Moneda' : 'Numero';

                $Ind['componentes'][$letra] = [
                    'proceso_id' => $i['proceso_id'],
                    'Variable' => trim($variable),
                    'TipoDato'  => $TipoDato,
                    'Decimales' => 0,
                    'Tipo'      => 'Manual',
                    'Frecuencia' => $i['frecuencia'],
                    'Filtros'   => []
                ];

            }

            return $Ind;

        })->values();

        

        foreach ($inds as $ind) {
        
            $DaInd = new Indicador($ind);
            unset($DaInd->componentes);

            $Found = Indicador::where('proceso_id', $DaInd->proceso_id)->where('Indicador', $DaInd->Indicador)->first();
            if(!$Found){
                $DaInd->save(); 

                foreach ($ind['componentes'] as $letra => $variable) {
                    
                    $DaVar = Variable::where('proceso_id', $variable['proceso_id'])->where('Variable', $variable['Variable'])->first();

                    if(!$DaVar){
                        $DaVar = new Variable($variable);
                        $DaVar->save();
                    }else{
                        //dd($variable);
                    }

                    $DaIndVar = new IndicadorVariable([
                        'indicador_id' => $DaInd->id,
                        'Letra'        => $letra,
                        'Tipo'         => 'Variable',
                        'variable_id'  => $DaVar->id,
                    ]);

                    $DaIndVar->save();

                }


            }else{
                //dd($ind);
            }

        }


             
    }






    public function getLoadSolgeinMetas()
    {
        \Excel::setDelimiter(';');

        $regs = \Excel::selectSheetsByIndex(3)->load('temp/Indicadores_SOLGEIN.xlsx', function($reader){   
        })->get();

        foreach ($regs as $reg) {
            
            $DaInd = Indicador::where('proceso_id', $reg['proceso_id'])->where('Indicador', $reg['indicador'])->first();

            if($DaInd){

                $UltimaMeta = IndicadorMeta::where('indicador_id', $DaInd->id)->orderBy('PeriodoDesde', 'DESC')->first();

                if(!$UltimaMeta AND !is_null($reg['meta'])){

                    $newMeta = new IndicadorMeta([
                        'indicador_id' => $DaInd->id,
                        'Meta'         => $reg['meta']
                    ]);

                    //dd($newMeta);

                    $newMeta->save();
                }

            }

        }

        return $regs;

    }


    public function getLoadSolgeinPersp()
    {
        \Excel::setDelimiter(';');
        set_time_limit(5*60);

        $regs = \Excel::selectSheetsByIndex(3)->load('temp/Indicadores_SOLGEIN.xlsx', function($reader){   
        })->get();

        $Saved = 0;

        foreach ($regs as $reg) {
            
            $DaInd = Indicador::where('proceso_id', $reg['proceso_id'])->where('Indicador', $reg['indicador'])->first();

            if($DaInd){

                $NodoAct =  \App\Models\ScorecardNodo::where('scorecard_id', 9)->where('tipo', 'Indicador')->where('elemento_id', $DaInd['id'])->first();

                if(!$NodoAct){

                    //dd($DaInd);

                    $newNodo = new \App\Models\ScorecardNodo([
                        'scorecard_id' => 9,
                        'Nodo'         => $reg['indicador'],
                        'padre_id'     => intval($reg['proceso_id']),
                        'Indice'       => 0,
                        'tipo'         => 'Indicador',
                        'elemento_id'  => $DaInd['id'],
                        'peso'         => 1
                    ]);

                    $newNodo->save();
                    $Saved++;
                }

            }else{

                dd($reg);

            }

        }

        return [ count($regs), $Saved ];

    }




}
