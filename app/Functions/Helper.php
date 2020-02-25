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
            case 'Porcentaje':              $val = ($val == 0) ? "0%" : number_format(($val*100),$decimales,',','.')."%"; break;
            case 'Moneda':                  $val = "$ ".number_format($val,$decimales,',','.'); break;
        }
        return $val;
    }

    public static function getIndicatorColor($porc_cump = null, $modo = 'A')
    {
        if($modo == 'A'){
            if(is_null($porc_cump)) return '#c1c1c1'; //Gris
            if($porc_cump < 0.85)   return '#ff2626'; //Rojo
            if($porc_cump < 1)      return '#ffac00'; //Amarillo
                                    return '#40d802'; //Verde
        };

        if($modo == 'B'){
            if(is_null($porc_cump)) return '#c1c1c1'; //Gris
            if($porc_cump < 0.80)   return '#ff2626'; //Rojo
            if($porc_cump < 0.90)   return '#ffac00'; //Amarillo
                                    return '#40d802'; //Verde
        };

        
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

    public static function randomString($len = 5, $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $charsLen = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $len; $i++) {
            $randomString .= $chars[rand(0, $charsLen - 1)];
        }
        return $randomString;
    }

}