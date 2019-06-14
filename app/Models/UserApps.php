<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApps extends Model
{
    protected $table = 'sara_usuario_apps';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'favorito' => 'boolean',
    ];
}
