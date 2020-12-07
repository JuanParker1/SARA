<?php

namespace App\Models;

use App\Models\Core\MyModel;

class IndicadorMeta extends MyModel
{
    protected $table = 'sara_indicadores_metas';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Meta' => 'float',
    	'Meta2' => 'float'
    ];
    protected $appends = [];
    protected $touches = ['daindicador'];


    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',					'id',				null, true, false, null, 100 ],
			[ 'indicador_id',		'indicador_id',		null, true, false, null, 100 ],
			[ 'PeriodoDesde',		'Periodo Desde',	null, true, false, null, 35 ],
			[ 'Meta',				'Meta',				null, true, false, null, 35 ],
			[ 'Meta2',				'Meta Max.',		null, true, false, null, 30 ],
		];
	}

	public function daindicador()
	{
		return $this->belongsTo('App\Models\Indicador');
	}

	public function scopeIndicador($query,$id)
	{
		return $query->where('indicador_id', $id);
	}

	public function scopeYear($query,$Year,$mesFin = 12)
	{
		return $query->where('PeriodoDesde', '<=', ($Year*100)+$mesFin)->orderBy('PeriodoDesde', 'DESC');
	}

	
}
