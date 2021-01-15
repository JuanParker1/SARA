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
use App\Models\ScorecardNodoValores;
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

    /*public function postGet()
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
    }*/

    public function postGetProcesos()
    {
        extract(request()->all()); //$id
        $Sco = Scorecard::where('id', $id)->first();
        $ScoN = new ScorecardNodo();

        $Nodos = ScorecardNodo::scorecard($Sco->id)->get();
        $ScoN->getElementos($Nodos);
        $Nodo = $Nodos->first(function($k, $E){ return is_null($E->padre_id); });

        $Procesos  = [];
        $Nodo->getChildren(true, $Nodos);
        $Nodo->pluck_procesos($Procesos);

        $RutasProcesos = array_column($Procesos, 'Ruta');
        array_multisort($RutasProcesos, SORT_ASC, $Procesos);

        return $Procesos;
    }

    public function postGet()
    {
        extract(request()->all()); //$id, $Anio, $filters
        set_time_limit(10*60);
        ini_set('memory_limit','2G');

        $Periodos = Helper::getPeriodos(($Anio*100)+01,($Anio*100)+12);
        $Sco = Scorecard::where('id', $id)->first();
        $ScoN = new ScorecardNodo();

        $Nodos = ScorecardNodo::scorecard($Sco->id)->get();
        
        $ScoN->getElementos($Nodos);
        $ScoN->getRutas($Nodos);

        //Filtrar procesos
        if($filters AND $filters['proceso_ruta']){
            $Nodos = $Nodos->filter(function($N) use ($filters){
                if(!$N['elemento']) return true;
                return substr($N['elemento']['proceso']['Ruta'], 0, strlen($filters['proceso_ruta'])) === $filters['proceso_ruta'];
            });
        }

    

        $ScoN->getValoresCache($Nodos, $Anio);


        //dd($Nodos[10]);

        $Nodo = $Nodos->first(function($k, $E){ return is_null($E->padre_id); });

        
        $NodosFlat = [];
        $NodoValores = collect([]);
        
        $Nodo->getChildren(true, $Nodos);
        $Nodo->calculate($Periodos, $NodoValores);
        if($filters AND $filters['cumplimiento']){
            $Nodo->filterCumplimientos($filters);
        }
        $Nodo->recountSubnodos();
        $Nodo->purgeNodos();
        $Nodo->calculateNodos($Periodos);
        $Nodo->reorder($filters);

        if(count($NodoValores) > 0){
            $nodos_ids = $NodoValores->pluck('nodo_id');
            ScorecardNodoValores::whereIn('nodo_id', $nodos_ids)->where('Anio', $Anio)->delete();
            ScorecardNodoValores::insert($NodoValores->toArray());
        }

        $Nodo->flatten($NodosFlat, 0, $Sco->config['open_to_level']);


        foreach ($NodosFlat as $i => &$N) { $N['i'] = $i; };

        $Sco->nodo = $Nodo;
        $Sco->nodos_flat = $NodosFlat;

        return $Sco;
    }

    public function getGet()
    {
        return $this->postGet();
    }

    public function postReindexar()
    {
        extract(request()->all()); //$Nodo

        $Nodo = ScorecardNodo::where('id', $Nodo['id'])->first();
        $Nodo->reindexar();
    }

    public function postMoveInds()
    {
        extract(request()->all()); //$Inds, $nodo_destino_id

        $NodoOrigen  = ScorecardNodo::where('id', $Inds[0]['padre_id'])->first();
        $NodoDestino = ScorecardNodo::where('id', $nodo_destino_id)->first();

        $newIndice = ScorecardNodo::where('padre_id', $NodoDestino->id)->where('Tipo', '<>','Nodo')->count();

        foreach ($Inds as $Ind) {
            ScorecardNodo::where('id', $Ind['id'])->update([ 'padre_id' => $NodoDestino->id, 'Indice' => $newIndice ]);
            $newIndice++;
        }

        $NodoOrigen->reindexar();
        $NodoDestino->reindexar();

        return compact('NodoOrigen', 'NodoDestino', 'newIndice');

    }

    public function postEraseCache()
    {
        extract(request()->all()); //$Inds
        $nodos_ids = collect($Inds)->pluck('id');
        $NodosValores =  \App\Models\ScorecardNodoValores::whereIn('nodo_id', $nodos_ids)->delete();
    }
}
