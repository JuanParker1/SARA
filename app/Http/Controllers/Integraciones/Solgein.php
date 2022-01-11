<?php

namespace App\Http\Controllers\Integraciones;

use Carbon\Carbon;
use App\Functions\Helper;

class Solgein {
	
	public static function upload_valores()
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


	public static function upload_comments()
	{
		set_time_limit(5*60);

        $folder   = 'temp';
        $filename = 'Import_SOLGEIN_comments.xls';
        request()->file('file')->move($folder, $filename);

        $BaseRegs = Helper::readTableFile($folder.'/'.$filename, [
            'col_ini' => 3, 'col_fin' => 12, 'row_ini' => 7, 'row_fin' => null,
            'headers' => ['Periodo', 'NivelCod', 'NivelDescri', 'Persp', 'Objetivo', 'Indicador', 'Cumplio', 'Usuario', 'Fecha', 'Comentario']
        ])->filter(function($R){
        	return !is_null($R['Usuario']);
        })->map(function($R){
        	$R['usuario_comp']   = Helper::prepValComp($R['Usuario']);
        	$R['niveldesc_comp'] = Helper::prepValComp($R['NivelDescri']);
        	$R['usuario_id'] = null;
            $R['proceso_id'] = null;
            $R['Indicador_sara'] = null;
        	return $R;
        });

        $Usuarios = \App\Models\Usuario::withTrashed()->get()->transform(function($U){
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

        $MissingNombres = [];
        $MissingProcesos = [];
        $MissingInds = [];
        $Regs = [];
        $Status = 'Ended';
        $AddedComments = 0;

        foreach ($BaseRegs as $R) {

            //Buscar el Usuario
            $usuario_id = $Usuarios->get($R['usuario_comp']);
            if(!$usuario_id){
                if(!in_array($R['Usuario'], $MissingNombres)) $MissingNombres[] = $R['Usuario'];
            }

            //Buscar el Proceso
            $proceso_id = $Procesos->get($R['niveldesc_comp']);
            if(!$proceso_id){
                if(!in_array($R['NivelDescri'], $MissingProcesos)) $MissingProcesos[] = $R['NivelDescri'];
            }

            if(!$usuario_id OR !$proceso_id) continue;

            //Buscar el Indicador
            $indicador_uid = $proceso_id .'___'. strtolower(trim(str_replace(['s', ' Acum', '  '], '', $R['Indicador'])));
            $Indicador = $Indicadores->get($indicador_uid);

            if(!$Indicador){
                $MissingInds[] = $R['NivelDescri'] .' - '. $R['Indicador'];
                continue;
            }

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

        if(count($MissingNombres) > 0 OR count($MissingProcesos) > 0){
        	$Status = 'Error';
        }else{

        	foreach ($Regs as $R) {

        		$CommentArr = [
	                'Entidad' => 'Indicador', 
	                'Entidad_id' => $R['Indicador_sara']['id'],
	                'Grupo' => 'Comentario',
	                'usuario_id' => $R['usuario_id'],
	                'Comentario' => $R['Comentario'],
	                'Op1' => $R['Periodo'],
	                'created_at' => $R['created_at'],
	                'updated_at' => $R['created_at']
	            ];

        		$DaComment = \App\Models\Comentario::where($CommentArr)->first();

        		if(!$DaComment){
        			$NewComment = new \App\Models\Comentario($CommentArr);
	            	$NewComment->save();
	            	$AddedComments++;
        		}
	        }

        }

        return compact('AddedComments','Status','BaseRegs','Regs','MissingNombres', 'MissingProcesos', 'MissingInds');
	}


}