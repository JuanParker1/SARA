<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Configuracion extends MyModel
{
    protected $table = 'sara_configuracion';
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $primaryKey = 'id';
    protected $casts = [];
    protected $appends = [];
}
