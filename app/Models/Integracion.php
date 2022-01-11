<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Integracion extends MyModel
{
    protected $table = 'sara_integraciones';
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $casts = [];
    protected $appends = [];

    public function columns()
    {
        //Name, Desc, Type, Required, Unique, Default, Width, Options
        return [
            [ 'id',                     null,               null, true, false, null, 100 ],
            [ 'Integracion',            null,               null, true, false, null, 100 ],
        ];
    }
}
