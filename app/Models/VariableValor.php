<?php

namespace App\Models;

use App\Models\Core\MyModel;

class VariableValor extends MyModel
{
    protected $table = 'sara_variables_valores';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Valor' => 'float'
    ];
    protected $appends = [];

    public function formatVal($formato = 'Numero', $decimales = 0)
    {
    	$val = $this->Valor;
        $this->val = \App\Functions\Helper::formatVal($this->Valor,$formato,$decimales);
    }

    public function scopeYear($query,$year,$mesIni = 1, $mesFin = 12)
    {
        return $query->where('Periodo', '>=', ($year*100)+$mesIni)->where('Periodo', '<=', ($year*100)+$mesFin);
    }
}
