<?php

namespace App\Models;

use App\Models\Core\MyModel;

class EntidadRestriccion extends MyModel
{
    protected $table = 'sara_entidades_restricciones';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [];
    protected $with = ['campo'];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',			'id',			null, true,  	false, null, 100 ],
			[ 'entidad_id',	'entidad_id',	null, true,  	false, null, 100 ],
			[ 'campo_id',	'campo_id',		null, true,  	false, null, 100 ],
			[ 'Comparador',	'Comparador',	null, true,  	false, null, 100 ],
			[ 'Valor',		'Valor',		null, false,  	false, null, 100 ],
			[ 'Op1',		'Op1',		null, false,  	false, null, 100 ],
			[ 'Op2',		'Op2',		null, false,  	false, null, 100 ],
			[ 'Op3',		'Op3',		null, false,  	false, null, 100 ],
		];
	}

	//relations
	public function entidad()
	{
		return $this->belongsTo('App\Models\Entidad', 'entidad_id');
	}

	public function campo()
	{
		return $this->belongsTo('App\Models\EntidadCampo', 'campo_id');
	}

	//Scopes
	public function scopeEntidad($query,$id)
	{
		return $query->where('entidad_id', $id);
	}
}
