<?php 

namespace App\Functions;
use Carbon\Carbon;
use DB;
use App\Functions\ConnHelper;
use App\Functions\DB2Helper;
use App\Functions\MySQLHelper;

use App\Functions\GridHelper;

class CamposHelper
{
	public static function getTipos()
	{
		$TC = [
			'Entidad'     => [ 'Icon' => 'md-pawn', 			'Divide' => true, 	'Defaults' => [  null, null, null,             null, null] ],
			'Texto'       => [ 'Icon' => 'md-format-quote', 	'Divide' => false, 	'Defaults' => [  null, null, null,             null, null] ],
            'TextoLargo'  => [ 'Icon' => 'md-insert-comment',   'Divide' => true,   'Defaults' => [  1000, null, null,             null, null] ],
			'Lista'       => [ 'Icon' => 'md-list-view', 	    'Divide' => true, 	'Defaults' => [  null, null, null,             '[]', null] ],
			'Entero'      => [ 'Icon' => 'my-entero', 			'Divide' => false, 	'Defaults' => [  null, null, null,             null, null] ],
			'Decimal'     => [ 'Icon' => 'my-decimal', 			'Divide' => false, 	'Defaults' => [  null, null,    1,             null, null] ],
			'Dinero'      => [ 'Icon' => 'md-money', 			'Divide' => true, 	'Defaults' => [  null, null,    1,             null, null] ],
			'Booleano'    => [ 'Icon' => 'md-toggle-on', 		'Divide' => true, 	'Defaults' => [  null, null, null,             'Si', 'No'] ],
            'Periodo'     => [ 'Icon' => 'md-calendar',         'Divide' => false,  'Defaults' => [  null, null, null,             null, null] ],
			'Fecha'       => [ 'Icon' => 'md-calendar-event', 	'Divide' => false, 	'Defaults' => [  null, null, null,          'Y-m-d', null] ],
			'Hora'        => [ 'Icon' => 'md-time', 			'Divide' => false, 	'Defaults' => [  null, null, null,          'H:i:s', null] ],
			'FechaHora'   => [ 'Icon' => 'md-timer', 			'Divide' => true, 	'Defaults' => [  null, null, null,    'Y-m-d H:i:s', null] ],
			'Color'   	  => [ 'Icon' => 'md-color', 			'Divide' => false, 	'Defaults' => [  null, null, null,             null, null] ],
		];

		$TC['Fecha']['Formatos']      = [ ['Y-m-d','2019-12-31'], ['Ymd', '20191231'] ];
		$TC['Hora']['Formatos']  	  = [ ['H:i:s',  '23:59:59'], ['His',   '235959'], ['H:i',  '23:59'], ['Hi',   '2359'] ];
		$TC['FechaHora']['Formatos']  = [ ['Y-m-d H:i:s', '2019-12-31 23:59:59'], ['YmdHis', '20191231235959'], ['Y-m-d H:i', '2019-12-31 23:59'], ['YmdHi', '201912312359'] ];

        $TC['Fecha']['Comparators'] = ['=' => 'Es','>=' => 'Desde','<=' => 'Hasta','nulo' => 'Es nulo','no_nulo' => 'No es nulo'];
        $TC['Fecha']['Relatives']     = [
            ['first day of january last year','Primer dia del año pasado'],     
            ['first day of january this year','Primer dia de este año'],        
            ['-3 month','Hace 3 meses'],                
            ['first day of this month - 2 month','Primer día del mes antepasado'], 
            ['-2 month','El mes antepasado'],           
            ['first day of last month','Primer día del mes pasado'],    
            ['-1 month','El mes pasado'],               
            ['first day of this month','Primer día de este mes'],       
            ['this week','Primer día de esta semana'],  
            ['-2 days','Antier'],                       
            ['yesterday','Ayer'],                       
            ['today','Hoy'],                        
            ['tomorrow','Mañana'],                      
            ['+2 days','Pasadomañana'],                 
            ['this week +6 days','Último día de esta semana'],  
            ['last day of this month','Último día de este mes'],        
            ['+1 month','El próximo mes'],              
            ['last day of next month','Último día del proximo mes'],    
            ['+2 months','En 2 meses'],                     
            ['+3 months','En 3 meses'],                     
            ['last day of december this year','Último día de este año'],
        ];


 
		$TC = collect($TC)->transform(function($T){
			$Def = [];
			foreach ($T['Defaults'] as $k => $d) { $Def['Op'.($k+1)] = $d; };
			$T['Defaults'] = $Def;
			return $T;
		});

		

		return $TC;
	}

