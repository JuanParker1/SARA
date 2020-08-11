<?php 

namespace App\Functions;
use Carbon\Carbon;
use App\Functions\FormulaParser;

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

    public static function periodoAdd($Periodo, $Add = 1)
    {
        for ($i=1; $i <= $Add; $i++) { 
            $Anio = intval($Periodo/100);
            $Mes  = $Periodo - ($Anio*100);

            $Periodo = ( $Mes < 12 ) ? ( ($Anio*100) + ($Mes+1) ) : ( ( ($Anio + 1)*100) + 1 );
        }

        return $Periodo;
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


    //Formulas
    public static function calcFormula($formula, $comps, $decimales = 0)
    {
        if(str_replace(" ", "", $formula) == 'a/b'){
            if(!array_key_exists('a', $comps) OR !array_key_exists('b', $comps)) return -9999;
            if($comps['a'] == 0 OR $comps['b'] == 0) return 0;
            $res = $comps['a'] / $comps['b'];
            return round($res, $decimales);
        };

        if(str_replace(" ", "", $formula) == 'a'){
            if(!array_key_exists('a', $comps)) return -8888;
            return round($comps['a'], $decimales);
        }

        //if(in_array($formula, ['(a + b + c) / d'])) return null;

        //echo $formula."<br>";

        try {
            $parser = new FormulaParser($formula, $decimales);
            $parser->setVariables($comps);
            $res = $parser->getResult();

            
            if($res && $res[0] == 'done' && !is_nan($res[1]) ){
                //print_r(is_nan($res[1]));
                return $res[1];
            }else{
                return null;
            }

        } catch (\Exception $e) {
            return null;
            //echo $e->getMessage(), "\n";
        }
    }

    public static function calcCump($Valor, $Meta, $Sentido, $Modo = 'bool', $Meta2 = null)
    {
        if(is_null($Valor) OR is_null($Meta)) return null;

        if($Sentido == 'ASC'){
            
            $cump = ($Valor >= $Meta) ? 1 : 0;
            $porc = $Valor / $Meta;
        
        }else if($Sentido == 'DES'){
            
            $cump = ($Valor <= $Meta) ? 1 : 0;
            $porc = 1 - ( ( $Valor - $Meta ) / $Meta );
        
        }else if($Sentido == 'RAN' AND !is_null($Meta2)){

            $cump = ($Valor >= $Meta AND $Valor <= $Meta2) ? 1 : 0;
            if($Valor <= $Meta){ $porc = $Valor / $Meta; }
            else if( $Valor >= $Meta2 ){ $porc = 1 - ( ( $Valor - $Meta2 ) / $Meta2 ); }
            else{ $porc = 1; }

        }else{
            return null;
        }

        $porc = max(min($porc, 1), 0);
        $porc = round($porc, 3);

        if( $Modo == 'bool' ) return $cump;
        if( $Modo == 'porc' ) return $porc;

    }
    
    
    public static function getUsuario()
    {
        $token = request()->header('token');

        if(!$token) abort(400, 'Usuario no autorizado');

        $Email = \Crypt::decrypt($token);
        $Usuario = \App\Models\Usuario::where('Email', $Email)->first();

        if(!$Usuario) abort(400, 'Usuario no autorizado');

        return $Usuario;
    }

    public static function getDir($url)
    {
        return implode('\\', array_slice(explode('\\', $url), 0, -1));
    }

    public static function readTableFile($filename, $Ops)
    {
        $Ops = array_merge([
            'headers' => false,
            'ReadDataOnly' => true,
            'Sheet' => 0,
            'col_ini' => 1, 'col_fin' => null,
            'row_ini' => 1, 'row_fin' => null,
            'collect' => true,
        ], $Ops);

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filename);
        $reader->setReadDataOnly($Ops['ReadDataOnly']);
        $spreadsheet = $reader->load($filename);
        $worksheet   = $spreadsheet->getSheet($Ops['Sheet']);
        
        if(is_null($Ops['col_fin'])) $Ops['col_fin'] = \PHPExcel_Cell::columnIndexFromString($worksheet->getHighestColumn());
        if(is_null($Ops['row_fin'])) $Ops['row_fin'] = $worksheet->getHighestRow();

        if(!$Ops['headers']){
            $Ops['headers'] = [];
            for ($c=$Ops['col_ini']; $c <= $Ops['col_fin']; $c++) { 
                $Ops['headers'][$c] = $worksheet->getCellByColumnAndRow($c, $Ops['row_ini'])->getValue();
            }
            $Ops['row_ini']++;
        }else{
            $Headers = [];
            for ($c=$Ops['col_ini']; $c <= $Ops['col_fin']; $c++) {
                $Headers[$c] = $Ops['headers'][ ($c - $Ops['col_ini']) ];
            }
            $Ops['headers'] = $Headers;
        }

        $Bag = [];
        for ($r = $Ops['row_ini']; $r <= $Ops['row_fin']; $r++) {
            $row = []; 
            for ($c=$Ops['col_ini']; $c <= $Ops['col_fin']; $c++) {
                $row[ $Ops['headers'][$c] ] = $worksheet->getCellByColumnAndRow($c, $r)->getValue();
            }
            $Bag[] = $row;
        }
        
        if($Ops['collect']) $Bag = collect($Bag);

        return $Bag;
    }

    public static function tablearArray($Arr)
    {
        $Titulos = collect($Arr[0])->keys();

        $Tb = "<table><thead><tr>";
        foreach ($Titulos as $T) { $Tb .= "<td><pre><b>$T</b></pre></td>";}
        $Tb .= "</tr></thead><tbody>";

        foreach ($Arr as $R) {
            $Row = "<tr>"; foreach ($Titulos as $kT => $T) {
                $Val = array_key_exists($T, $R) ? $R[$T] : '';
                $Row .= "<td><pre>$Val</pre></td>";
            } $Row .= "</tr>";
            $Tb .= $Row;
        }

        $Tb .= "</tbody></table>

        <style>
            table {
              font-family: sans-serif;
              border-collapse: collapse;
              width: 100%;
            }

            table td{
                border: 1px solid #e0e0e0;
            }

        </style>
        ";
        echo $Tb;
    }

    public static function streamCSV($rows, $fileName = 'ExportedFile.csv', $columnNames = false, $separator = ';')
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $fileName,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        if(!$columnNames) $columnNames = collect($rows[0])->keys()->toArray();

        $callback = function() use ($columnNames, $rows, $separator) {
            $file = fopen('php://output', 'w');
            $bom = chr(0xEF) . chr(0xBB) . chr(0xBF);
            fputs($file, $bom);
            fputcsv($file, $columnNames, $separator);
            foreach ($rows as $row) {
                fputcsv($file, $row, $separator);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }


    public static function exportTableFile($Arr, $name = 'ExportedFile', $filetype = 'Xlsx')
    {
        $spread = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spread->getActiveSheet();

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spread, $filetype);
        $writer->save($filetype);
    }


}