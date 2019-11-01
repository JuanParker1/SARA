<?php

namespace App\Models;

use App\Models\Core\MyModel;

class EntidadGrid extends MyModel
{
    protected $table = 'sara_entidades_grids';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Config' => 'array'
    ];
    protected $appends = [];


    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',			'id',			null, true,  false, null, 100 ],
			[ 'entidad_id',	'entidad_id',	null, true,  false, null, 100 ],
			[ 'Titulo',		'Titulo',		null, true,  false, null, 100 ],
			[ 'Config',		'Config',		null, true,  false, null, 100 ],
		];
	}


	public function scopeEntidad($query,$id)
	{
		return $query->where('entidad_id', $id);
	}


	//Relaciones
	public function entidad()
	{
		return $this->belongsTo('\App\Models\Entidad', 'entidad_id');
	}
	
	public function columnas()
	{
		return $this->hasMany('\App\Models\EntidadGridColumna', 'grid_id')->orderBy('Indice', 'ASC');
	}

	public function filtros()
	{
		return $this->hasMany('\App\Models\EntidadGridFiltro', 'grid_id')->orderBy('Indice', 'ASC');
	}

}