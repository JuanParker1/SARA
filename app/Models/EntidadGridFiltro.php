<?php

namespace App\Models;

use App\Models\Core\MyModel;

class EntidadGridFiltro extends MyModel
{
    protected $table = 'sara_entidades_grids_filtros';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [];
    protected $with = [];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',			'id',			null, true,  	false, null, 100 ],
			[ 'grid_id',	'grid_id',		null, true,  	false, null, 100 ],
			[ 'columna_id',	'columna_id',	null, true,  	false, null, 100 ],
			[ 'Indice',		'Indice',		null, true,  	false, null, 100 ],
			[ 'Comparador',	'Comparador',	null, true,  	false, null, 100 ],
			[ 'Valor',		'Valor',		null, false,  	false, null, 100 ],
			[ 'Op1',		'Op1',			null, false,  	false, null, 100 ],
			[ 'Op2',		'Op2',			null, false,  	false, null, 100 ],
			[ 'Op3',		'Op3',			null, false,  	false, null, 100 ],
		];
	}


	//Scopes
	public function scopeGrid($query,$id)
	{
		return $query->where('grid_id', $id);
	}


}