	public static function autoget($Bdd, $Entidad, $Campos)
	{

        $Conn = ConnHelper::getConn($Bdd);
        $SchemaTabla = GridHelper::getTableName($Entidad['Tabla'], $Bdd->Op3);

        try {
            if(in_array($Bdd->Tipo, ['ODBC_DB2'])){
            	$newCampos = DB2Helper::getColumns($Conn, $SchemaTabla[0], $SchemaTabla[1]);
                return DB2Helper::standarizeColumns($newCampos, $Bdd, $Entidad, $Campos);
            };

            if(in_array($Bdd->Tipo, ['ODBC_MySQL', 'MySQL'])){
                $newCampos = DB2Helper::getColumns($Conn, $SchemaTabla[0], $SchemaTabla[1]);
                return DB2Helper::standarizeColumns($newCampos, $Bdd, $Entidad, $Campos);
            };

        } catch(\Illuminate\Database\QueryException $ex){
            return response()->json([ 'Msg' => $ex->getMessage(), 'e' => $ex ], 512);
        };
	}

    public static function prepDato($Campo, $D)
    {
        if(is_null($D)) return $D;
        if($Campo['Tipo'] == 'Entero'){ return intval($D); }
        if($Campo['Tipo'] == 'Hora'){ 
            if(strlen($D) < 4) $D = str_pad($D, 4, "0", STR_PAD_LEFT);
            $Date = Carbon::createFromFormat($Campo['Op4'], $D);
            if(in_array($Campo['Op4'], ['Hi','H:i'])){ return $Date->format('H:i'); }
            return $Date->toTimeString();
        }
        if($Campo['Tipo'] == 'Fecha'){
            $Date = Carbon::createFromFormat($Campo['Op4'], $D);
            return $Date->toDateString();
        }
        if($Campo['Tipo'] == 'Texto'){ return utf8_encode(trim($D)); }
        if($Campo['Tipo'] == 'TextoLargo'){ return utf8_encode($D); }
        return $D;
    }

    public static function prepDatoIns($Campo, $D)
    {
        if($Campo['Tipo'] == 'Fecha'){
            return Carbon::createFromFormat($Campo['formato'], $D)->format($Campo['Op4']);
        };
        return $D;
    }

    public static function prepData($Columnas, $Datos)
    {
        $Datos = collect($Datos)->transform(function($row) use ($Columnas){
            return collect($row)->transform(function($c,$i) use ($Columnas){
                return self::prepDato($Columnas[$i]['campo'], $c);
            });
        });

        return $Datos;
    }

    public static function getFilterComp($R, $Campo)
    {
        $TC = self::getTipos();
        $Tipo = $TC[$Campo['Tipo']];
        if(array_key_exists('Comparators', $Tipo)) return $Tipo['Comparators'][$R['Comparador']];
    }

    public static function getFilterVal($R, $Campo)
    {
        $Valor = $R->Valor;
        if($Campo['Tipo'] == 'Fecha'){
            $Date  = Carbon::parse($Valor)->startOfDay();
            $Valor = $Date->format('c');
        };
        return $Valor;
    }

    public static function prepFilterVal($Valor, $Campo)
    {
        if($Campo['Tipo'] == 'Fecha'){
            $Date  = Carbon::parse($Valor);
            $Valor = $Date->format($Campo['Op4']);
        };
        return $Valor;
    }


    public static function getColName($base, $Columna)
    {
        $base = ($base == "") ? "" : "$base.";
        return str_replace('.', $base, $Columna);
    }

    public static function getSysVariable($Var)
    {
        if($Var == 'Fecha Actual')    { return Carbon::now()->toDateString(); };
        if($Var == 'Hora Actual')     { return Carbon::now()->toTimeString(); };
        if($Var == 'FechaHora Actual'){ return Carbon::now()->toDateTimeString(); };
        if($Var == 'Usuario Logueado'){ return 'SISCORREGO'; }
    }

}