<?php

namespace App\Models;

use App\Models\Core\MyModel;

class IndicadorVariable extends MyModel
{
    protected $table = 'sara_indicadores_variables';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
	];
    protected $appends = [];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',					'id',				null, true, false, null, 100 ],
			[ 'indicador_id',		'indicador_id',		null, true, false, null, 100 ],
			[ 'Letra',				'Letra',			null, true, false, null, 100 ],
			[ 'Tipo',				'Tipo',				null, true, false, null, 100 ],
			[ 'variable_id',		'variable_id',		null, true, false, null, 100 ],
			[ 'Op1',		'Op1',		null, true, false, null, 100 ],
			[ 'Op2',		'Op2',		null, true, false, null, 100 ],
			[ 'Op3',		'Op3',		null, true, false, null, 100 ],
		];
	}

	public function variable()
	{
		return $this->belongsTo('\App\Models\Variable', 'variable_id');
	}

	public function scopeIndicador($query,$id)
	{
		return $query->where('indicador_id', $id);
	}
}
