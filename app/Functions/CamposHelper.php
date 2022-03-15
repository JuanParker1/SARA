<?php 

namespace App\Functions;
use Carbon\Carbon;
use DB;
use App\Functions\Helper;
use App\Functions\ConnHelper;
use App\Functions\DB2Helper;
use App\Functions\MySQLHelper;
use App\Functions\PostgreSQLHelper;

use App\Functions\GridHelper;

class CamposHelper
{
	public static function getTipos()
	{
		$TC = [
			'Entidad'        => [ 'Icon' => 'md-pawn', 			     'Divide' => true, 	       'Defaults' => [    '',   '',    0,               '',   ''], 'DefaultValor' =>  '',          'DefaultComparador' => ''   ],
			'Texto'          => [ 'Icon' => 'md-format-quote', 	     'Divide' => false, 	   'Defaults' => [    '',   '',    0,               '',   ''], 'DefaultValor' =>  '',          'DefaultComparador' => '='  ],
            'TextoLargo'     => [ 'Icon' => 'md-insert-comment',     'Divide' => true,         'Defaults' => [  1000,   '',    0,               '',   ''], 'DefaultValor' =>  '',          'DefaultComparador' => ''   ],
            'Lista'          => [ 'Icon' => 'md-list-view',          'Divide' => false,        'Defaults' => [    '',   '',    0,             '[]',   ''], 'DefaultValor' =>  '',          'DefaultComparador' => 'lista' ],
			'ListaAvanzada'  => [ 'Icon' => 'md-list-alt', 	         'Divide' => true, 	       'Defaults' => [    '',   '',    0,             '[]',   ''], 'DefaultValor' =>  '',          'DefaultComparador' => ''   ],
			'Entero'         => [ 'Icon' => 'my-entero', 			 'Divide' => false, 	   'Defaults' => [    '',   '',    0,               '',   ''], 'DefaultValor' =>  null,        'DefaultComparador' => '>=' ],
            'Decimal'        => [ 'Icon' => 'my-decimal',            'Divide' => false,        'Defaults' => [    '',   '',    1,               '',   ''], 'DefaultValor' =>  null,        'DefaultComparador' => '>=' ],
			'Porcentaje'     => [ 'Icon' => 'md-percent', 			 'Divide' => false, 	   'Defaults' => [   '0',  '1',    1,               '',   ''], 'DefaultValor' =>  null,        'DefaultComparador' => '>=' ],
			'Dinero'         => [ 'Icon' => 'md-money', 			 'Divide' => true, 	       'Defaults' => [    '',   '',    1,               '',   ''], 'DefaultValor' =>  null,        'DefaultComparador' => '>=' ],
			'Booleano'       => [ 'Icon' => 'md-toggle-on', 		 'Divide' => true, 	       'Defaults' => [    '',   '',    0,             'Si', 'No'], 'DefaultValor' =>  '',          'DefaultComparador' => ''   ],
            'Periodo'        => [ 'Icon' => 'md-calendar',           'Divide' => false,        'Defaults' => [    '',   '',    0,             'Ym',   ''], 'DefaultValor' =>  'today',     'DefaultComparador' => '>=' ],
			'Fecha'          => [ 'Icon' => 'md-calendar-event', 	 'Divide' => false, 	   'Defaults' => [ 'rel',   '',    0,          'Y-m-d',   ''], 'DefaultValor' =>  'today',     'DefaultComparador' => '>=' ],
			'Hora'           => [ 'Icon' => 'md-time', 			     'Divide' => false, 	   'Defaults' => [    '',   '',    0,          'H:i:s',   ''], 'DefaultValor' =>  '',          'DefaultComparador' => ''   ],
			'FechaHora'      => [ 'Icon' => 'md-timer', 			 'Divide' => true, 	       'Defaults' => [    '',   '',    0,    'Y-m-d H:i:s',   ''], 'DefaultValor' =>  '',          'DefaultComparador' => ''   ],
            'Color'          => [ 'Icon' => 'md-color',              'Divide' => false,        'Defaults' => [    '',   '',    0,               '',   ''], 'DefaultValor' =>  '',          'DefaultComparador' => ''   ],
            'Imagen'         => [ 'Icon' => 'md-image',              'Divide' => false,        'Defaults' => [    '',   '',    0,               '',   ''], 'DefaultValor' =>  '',          'DefaultComparador' => ''   ],
			'Link'   	     => [ 'Icon' => 'md-link', 			     'Divide' => false, 	   'Defaults' => [    '',   '',    0,               '',   ''], 'DefaultValor' =>  '',          'DefaultComparador' => ''   ],
		];

		$TC['Periodo']['Formatos']      = [ ['Ym','201912'] ]; //['Y-m', '2019-12']
        $TC['Fecha']['Formatos']      = [ ['Y-m-d','2019-12-31'], ['Ymd', '20191231'] ];
		$TC['Hora']['Formatos']  	  = [ ['H:i:s',  '23:59:59'], ['His',   '235959'], ['H:i',  '23:59'], ['Hi',   '2359'] ];
		$TC['FechaHora']['Formatos']  = [ ['Y-m-d H:i:s', '2019-12-31 23:59:59'], ['YmdHis', '20191231235959'], ['Y-m-d H:i', '2019-12-31 23:59'], ['YmdHi', '201912312359'], ['Y-m-d H:i:s.u', '2019-12-31 23:59:59.000000'] ];

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
            ['-5 days','Hace 5 días'],  
            ['-4 days','Hace 4 días'],  
            ['-3 days','Hace 3 días'],  
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

        $TC['Periodo']['Comparators'] = ['=' => 'Es','>=' => 'Desde','<=' => 'Hasta','nulo' => 'Es nulo','no_nulo' => 'No es nulo'];
        $TC['Periodo']['Relatives']     = [
            ['first day of january last year','Primer mes del año pasado'],     
            ['first day of january this year','Primer mes de este año'],        
            ['-3 month','Hace 3 meses'],
            ['-2 month','El mes antepasado'],
            ['-1 month','El mes pasado'],
            ['today','Este mes'],
            ['+1 month','El próximo mes'],
            ['+2 months','En 2 meses'],                     
            ['+3 months','En 3 meses'],                     
            ['last day of december this year','Último mes de este año'],
            ['last day of december next year','Último mes del prox. año'],
        ];


 
		$TC = collect($TC)->transform(function($T){
			$Def = [];
			foreach ($T['Defaults'] as $k => $d) { $Def['Op'.($k+1)] = $d; };
			$T['Defaults'] = $Def;
			return $T;
		});

		return $TC;
	}

