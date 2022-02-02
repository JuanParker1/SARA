<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        
        $config_name = \App\Functions\Helper::getInstanceConn(false);
        $this->setConnection($config_name);
    }


    /**
     * Ajusta un array y ejecuta fill
     */
    public function fillit(array $attributes)
    {
        $Attrs = array_diff_key($this->attributes, array_flip($this->getGuarded()));
        $Filler = array_intersect_key($attributes, $Attrs);
        return $this->fill($Filler);
    }


    public function fillit_columns(array $attributes)
    {
        $Cols = array_column($this->columns(), 0);
        $Filler = array_intersect_key($attributes, array_fill_keys($Cols, null));
        return $this->fill($Filler);
    }


    /**
     * Devuelve una propiedad periodo basado en 2 fechas
     */
    public function period($Date1, $Date2, $Sep = '-', $Comp = '')
    {
    	if(!$Date1 OR !$Date2) return '';
        $Months = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $d1 = "{$Date1->format('d')} {$Months[$Date1->month]}";
        $d2 = "{$Date2->format('d')} {$Months[$Date2->month]}";
        return trim("$d1 $Sep $d2 $Comp");
    }

}
