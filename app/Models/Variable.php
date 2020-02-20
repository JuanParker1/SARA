<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\EntidadCampo;

class Variable extends Model
{
    protected $table = 'sara_variables';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Filtros' => 'array'
    ];
    protected $appends = ['Filtros', 'Ruta'];

    use SoftDeletes;

    public function columns()
	{

		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',				null, true, false, null, 100 ],
			[ 'Ruta',					'Ruta',				null, true, false, null, 100 ],
			[ 'proceso_id',				'proceso_id',		null, true, false, null, 100 ],
			[ 'Variable',				'Variable',			null, true, false, null, 100 ],
			[ 'Descripcion',			'Descripcion',		null, true, false, null, 100 ],
			[ 'TipoDato',				'TipoDato',			null, true, false, null, 100 ],
			[ 'Decimales',				'Decimales',		null, true, false, null, 100 ],
			[ 'Tipo',					'Tipo',				null, true, false, null, 100 ],
			[ 'grid_id',				'grid_id',			null, true, false, null, 100 ],
			[ 'ColPeriodo',				'ColPeriodo',		null, true, false, null, 100 ],
			[ 'Agrupador',				'Agrupador',		null, true, false, null, 100 ],
			[ 'Col',					'Col',				null, true, false, null, 100 ],
			[ 'Filtros',				'Filtros',			null, true, false, null, 100 ],
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

	public function proceso()
	{
		return $this->belongsTo('\App\Models\Proceso', 'proceso_id');
	}

	public function getFiltrosAttribute($a)
	{
		$Filtros = json_decode($this->attributes['Filtros'], true);
		foreach ($Filtros as &$F) { 
			$F['campo'] = EntidadCampo::find($F['campo_id']);
			$F['val']   = $F['Valor'];
		};
		return $Filtros;
	}
	
	public function setFiltrosAttribute($Filtros)
	{

		foreach ($Filtros as &$F) { unset($F['campo']); };
		$this->attributes['Filtros'] = json_encode($Filtros);
	}

	public function getRutaAttribute()
	{
		return $this->proceso->Ruta;
	}

}
