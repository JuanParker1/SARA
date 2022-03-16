<?php

namespace App\Models;

use App\Models\Core\MyModel;

class AppPages extends MyModel
{
    protected $table = 'sara_apps_pages';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
        'Config' => 'array'
    ];
    protected $appends = [];

    public function columns()
    {
        //Name, Desc, Type, Required, Unique, Default, Width, Options
        return [
            [ 'id',     	'id',          null, true, false, null, 100 ],
            [ 'app_id',     'app_id',      null, true, false, null, 100 ],
            [ 'Indice',     'Indice',      null, true, false, null, 100 ],
            [ 'Titulo', 	'Titulo',      null, true, false, null, 100 ],
            [ 'Tipo', 		'Tipo',        null, true, false, null, 100 ],
            [ 'Config', 	'Config',      null, true, false, null, 100 ],
        ];
    }

    public function scopeApp($query,$id)
    {
        return $query->where('app_id', $id);
    }

    public function daapp()
    {
        return $this->belongsTo('\App\Models\Apps', 'app_id');
    }
}
