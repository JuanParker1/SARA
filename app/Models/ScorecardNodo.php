<?php

namespace App\Models;

use App\Models\Core\MyModel;

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
    protected $appends = ['elemento','Nodo','Ruta','children'];

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

	public function getElementoAttribute()
	{
		if($this->tipo == 'Indicador'){
			return \App\Models\Indicador::where('id', $this->elemento_id)->first();
		}
	}

	public function getNodoAttribute()
	{

		if($this->tipo == 'Indicador'){
			return $this->elemento->Indicador;
		}else{
			return $this->attributes['Nodo'];
		}
	}


	public function getRutaAttribute()
	{
		if(is_null($this->padre_id)){
			//return $this->Nodo;
			return '';
		}else if($this->tipo == 'Nodo'){
			$RutaPadre = $this->nodo_padre->Ruta;
			if($RutaPadre !== '') $RutaPadre .= '\\';
			return $RutaPadre . $this->Nodo;
		}else{
			return $this->nodo_padre->Ruta;
		}
	}

	public function getChildrenAttribute()
	{
		return $this->tipo == 'Nodo' ? 1 : 0;
	}
}
