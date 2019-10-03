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
	];
    protected $appends = [];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',				null, true, false, null, 100 ],
			[ 'Ruta',					'Ruta',				null, true, false, null, 100 ],
			[ 'Titulo',					'Titulo',		null, true, false, null, 100 ],
			[ 'Secciones',				'Secciones',		null, true, false, null, 100 ],
		];
	}

	public function cards()
	{
		return $this->hasMany('\App\Models\ScorecardCard', 'scorecard_id')->orderBy('Indice');
	}
}
