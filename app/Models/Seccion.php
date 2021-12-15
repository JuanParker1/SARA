<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Seccion extends MyModel
{
    protected $table = 'sara_secciones';
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $casts = [];

    public function columns()
    {
        //Name, Desc, Type, Required, Unique, Default, Width, Options
        return [
            [ 'Seccion',           null,               null, true, false, null, 100 ],
            [ 'Orden',             null,               null, true, false, null, 100 ],
            [ 'Icono',             null,               null, true, false, null, 100 ],
            [ 'Estado',            null,               null, true, false, null, 100 ],
        ];
    }

}
