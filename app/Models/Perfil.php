<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Perfil extends MyModel
{
    protected $table = 'sara_perfiles';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $with = [];
	protected $primaryKey = 'id';
    protected $casts = [
	];
    protected $appends = [];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',				null, true, false, null, 100 ],
			[ 'Perfil',					'Perfil',			null, true, false, null, 100 ],
			[ 'Perfil_Show',			'Perfil_Show',			null, true, false, null, 100 ],
			[ 'Orden',					'Orden',			null, true, false, null, 100 ],
		];
	}
}
