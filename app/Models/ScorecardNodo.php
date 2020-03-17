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
    protected $appends = ['elemento','Nodo','children'];

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

	
	public function nodo_padre()
	{
		return $this->belongsTo('\App\Models\ScorecardNodo', 'padre_id');
	}

	public function nodos()
	{
		return $this->hasMany('\App\Models\ScorecardNodo', 'padre_id')->orderBy('Indice')->orderBy('Nodo');
	}

	public function getElementoAttribute()
	{
		if($this->tipo == 'Indicador'){
			return \App\Models\Indicador::where('id', $this->elemento_id)->first();
		}

		if($this->tipo == 'Variable'){
			return \App\Models\Variable::where('id', $this->elemento_id)->first();
		}
	}

	public function getNodoAttribute()
	{

		if($this->tipo == 'Indicador'){
			return $this->elemento->Indicador;
		}else if($this->tipo == 'Variable'){
			if($this->elemento) return $this->elemento->Variable;
		}else{
			return $this->attributes['Nodo'];
		}
	}



	public function getRuta($Base = false)
	{
		if(is_null($this->padre_id)){
			$this->Ruta = ($Base) ? $Base : '';
		}else if($this->tipo == 'Nodo'){
			$this->nodo_padre->getRuta();
			$RutaPadre = $this->nodo_padre->Ruta;
			if($RutaPadre !== '') $RutaPadre .= '\\';
			$this->Ruta = $RutaPadre . $this->Indice . '. ' . $this->Nodo;
		}else{
			$this->nodo_padre->getRuta();
			$this->Ruta = $this->nodo_padre->Ruta;
		}
	}

	public function getChildrenAttribute()
	{
		return $this->tipo == 'Nodo' ? 1 : 0;
	}

	public function getChildren($Cascade = false, $Ruta = '')
	{
		$this->nodos_cant = $this->nodos->count();
		$this->open = true;

		if($Cascade){
			if($Ruta !== '') $Ruta .= '\\';
			$Ruta .= $this->Nodo;
			$this->ruta = $Ruta;
			foreach ($this->nodos as $nodo) {
				$nodo->getChildren(true, $Ruta);
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
		if($this->tipo == 'Variable'  AND $this->elemento)  $this->valores = $this->elemento->getVals( round($Periodos[0]/100));
		if($this->tipo == 'Nodo'){
			$calc = array_fill_keys($Periodos, [ 'puntos' => 0, 'incalculables' => 0 ]);
			foreach ($this->nodos as $subnodo) {
				
				if($subnodo->tipo == 'Indicador'){
					foreach ($subnodo->valores as $per => $val) {
						if($val['calculable']){
							$calc[$per]['puntos'] += $subnodo->peso * $val['cump_porc'];
						}else{
							$calc[$per]['incalculables']++;
						}
					}
				}

				if($subnodo->tipo == 'Variable' AND $subnodo->valores){
					foreach ($subnodo->valores as $per => $val) {
						if(!is_null($val['Valor'])){
							$calc[$per]['puntos'] += $subnodo->peso;
						}else{
							$calc[$per]['incalculables']++;
						}
						
					}
				}

				if($subnodo->tipo == 'Nodo'){
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
			'calc' => $this->calc, 'valores' => $this->valores, 'elemento' => $this->elemento, 'open' => $open, 'show' => $show,
			'ruta' => $this->ruta
		];
		$depth++;
		foreach ($this->nodos as $nodo) {
			$nodo->flatten($NodosFlat, $depth, $open_to_level);
		}
	}

}
