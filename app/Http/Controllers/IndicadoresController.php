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


    //Scorecards
    public function postScorecardsAll()
    {
        $Scorecards = Scorecard::all();
        return $Scorecards;
    }

    public function postScorecards()
    {
        $CRUD = new CRUD('App\Models\Scorecard');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postScorecardsCards()
    {
        $CRUD = new CRUD('App\Models\ScorecardCard');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postScorecardGet()
    {
        $Anio = request('Anio');
        $Sco = Scorecard::where('id', request('id'))->first();
        $elementos = [ 'Indicador' => [], 'Variable' => [] ];

        $Sco->cards = $Sco->cards()->get()->transform(function($C) use ($Sco){
            $Seccion = (array_key_exists($C->seccion_id, $Sco->Secciones)) ? $Sco->Secciones[$C->seccion_id] : null;
            $C->seccion_name = $Seccion;
            $C->animation_delay = rand(100,400) . "ms";
            return $C;
        });

        foreach ($Sco->cards as $C) {
            $elementos[$C['tipo']][$C['elemento_id']] = null;
        };

        foreach ($elementos as $tipo => &$elms) {
            foreach ($elms as $elm_id => $elm) {
                
                if($tipo == 'Indicador'){
                    $e = Indicador::where('id', $elm_id)->first();
                    $e->valores = $e->calcVals($Anio);
                    $elms[$elm_id] = $e;  
                };

                if($tipo == 'Variable'){
                    $e = Variable::where('id', $elm_id)->first();
                    $e->valores = $e->valores()->get()->keyBy('Periodo')->transform(function($v) use ($e){
                        $v->formatVal($e->TipoDato, $e->Decimales);
                        return [ 'val' => $v->val, 'Valor' => $v->Valor ];
                    });
                    $elms[$elm_id] = $e;  
                };

            }
        }

        $Sco->elementos = $elementos;

        return $Sco;
    }


}
