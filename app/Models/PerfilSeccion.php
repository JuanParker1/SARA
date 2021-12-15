<?php

namespace App\Models;

use App\Models\Core\MyModel;

class PerfilSeccion extends MyModel
{
    protected $table = 'sara_perfiles_secciones';
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $with = [];
    protected $primaryKey = 'id';
    protected $casts = [];
    protected $appends = [];
}
