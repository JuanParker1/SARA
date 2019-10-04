<?php

namespace App\Models;

use App\Models\Core\MyModel;

class EntidadGridColumna extends MyModel
{
    protected $table = 'sara_entidades_grids_columnas';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Ruta' => 'array',
    	'Llaves' => 'array',
    	'Visible' => 'boolean',
    ];
    protected $with = ['campo'];
    protected $appends = ['column_title', 'tipo_campo'];


    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',					'id',					null, true,  false, null, 100 ],
			[ 'grid_id',			'grid_id',				null, true,  false, null, 100 ],
			[ 'Indice',				'Indice',				null, true,  false, null, 100 ],
			[ 'Cabecera',			'Cabecera',				null, false,  false, null, 100 ],
			[ 'Tipo',				'Tipo',					null, false,  false, null, 100 ],
			[ 'Ruta',				'Ruta',					null, false,  false, null, 100 ],
			[ 'Llaves',				'Llaves',				null, false,  false, null, 100 ],
			[ 'Visible',			'Visible',				null, false,  false, null, 100 ],
			[ 'campo_id',			'campo_id',				null, true,  false, null, 100 ],
			[ 'externalgrid_id',	'externalgrid_id',		null, false,  false, null, 100 ],
		];
	}

	//relations
	public function campo()
	{
		return $this->belongsTo('App\Models\EntidadCampo', 'campo_id');
	}


	public function scopeGrid($query,$id)
	{
		return $query->where('grid_id', $id);
	}


	public function getRuta()
	{
		$this->ruta_entidades = \App\Models\Entidad::whereIn('id', $this->Ruta)->get();
	}


	public function getColumnTitleAttribute()
	{
		if(is_null($this->campo)) return $this->Cabecera;
		return $this->Cabecera ?: $this->campo->campo_title;
	}

	public function getTipoCampoAttribute()
	{
		if(is_null($this->campo)) return null;
		return $this->campo->Tipo;
	}

}
