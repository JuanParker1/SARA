<?php

namespace App\Models;

use App\Models\Core\MyModel;

class ScorecardCard extends MyModel
{
    protected $table = 'sara_scorecards_card';
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
			[ 'id',				'id',				null, true, false, null, 100 ],
			[ 'scorecard_id',	'scorecard_id',		null, true, false, null, 100 ],
			[ 'Indice',			'Indice',			null, true, false, null, 100 ],
			[ 'seccion_id',		'seccion_id',		null, true, false, null, 100 ],
			[ 'tipo',			'tipo',				null, true, false, null, 100 ],
			[ 'elemento_id',	'elemento_id',		null, true, false, null, 100 ],
		];
	}

	public function scopeScorecard($query,$id)
	{
		return $query->where('scorecard_id', $id);
	}
}




