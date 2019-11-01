<?php

namespace App\Models;

use App\Models\Core\MyModel;

class EntidadEditor extends MyModel
{
    protected $table = 'sara_entidades_editores';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Secciones' => 'array'
    ];
    protected $appends = [];


    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',			'id',			null, true,  false, null, 100 ],
			[ 'entidad_id',	'entidad_id',	null, true,  false, null, 100 ],
			[ 'Titulo',		'Titulo',		null, true,  false, null, 100 ],
			[ 'Ancho',		'Ancho',		null, true,  false, null, 100 ],
			[ 'Secciones',	'Secciones',	null, true,  false, null, 100 ],
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
	
	public function campos()
	{
		return $this->hasMany('\App\Models\EntidadEditorCampo', 'editor_id')->orderBy('Indice', 'ASC');
	}
}
