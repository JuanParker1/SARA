<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'sara_configuracion';
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $primaryKey = 'id';
    protected $casts = [];
    protected $appends = [];
}
