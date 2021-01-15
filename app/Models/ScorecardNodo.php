<?php

namespace App\Models;

use App\Models\Core\MyModel;
use App\Functions\Helper;

class ScorecardNodo extends MyModel
{
    protected $table = 'sara_scorecards_nodos';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $with = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'peso' => 'real'
	];
    protected $appends = [
    	//'elemento',
    	//'Nodo',
    	'children'
    ];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',				'id',				null, true, false, null, 100 ],
			[ 'scorecard_id',	'scorecard_id',		null, true, false, null, 100 ],
			[ 'Nodo',			'Nodo',				null, true, false, null, 100 ],
			[ 'padre_id',		'padre_id',			null, true, false, null, 100 ],
			[ 'Indice',			'Indice',			null, true, false, null, 100 ],
			[ 'tipo',			'tipo',				null, true, false, null, 100 ],
			[ 'elemento_id',	'elemento_id',		null, true, false, null, 100 ],
			[ 'peso',			'peso',				null, true, false, null, 100 ],
		];
	}

	public function scopeNodo($q, $nodo)
	{
		if($nodo) return $q->where('tipo', 'Nodo');
		if(!$nodo) return $q->where('tipo', '!=', 'Nodo');
	}

	public function scopeScorecard($query,$id)
	{
		return $query->where('scorecard_id', $id);
	}

	
	/*public function nodo_padre()
	{
		return $this->belongsTo('\App\Models\ScorecardNodo', 'padre_id');
	}*/

	/*public function nodos()
	{
		return $this->hasMany('\App\Models\ScorecardNodo', 'padre_id')->orderBy('Indice')->orderBy('Nodo');
	}*/

	/*public function getElementoAttribute()
	{
		if($this->tipo == 'Indicador'){
			return \App\Models\Indicador::where('id', $this->elemento_id)->first();
		}

		if($this->tipo == 'Variable'){
			return \App\Models\Variable::where('id', $this->elemento_id)->first();
		}
	}*/

	/*public function getNodoAttribute()
	{

		if($this->tipo == 'Indicador'){
			return $this->elemento->Indicador;
		}else if($this->tipo == 'Variable'){
			if($this->elemento) return $this->elemento->Variable;
		}else{
			return $this->attributes['Nodo'];
		}
	}*/



	public function getRuta($Nodo, $Padre)
	{
		if(is_null($Padre)){
			return '';
		}else if($Nodo->tipo == 'Nodo'){

			$RutaPadre = $Padre->Ruta;
			if($RutaPadre !== '') $RutaPadre .= '\\';
			return $RutaPadre . $Nodo->Indice . '. ' . $Nodo->Nodo;

		}else{

			return $Padre->Ruta;
		}
	}

	public function getFullRuta($Nodos)
	{
		$Nodos = collect($Nodos)->keyBy('id');

		foreach ($Nodos as &$N){
			if($N['tipo'] == 'Nodo'){
				if(is_null($N['padre_id'])){ 
					$N['Ruta'] = $N['Nodo'];
				}else{
					$N['Ruta'] = $Nodos[$N['padre_id']]['Ruta'] .'\\'. $N['Indice'] .'. '. $N['Nodo'];
				}
			}
		}

		foreach ($Nodos as &$N){
			if($N['tipo'] !== 'Nodo'){
				$N['Ruta'] = $Nodos[$N['padre_id']]['Ruta'];
			}
		}
	}


	public function getElementos($Nodos)
	{
		$var_ids = [];
		$ind_ids = [];

		foreach ($Nodos as $Nodo) {
			if($Nodo->tipo == 'Variable')  $var_ids[] = $Nodo->elemento_id;
			if($Nodo->tipo == 'Indicador') $ind_ids[] = $Nodo->elemento_id;
		};

		if(!empty($var_ids)) $Variables   = \App\Models\Variable::whereIn( 'id', $var_ids)->get()->keyBy('id');
		if(!empty($ind_ids)) $Indicadores = \App\Models\Indicador::whereIn('id', $ind_ids)->get()->keyBy('id');

		//dd($Indicadores);

		foreach ($Nodos as $Nodo) {
			if($Nodo->tipo == 'Variable')  {
				$Nodo->elemento = $Variables[$Nodo->elemento_id];
				if($Nodo->elemento) $Nodo->Nodo = $Nodo->elemento->Variable;
			}
			if($Nodo->tipo == 'Indicador') {
				$Nodo->elemento = $Indicadores[$Nodo->elemento_id];
				if($Nodo->elemento) $Nodo->Nodo = $Nodo->elemento->Indicador;
			}
		};
	}

	public function getRutas($Nodos)
	{
		foreach ($Nodos as $Nodo) {		
			$Padre = $Nodos->first(function($k, $N) use ($Nodo){ return $N['id'] == $Nodo['padre_id']; });
			$Nodo->Ruta = $this->getRuta($Nodo, $Padre);
		}
	}

	public function getChildrenAttribute()
	{
		return $this->tipo == 'Nodo' ? 1 : 0;
	}

	public function getChildren($Cascade = false, $Nodos = [], $Ruta = '')
	{
		$this->nodos = $Nodos->filter(function($E){
			return $E->padre_id == $this->id;
		})->values();

		$this->nodos_cant = count($this->nodos);
		$this->open = true;
		$this->cant_indicadores = 0;
		$this->cant_variables   = 0;

		/*foreach ($this->nodos as $Nodo) {
			if($Nodo->tipo == 'Indicador') $this->cant_indicadores++;
			if($Nodo->tipo == 'Variable')  $this->cant_variables++;
		}*/


		if($Cascade){
			if($Ruta !== '') $Ruta .= '\\';
			$Ruta .= $this->Nodo;
			$this->ruta = $Ruta;
			foreach ($this->nodos as $nodo) {
				$nodo->getChildren(true, $Nodos, $Ruta);
			}
		}
	}

	public function getValoresCache(&$Nodos, $Anio)
	{
		$nodos_ids = $Nodos->pluck('id');
		$NodosValores = \App\Models\ScorecardNodoValores::whereIn('nodo_id', $nodos_ids)->where('Anio', $Anio)->get()->keyBy('nodo_id');


		//dd($NodosValores);
		$Nodos->transform(function($N) use ($NodosValores){
			if($NodosValores->has($N->id)){
				$NodoValores = $NodosValores[$N->id];

				if($NodoValores->created_at > $N->elemento->updated_at){
					$N->valores = $NodoValores->valores['valores'];
				}
			}
			return $N;
		});

		$NodosValores = null;
		//if(!empty($DeleteNV)) \App\Models\ScorecardNodoValores::whereIn('id', $DeleteNV)->delete();

	}

	public function calculate($Periodos, &$NodoValores)
	{
		foreach ($this->nodos as $nodo) {
			$nodo->calculate($Periodos, $NodoValores);	
		}
		
		//$this->puntos_totales = $this->nodos->sum('peso');
		$Anio = round($Periodos[0]/100);

		if($this->tipo == 'Indicador' AND $this->elemento AND !$this->valores){
			$this->valores = $this->elemento->calcVals($Anio);
			$NodoValores->push([
				'nodo_id' => $this->id, 
				'Anio'    => $Anio,
				'valores' => json_encode([ 'valores' => $this->valores ])
			]);
		}

		if($this->tipo == 'Variable'  AND $this->elemento AND !$this->valores){
			$this->valores = $this->elemento->getVals( $Anio);

			$NodoValores->push([
				'nodo_id' => $this->id, 
				'Anio'    => $Anio,
				'valores' => json_encode([ 'valores' => $this->valores ])
			]);
		}

		/*
		if($this->tipo == 'Nodo'){ //Conteo de Variables e Indicadores
			foreach ($this->nodos as $subnodo) {
				if($subnodo->tipo == 'Indicador'){
					$this->cant_indicadores++;
				}

				if($subnodo->tipo == 'Variable' AND $subnodo->valores){
					$this->cant_variables++;
				}

				if($subnodo->tipo == 'Nodo'){

					$this->cant_indicadores += $subnodo->cant_indicadores;
					$this->cant_variables   += $subnodo->cant_variables;

				}
			}
		}*/
		
	}

	public function filterCumplimientos($filters)
	{
		$this->nodos = $this->nodos->filter(function($nodo) use ($filters){
			if($nodo->tipo == 'Nodo') return true;
			if($nodo->tipo == 'Variable') return ($filters['cumplimiento'] == 'no_value');

			if($nodo->tipo == 'Indicador'){

				$valor = $nodo->valores[$filters['Periodo']];

				if($filters['cumplimiento'] == 'no_value'){    return is_null($valor['cump_porc']); }
				else if($filters['cumplimiento'] == 'red'){    return (!is_null($valor['cump_porc']) AND $valor['cump_porc'] < 0.85); }
				else if($filters['cumplimiento'] == 'yellow'){ return ($valor['cump_porc'] >= 0.85   AND $valor['cump_porc'] < 1); }
				else if($filters['cumplimiento'] == 'green'){  return $valor['cump_porc'] >= 1; }
			}

			return false;
		});

		foreach ($this->nodos as $nodo) {
			if($nodo->tipo == 'Nodo') $nodo->filterCumplimientos($filters);
		}
	}

	public function recountSubnodos()
	{
		foreach ($this->nodos as $subnodo) {
			if($subnodo->tipo == 'Nodo') $subnodo->recountSubnodos();
		}

		$this->cant_variables = 0;
		$this->cant_indicadores = 0;

		foreach ($this->nodos as $subnodo) {
			if($subnodo->tipo == 'Variable')  $this->cant_variables++;
			if($subnodo->tipo == 'Indicador') $this->cant_indicadores++;
			if($subnodo->tipo == 'Nodo'){
				$this->cant_variables   += $subnodo->cant_variables;
				$this->cant_indicadores += $subnodo->cant_indicadores;
			}
		}
	}

	public function purgeNodos()
	{
		$this->nodos = $this->nodos->filter(function($nodo){
			if($nodo->tipo != 'Nodo') return true;
			return ($nodo->cant_indicadores + $nodo->cant_variables) > 0;
		});

		foreach ($this->nodos as $nodo) {
			if($nodo->tipo == 'Nodo') $nodo->purgeNodos();
		}
	}



	public function calculateNodos($Periodos)
	{
		foreach ($this->nodos as $nodo) {
			if($nodo->tipo == 'Nodo') $nodo->calculateNodos($Periodos);	
		}

		if($this->tipo == 'Nodo'){

			$this->puntos_totales = $this->nodos->sum('peso');
			$calc = array_fill_keys($Periodos, [ 'puntos' => 0, 'incalculables' => 0 ]);

			foreach ($this->nodos as $subnodo) {
				
				if($subnodo->tipo == 'Indicador'){
					
					$cum_porc_total = 0;
					$meses_calculables = 0;

					foreach ($subnodo->valores as $per => $val) {

						if($val['calculable']){
							$calc[$per]['puntos'] += $subnodo->peso * $val['cump_porc'];
							$cum_porc_total += $val['cump_porc'];
							$meses_calculables++;
						}else{
							$calc[$per]['incalculables']++;
						}
					}

					$cump_porc_prom = ($meses_calculables > 0) ? ($cum_porc_total / $meses_calculables) : 0;
					$subnodo->cump_porc_prom = $cump_porc_prom;
				}

				if($subnodo->tipo == 'Variable' AND $subnodo->valores){
					
					foreach ($Periodos as $per){

						if(!is_null($subnodo->valores[$per]['Valor'])){
							$calc[$per]['puntos'] += $subnodo->peso;
						}else{
							//$calc[$per]['incalculables']++;
						}
					}

					$subnodo->cump_porc_prom = 1;
				}

				if($subnodo->tipo == 'Nodo'){

					foreach ($subnodo->calc as $per => $cal) {
						if($cal['calculable']){
							$calc[$per]['puntos'] += $subnodo->peso * $cal['Valor'];
						}else{
							$calc[$per]['incalculables']++;
						}
					}

					$subnodo->cump_porc_prom = 1000;
				}
			}

			foreach ($calc as &$c) {
				$c['Valor'] = $this->puntos_totales > 0 ? round($c['puntos'] / $this->puntos_totales, 3) : 0;
				$c['val'] = Helper::formatVal($c['Valor'], 'Porcentaje', 1);
				$c['color'] = Helper::getIndicatorColor($c['Valor'], 'B');
				$c['calculable'] = $c['incalculables'] < $this->nodos_cant;
			}

			$this->calc = $calc;
		}
	}

	public function reorder($filters)
	{
		foreach ($this->nodos as $subnodo) {
			$subnodo->reorder($filters);
		}

		$this->nodos = $this->nodos->sortBy(function($N) use ($filters){
			if($N->tipo == 'Nodo') return 1000;
			if($N->tipo == 'Variable') return 1;
			if($N->tipo == 'Indicador'){
				$cump_porc = $N->valores[$filters['Periodo']]['cump_porc'];
				return is_null($cump_porc) ? -1 : $cump_porc;
			}
		});

		//$this->nodos = $this->nodos->sortBy('cump_porc_prom');
	}

	public function flatten(&$NodosFlat, $depth, $open_to_level)
	{
		$open = ( $depth <  $open_to_level );
		$show = ( $depth <= $open_to_level );

		$NodosFlat[] = [
			'id' => $this->id, 'Nodo' => $this->Nodo, 
			'depth' => $depth, 'tipo' => $this->tipo, 'nodos_cant' => $this->nodos_cant, 
			'cant_indicadores' => $this->cant_indicadores, 'cant_variables' => $this->cant_variables, 
			'calc' => $this->calc, 'valores' => $this->valores, 'elemento' => $this->elemento, 'open' => $open, 'show' => $show,
			'ruta' => $this->ruta, 'cump_porc_prom' => $this->cump_porc_prom
		];

		$depth++;
		foreach ($this->nodos as $nodo) {
			$nodo->flatten($NodosFlat, $depth, $open_to_level);
		}
	}

	public function pluck_procesos(&$Procesos)
	{
		if($this->elemento && $this->elemento['proceso']){
			$Procesos[$this->elemento['proceso']['id']] = $this->elemento['proceso'];
		};

		foreach ($this->nodos as $nodo) {
			$nodo->pluck_procesos($Procesos);
		}
	}

	public function reindexar()
	{
		//Subnodos
        $Subnodos = \App\Models\ScorecardNodo::where('Tipo', 'Nodo')->where('padre_id', $this->id)->orderBy('Indice')->get();
        foreach ($Subnodos as $k => $Subnodo) {
            $newIndex = ($k + 1);
            if($Subnodo->Indice != $newIndex){
                $Subnodo->Indice = $newIndex;
                $Subnodo->save();
            }
        }

        //Indicadores
        $Indicadores = \App\Models\ScorecardNodo::where('Tipo', '<>','Nodo')->where('padre_id', $this->id)->orderBy('Indice')->get();
        foreach ($Indicadores as $k => $IndNodo) {
            $newIndex = ($k + 1);
            if($IndNodo->Indice != $newIndex){
                $IndNodo->Indice = $newIndex;
                $IndNodo->save();
            }
        }
	}

}
