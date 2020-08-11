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

	public function calculate($Periodos)
	{
		foreach ($this->nodos as $nodo) {
			$nodo->calculate($Periodos);	
		}
		
		$this->puntos_totales = $this->nodos->sum('peso');
		if($this->tipo == 'Indicador' AND $this->elemento) $this->valores = $this->elemento->calcVals(round($Periodos[0]/100));
		if($this->tipo == 'Variable'  AND $this->elemento) $this->valores = $this->elemento->getVals( round($Periodos[0]/100));
		if($this->tipo == 'Nodo'){
			$calc = array_fill_keys($Periodos, [ 'puntos' => 0, 'incalculables' => 0 ]);

			foreach ($this->nodos as $subnodo) {
				
				if($subnodo->tipo == 'Indicador'){
					
					$this->cant_indicadores++;

					foreach ($subnodo->valores as $per => $val) {

						if($val['calculable']){
							$calc[$per]['puntos'] += $subnodo->peso * $val['cump_porc'];
						}else{
							$calc[$per]['incalculables']++;
						}
					}
				}

				if($subnodo->tipo == 'Variable' AND $subnodo->valores){
					
					$this->cant_variables++;

					foreach ($Periodos as $per){

						if(!is_null($subnodo->valores[$per]['Valor'])){
							$calc[$per]['puntos'] += $subnodo->peso;
						}else{
							$calc[$per]['incalculables']++;
						}
					}
				}

				if($subnodo->tipo == 'Nodo'){

					$this->cant_indicadores += $subnodo->cant_indicadores;
					$this->cant_variables   += $subnodo->cant_variables;

					foreach ($subnodo->calc as $per => $cal) {
						if($cal['calculable']){
							$calc[$per]['puntos'] += $subnodo->peso * $cal['Valor'];
						}else{
							$calc[$per]['incalculables']++;
						}
					}
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

	public function flatten(&$NodosFlat, $depth, $open_to_level)
	{
		$open = ( $depth <  $open_to_level );
		$show = ( $depth <= $open_to_level );

		$NodosFlat[] = [
			'id' => $this->id, 'Nodo' => $this->Nodo, 
			'depth' => $depth, 'tipo' => $this->tipo, 'nodos_cant' => $this->nodos_cant, 
			'cant_indicadores' => $this->cant_indicadores, 'cant_variables' => $this->cant_variables, 
			'calc' => $this->calc, 'valores' => $this->valores, 'elemento' => $this->elemento, 'open' => $open, 'show' => $show,
			'ruta' => $this->ruta
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

}