    public static function getHelper($Bdd)
    {
        if(in_array($Bdd->Tipo, ['ODBC_DB2'])) return new DB2Helper();
        if(in_array($Bdd->Tipo, ['ODBC_MySQL', 'MySQL'])) return new MySQLHelper();
        if(in_array($Bdd->Tipo, ['PostgreSQL'])) return new PostgreSQLHelper();
    }

    public static function getTableRoute($Bdd, $Tabla)
    {
        $DBHelper = self::getHelper($Bdd);
        return $DBHelper->getTableRoute($Bdd, $Tabla);
    }

	public static function autoget($Bdd, $Entidad, $Campos)
	{

        $Conn = ConnHelper::getConn($Bdd);
        $SchemaTabla = GridHelper::getTableName($Entidad['Tabla'], $Bdd->Op3);
        $tiposCampo = self::getTipos();

        $DBHelper = self::getHelper($Bdd);
        $tableRoute = $DBHelper->getTableRoute($Bdd, $Entidad['Tabla']);
        $newCampos = $DBHelper->getColumns($Conn, $tableRoute);
        return $DBHelper->standarizeColumns($newCampos, $Bdd, $Entidad, $Campos, $tiposCampo);
	}

    public static function prepDato($Campo, $D)
    {
        if(is_null($D)) return $D;

        if($Campo['Tipo'] == 'Entero'){ return intval($D); }

        if($Campo['Tipo'] == 'Fecha'){
            $Date = Carbon::createFromFormat($Campo['Op4'], $D);
            return $Date->toDateString();
        }

        if($Campo['Tipo'] == 'Hora'){ 
            if(strlen($D) < 4) $D = str_pad($D, 4, "0", STR_PAD_LEFT);
            $Date = Carbon::createFromFormat($Campo['Op4'], $D);
            if(in_array($Campo['Op4'], ['Hi','H:i'])){ return $Date->format('H:i'); }
            return $Date->toTimeString();
        }

        if($Campo['Tipo'] == 'FechaHora'){
            $D = str_limit($D, 19, '');
            $Date = Carbon::createFromFormat($Campo['Op4'], $D);
            return $Date->format('Y-m-d H:i');
        }

        if(in_array($Campo['Tipo'], ['Texto','TextoLargo','Lista'])){
            if(config('app.encode_utf8')) $D = utf8_encode($D);
            $D = trim($D);
        };

        if($Campo['Tipo'] == 'Porcentaje'){ 
            return Helper::formatVal($D, "Porcentaje"); //$Campo['Op3']
        }

        if($Campo['Tipo'] == 'Dinero'){ 
            return Helper::formatVal($D, "Moneda");
        }

        if($Campo['Tipo'] == 'Imagen'){
            $img_ruta = str_replace('$id', $D, $Campo['Config']['img_ruta']);
            $img_ruta = file_exists(public_path($img_ruta)) ? ($img_ruta.'?'.uniqid()) : null;
            $D = [ 'id' => $D, 'url' => $img_ruta, 'changed' => false ];
        }

        return $D;
    }

