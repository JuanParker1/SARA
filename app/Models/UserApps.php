<?php

namespace App\Models;

use App\Models\Core\MyModel;

class UserApps extends MyModel
{
    protected $table = 'sara_usuario_apps';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'favorito' => 'boolean',
    ];
}
