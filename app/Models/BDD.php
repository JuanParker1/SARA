<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BDD extends Model
{
    protected $table = 'sara_bdds';
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
			[ 'id',			'id',			null, true, true, null, 100 ],
			[ 'Tipo',		'Tipo',			null, true, false, null, 100 ],
			[ 'Nombre',		'Nombre',		null, true, true, null, 100 ],
			[ 'Usuario',	'Usuario',		null, true, true, null, 100 ],
			[ 'Contraseña',	'Contraseña',	null, true, true, null, 100 ],
			[ 'Op1',		'Op1',			null, true, true, null, 100 ],
			[ 'Op2',		'Op2',			null, true, true, null, 100 ],
			[ 'Op3',		'Op3',			null, true, true, null, 100 ],
			[ 'Op4',		'Op4',			null, true, true, null, 100 ],
			[ 'Op5',		'Op5',			null, true, true, null, 100 ],
		];
	}
    
}