    public static function prepDatoUp($Campo, $D, $F)
    {
        if(is_null($D)) return $D;

        if($Campo['Tipo'] == 'Decimal'){
            $D = round($D, $Campo['Op3']);
        };

        if($Campo['Tipo'] == 'Periodo'){
            $Date = new Carbon($D);
            return $Date->format($Campo['Op4']);
        }

        if($Campo['Tipo'] == 'FechaHora'){
            $Date = Carbon::parse($D);
            $D = $Date->setTimezone(config('app.timezone'))->format($Campo['Op4']);
        };

        if(in_array($Campo['Tipo'], ['Texto','TextoLargo','Lista'])){ 
            if(config('app.encode_utf8')) $D = utf8_decode($D);
            $D = trim($D);
        };

        if($Campo['Tipo'] == 'ListaAvanzada'){
            if ($Campo['Op4'] == 'AddDate' AND $D == '_SELECT_DATE_') {
                $Date = Carbon::parse($F['val_aux']);
                $D = $Date->setTimezone(config('app.timezone'))->toDateString();
            }
        }

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


    public static function getColName($base = "", $Columna)
    {
        $base = ($base == "") ? "" : "$base.";

        $Columna = preg_replace('/([a-z]|[0-9])\K(\.)/i', ':::', $Columna);
        $Columna = str_replace('.', $base, $Columna);
        $Columna = str_replace(':::', '.', $Columna);

        return $Columna;
    }

    public static function getSysVariable($Var)
    {
        if($Var == 'Fecha Actual')    { return Carbon::now()->toDateString(); };
        if($Var == 'Hora Actual')     { return Carbon::now()->toTimeString(); };
        if($Var == 'FechaHora Actual'){ return Carbon::now()->toDateTimeString(); };
        if($Var == 'Usuario Logueado'){ return 'SISCORREGO'; }
    }

    public static function getCreateStatement($entidad_id)
    {
        $Entidad = \App\Models\Entidad::find($entidad_id);
        $Bdd     = \App\Models\BDD::find($Entidad['bdd_id']);

        $DBHelper = self::getHelper($Bdd);
        $Conn    = ConnHelper::getConn($Bdd);
        $tableRoute = $DBHelper->getTableRoute($Bdd, $Entidad['Tabla']);
        return $DBHelper->getCreateStatement($Conn, $tableRoute, $Entidad);
    }

}