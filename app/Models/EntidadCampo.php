<?php

namespace App\Models;

use App\Models\Core\MyModel;

class EntidadCampo extends MyModel
{
    protected $table = 'sara_entidades_campos';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Requerido'  => 'boolean',
    	'Visible'    => 'boolean',
    	'Unico'      => 'boolean',
    	'Editable'   => 'boolean',
    	'Buscable'   => 'boolean',
    	'Config'     => 'array',
    ];
    protected $appends = ['campo_title'];


    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',			'id',			null, true,  false, null, 100 ],
			[ 'entidad_id',	'entidad_id',	null, true,  false, null, 100 ],
			[ 'Indice',		'Indice',		null, true,  false, null, 100 ],
			[ 'Columna',	'Columna',		null, true,  false, null, 100 ],
			[ 'Alias',		'Alias',		null, true,  false, null, 100 ],
			[ 'Tipo',		'Tipo',			null, true,  false, null, 100 ],
			[ 'Defecto',	'Defecto',		null, true,  false, null, 100 ],
			[ 'Requerido',	'Requerido',	null, true,  false, null, 100 ],
			[ 'Visible',	'Visible',		null, true,  false, null, 100 ],
			[ 'Unico',		'Unico',		null, true,  false, null, 100 ],
			[ 'Editable',	'Editable',		null, true,  false, null, 100 ],
			[ 'Buscable',	'Buscable',		null, true,  false, null, 100 ],
			[ 'Op1',		'Op1',			null, false, false, null, 100 ],
			[ 'Op2',		'Op2',			null, false, false, null, 100 ],
			[ 'Op3',		'Op3',			null, false, false, null, 100 ],
			[ 'Op4',		'Op4',			null, false, false, null, 100 ],
			[ 'Op5',		'Op5',			null, false, false, null, 100 ],
			[ 'Config',		'Config',		null, false, false, null, 100 ],
		];
	}


	//relations
	public function entidad()
	{
		return $this->belongsTo('App\Models\Entidad', 'entidad_id');
	}

	public function entidadext()
	{
		return $this->belongsTo('App\Models\Entidad', 'Op1');
	}

	//Scopes
	public function scopeEntidad($query,$id)
	{
		return $query->where('entidad_id', $id);
	}

	//Funciones
	public function getColName($base)
	{
		return \App\Functions\CamposHelper::getColName($base, $this->Columna);
	}

	public function getCampoTitleAttribute()
	{
		return $this->Alias ?: $this->Columna;
	}


}
