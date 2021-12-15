<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Feedback extends MyModel
{
    protected $table = 'sara_feedback';
    protected $guarded = ['id'];

    public function columns()
    {
        //Name, Desc, Type, Required, Unique, Default, Width, Options
        return [
            [ 'usuario_id',               null,                   null, true,  false, null, 100 ],
            [ 'Tema',                     null,                   null, false, false, null, 100 ],
            [ 'Comentario',               null,                   null, true,  false, null, 100 ],
            [ 'Estado',                   null,                   null, true,  false, null, 100 ],
        ];
    }

    //scopes
    public function scopeEstado($q, $estado)
    {
        if(!$estado) return $q;
        return $q->where('Estado', $estado);
    }

    public function usuario()
    {
        return $this->belongsTo('\App\Models\Usuario', 'usuario_id');
    }

}
