<?php

namespace App\Models;

use App\Models\Core\MyModel;

class IndicadorValor extends MyModel
{
    protected $table = 'sara_indicadores_valores';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
        'valores' => 'array'
    ];
    protected $appends = [];


    public function scopeIndicador($q, $indicador_id)
    {
        return $q->where('indicador_id', $indicador_id);
    }

    public function scopeAnio($q, $anio)
    {
        return $q->where('Anio', $anio);
    }
}
