<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;
use App\Models\Indicador;
use App\Models\IndicadorVariable;
use App\Models\IndicadorValor;
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

    public function calcScorecard($id, $Anio, $filters = [])
    {
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

        //filtrar frecuencia analisis
        if($filters AND $filters['frecuencia_analisis']){
            if(!in_array('-1', $filters['frecuencia_analisis'])){
                $Nodos = $Nodos->filter(function($N) use ($filters){
                    if(!$N['elemento']) return true;
                    if($N['tipo'] == 'Indicador') return in_array($N['elemento']['FrecuenciaAnalisis'], $filters['frecuencia_analisis']);
                    if($N['tipo'] == 'Variable')  return in_array($N['elemento']['Frecuencia'],         $filters['frecuencia_analisis']);
                    return true;   
                }); 
            }
        }

        $ScoN->getValoresCache($Nodos, $Anio);

        $Nodo = $Nodos->first(function($k, $E){ return is_null($E->padre_id); });

        
        $NodosFlat = [];
        $IndicadorValores = collect([]);
        
        $Nodo->getChildren(true, $Nodos);
        $Nodo->calculate($Periodos, $IndicadorValores);
        if($filters AND $filters['cumplimiento']){
            $Nodo->filterCumplimientos($filters);
        }
        $Nodo->recountSubnodos();
        $Nodo->purgeNodos();

        $calc_method = $Sco->config['calc_method'];
        $Nodo->calculateNodos($Periodos, $calc_method);
        
        if($filters AND $filters['order_by'] !== 'default') $Nodo->reorder($filters);

        if(count($IndicadorValores) > 0){
            $indicadores_ids = $IndicadorValores->pluck('indicador_id');
            IndicadorValor::whereIn('indicador_id', $indicadores_ids)->where('Anio', $Anio)->delete();
            IndicadorValor::insert($IndicadorValores->toArray());
        }
        
        $Nodo->flatten($NodosFlat, 0, $Sco->config['open_to_level']);

        foreach ($NodosFlat as $i => &$N) { $N['i'] = $i; };
        $Sco->nodos_flat = $NodosFlat;
        $Sco->nodos_flat_show = [];

        return $Sco;
    }

    public function postGet()
    {
        extract(request()->all()); //$id, $Anio, $filters
        return $this->calcScorecard($id, $Anio, $filters);
    }

    public function getGet()
    {
        return $this->postGet();
    }

    public function postGetHead()
    {
        extract(request()->all()); //$id
        $Sco = Scorecard::where('id', $id)->first();
        return $Sco;
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
        $inds_ids = collect($Inds)->filter(function($N){ return $N['tipo'] == 'Indicador'; })->map(function($N){ return $N['elemento']['id']; });
        \App\Models\IndicadorValor::whereIn('indicador_id', $inds_ids)->delete();
    }


    public function getRawData($code, $year)
    {
        $Scorecard = Scorecard::get()->first(function($kS,$S) use ($code){
            return $S['config']['data_code'] == $code;
        });

        if(!$Scorecard) abort(512, 'scorecard no encontrado');

        $Sco = $this->calcScorecard($Scorecard->id, $year);

        $datos = [];

        //Obtener niveles
        $Niveles = 0;
        foreach ($Sco['nodos_flat'] as $N) {   
            if($N['tipo'] == 'Nodo'){
                $RutaArr = explode("\\", $N['ruta']);
                $Niveles = max($Niveles, count($RutaArr));
            }
        }

        foreach ($Sco['nodos_flat'] as $N) {
            
            if($N['tipo'] == 'Nodo'){
                
                $RutaArr = explode("\\", $N['ruta']);
                $BaseArr = [
                    'Ruta' => $N['ruta']
                ];
                for ($i=1; $i <= $Niveles; $i++) {
                    $BaseArr['Nivel_'.$i] = array_key_exists(($i-1), $RutaArr) ? $RutaArr[($i-1)] : null;
                }
                foreach ($N['calc'] as $Periodo => $V) {
                    $Row = [
                        'Scorecard'     => $Scorecard->Titulo,
                        'Tipo'          => 'Nodo', 'Nodo' => $N['Nodo'], 'Peso' => $N['peso'], 
                        'Periodo'       => $Periodo,
                        'Valor'         => $V['Valor'],      'Valor_Formateado' => $V['val'],
                        'Meta'          => null,             'Meta_Formateado'  => null,
                        'Cumplimiento'  => null, 
                        'Color'         => $V['color'],
                        'Calculable'    => $V['calculable'], 'Nivel' => $N['depth'],
                        'Proceso'       => null,
                        'Proceso_Ruta'  => null,
                        'Frecuencia_Analisis' => 1,
                        'Sentido'       => null,
                        'TipoDato'      => null,
                    ];

                    $Row = array_merge($Row, $BaseArr);

                    $datos[] = $Row;
                }

            }else{

                $RutaArr = explode("\\", $N['ruta']); array_pop($RutaArr);
                $BaseArr = [
                    'Ruta' => $N['ruta']
                ];
                for ($i=1; $i <= $Niveles; $i++) {
                    $BaseArr['Nivel_'.$i] = array_key_exists(($i-1), $RutaArr) ? $RutaArr[($i-1)] : null;
                }

                foreach ($N['valores'] as $Periodo => $V) {
                    $Row = [
                        'Scorecard'     => $Scorecard->Titulo,
                        'Tipo'          => $N['tipo'], 'Nodo' => $N['Nodo'], 'Peso' => $N['peso'], 
                        'Periodo'       => $Periodo,
                        'Valor'         => $V['Valor'],      'Valor_Formateado' => $V['val'],
                        'Meta'          => $V['meta_Valor'], 'Meta_Formateado'  => $V['meta_val'],
                        'Cumplimiento'  => $V['cump_porc'], 
                        'Color'         => $V['color'],
                        'Calculable'    => $V['calculable'], 'Nivel' => $N['depth'],
                        'Proceso'       => $N['elemento']['proceso']['Proceso'],
                        'Proceso_Ruta'  => $N['elemento']['proceso']['Ruta'],
                        'Frecuencia_Analisis' => $N['elemento']['FrecuenciaAnalisis'],
                        'Sentido'       => $N['elemento']['Sentido'],
                        'TipoDato'      => $N['elemento']['TipoDato'],
                    ];

                    $Row = array_merge($Row, $BaseArr);

                    $datos[] = $Row;
                }

            }

        }

        return $datos;
    }

}
