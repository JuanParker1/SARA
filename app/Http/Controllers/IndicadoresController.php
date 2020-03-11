<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;
use App\Models\Indicador;
use App\Models\IndicadorVariable;
use App\Models\Variable;
use App\Models\VariableValor;
use App\Models\Scorecard;
use App\Functions\Helper;

class IndicadoresController extends Controller
{
    //Variables
    public function postIndex()
    {
        $CRUD = new CRUD('App\Models\Indicador');
        return $CRUD->call(request()->fn, request()->ops);
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


}
