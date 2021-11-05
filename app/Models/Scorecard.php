<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Scorecard extends MyModel
{
    protected $table = 'sara_scorecards';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Secciones' => 'array',
    	'config' => 'array',
	];
    protected $appends = [];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',			'id',			null, true, false, null, 100 ],
			[ 'Ruta',		'Ruta',			null, true, false, null, 100 ],
			[ 'Titulo',		'Titulo',		null, true, false, null, 100 ],
			[ 'Secciones',	'Secciones',	null, true, false, null, 100 ],
			[ 'config',		'config',		null, true, false, null, 100 ],
		];
	}

	public function cards()
	{
		return $this->hasMany('\App\Models\ScorecardCard', 'scorecard_id')->orderBy('Indice');
	}

	public function nodos()
	{
		return $this->hasMany('\App\Models\ScorecardNodo', 'scorecard_id');
	}

	public function getConfigAttribute($Config)
	{
		$Default = [
			'open_to_level' => 1,
			'show_proceso' => false,
			'data_code' => '',
			'calc_method' => 'peso',
			'default_frecuencia_analisis' => ['-1'],
			'default_see' => 'Res'
		];
		
		if(gettype($Config) == 'string') $Config = json_decode($Config);
		if(gettype($Config) == 'object') $Config = (array) $Config;
		$Config = is_null($Config) ? $Default : array_merge($Default, $Config);

		return $Config;
	}


	public function scopeBuscar($q, $searchText)
	{
		return $q->where('Titulo', 'LIKE', "%$searchText%")->select([
			'id', 'Titulo'
		]);
	}


}
