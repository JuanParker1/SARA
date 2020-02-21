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
use App\Models\ScorecardNodo;
use App\Functions\Helper;

class ScorecardsController extends Controller
{
    //Scorecards
    public function postAll()
    {
        $Scorecards = Scorecard::all();
        return $Scorecards;
    }

    public function postIndex()
    {
        $CRUD = new CRUD('App\Models\Scorecard');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postCards()
    {
        $CRUD = new CRUD('App\Models\ScorecardCard');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postNodos()
    {
        $CRUD = new CRUD('App\Models\ScorecardNodo');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postGet()
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

    public function getTest()
    {
        $Sco = Scorecard::where('id', 1)->first();

        $Nodos = ScorecardNodo::scorecard($Sco->id)->get();

        return $Nodos;
    }
}
