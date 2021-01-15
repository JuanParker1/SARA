<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotLog extends Model
{
    protected $table = 'sara_bots_logs';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [];
    protected $appends = [];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',			null, true, false, null, 100 ],
			[ 'bot_id',				 	'bot_id',		null, true, false, null, 100 ],
			[ 'bot_paso_id',			'bot_paso_id',	null, true, false, null, 100 ],
			[ 'Estado',				 	'Estado',		null, true, false, null, 100 ],
			[ 'Mensaje',				 'Mensaje',		null, true, false, null, 100 ]
		];
	}

	public function paso()
	{
		return $this->belongsTo('\App\Models\BotPaso', 'bot_paso_id');
	}
}
