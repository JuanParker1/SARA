<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variable extends Model
{
    protected $table = 'sara_variables';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Filtros' => 'array'
    ];
    protected $appends = [];

    use SoftDeletes;

    public function columns()
	{

		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',					null, true, false, null, 100 ],
			[ 'Ruta',					'Ruta',				null, true, false, null, 100 ],
			[ 'Variable',				'Variable',				null, true, false, null, 100 ],
			[ 'Descripcion',			'Descripcion',				null, true, false, null, 100 ],
			[ 'TipoDato',				'TipoDato',				null, true, false, null, 100 ],
			[ 'Decimales',				'Decimales',				null, true, false, null, 100 ],
			[ 'Tipo',					'Tipo',				null, true, false, null, 100 ],
			[ 'grid_id',				'grid_id',				null, true, false, null, 100 ],
			[ 'ColPeriodo',				'ColPeriodo',				null, true, false, null, 100 ],
			[ 'Agrupador',				'Agrupador',				null, true, false, null, 100 ],
			[ 'Col',					'Col',				null, true, false, null, 100 ],
			[ 'Filtros',				'Filtros',				null, true, false, null, 100 ],
		];
	}

	public function grid()
	{
		return $this->belongsTo('\App\Models\EntidadGrid', 'grid_id');
	}

	public function valores()
	{
		return $this->hasMany('\App\Models\VariableValor', 'variable_id')->orderBy('Periodo');
	}
}
