<?php

namespace App\Models;

use App\Models\Core\MyModel;

class ScorecardNodoValores extends MyModel
{
    protected $table = 'sara_scorecards_nodos_valores';
	protected $guarded = ['id'];
	protected $primaryKey = 'id';
    protected $casts = [
    	'valores' => 'array'
	];
}
