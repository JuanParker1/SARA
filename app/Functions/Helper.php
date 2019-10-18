<?php 

namespace App\Functions;
use Carbon\Carbon;

class Helper
{
	public static function getElm($Collection, $Value, $Key = 'id')
    {
        return collect($Collection)->filter(function ($elm) use ($Key, $Value){
            return $elm[$Key] == $Value;
        })->first();
    }

    public static function formatVal($val, $formato = 'Numero', $decimales = 0)
    {
    	if(!is_numeric($val)) return null;
		switch ($formato) {
            case 'Numero':                  $val = number_format($val,$decimales,',','.'); break;
            case 'Porcentaje':              $val = number_format(($val*100),$decimales,',','.')."%"; break;
            case 'Moneda':                  $val = "$ ".number_format($val,$decimales,',','.'); break;
        }
        return $val;
    }

    public static function getIndicatorColor($porc_cump = null)
    {
        if(is_null($porc_cump)) return '#c1c1c1'; //Gris
        if($porc_cump < 0.85)   return '#ff2626'; //Rojo
        if($porc_cump < 1)      return '#ffac00';
                                return '#00b306';
    }

    public static function getPeriodos($periodoIni,$periodoFin)
    {
        $Periodos = [];
        if($periodoFin < $periodoIni) return $Periodos;

        $Anio = intval(substr($periodoIni, 0, 4));
        $Mes  = intval(substr($periodoIni, 4, 2));

        while($periodoIni <= $periodoFin){
            $Periodos[] = $periodoIni;

            $Mes++;
            if($Mes == 13){ $Anio++; $Mes = 1; }

            $periodoIni = ($Anio*100)+$Mes;
        }

        return $Periodos;


    }

}