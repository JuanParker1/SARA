<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariableValor extends Model
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
        if(!is_null($val)){
            switch ($formato) {
                case 'Numero':                  $val = number_format($val,$decimales,',','.'); break;
                case 'Porcentaje':              $val = number_format(($val*100),$decimales,',','.')."%"; break;
                case 'Moneda':                  $val = "$ ".number_format($val,$decimales,',','.'); break;
            }
        }
    	
    	$this->val = $val;
    }
}
