<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Perfil extends MyModel
{
    protected $table = 'sara_perfiles';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $with = ['perfil_secciones'];
	protected $primaryKey = 'id';
    protected $casts = [
    	'config' => 'array'
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

	public function perfil_secciones()
	{
		return $this->hasMany('App\Models\PerfilSeccion', 'perfil_id');
	}

	public function getConfigAttribute($Config)
	{
		$Default = [
		];
		
		if(gettype($Config) == 'string') $Config = json_decode($Config);
		if(gettype($Config) == 'object') $Config = (array) $Config;
		$Config = is_null($Config) ? $Default : array_merge($Default, $Config);

		return $Config;
	}

}
