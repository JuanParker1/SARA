<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotVariable extends Model
{
    protected $table = 'sara_bots_variables';
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
			[ 'Nombre',				 	'Nombre',		null, true, false, null, 100 ],
			[ 'Valor',				 	'Valor',		null, true, false, null, 100 ]	
		];
	}

	public function scopeBot($q, $id)
	{
		return $q->where('bot_id', $id);
	}
}
