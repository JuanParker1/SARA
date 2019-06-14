<?php 

namespace App\Functions;
use Carbon\Carbon;
use DB;
use App\Functions\ConnHelper;
use App\Functions\DB2Helper;
use App\Functions\MySQLHelper;

class CamposHelper
{
	public static function getTipos()
	{
		$TC = [
			'Entidad'     => [ 'Icon' => 'md-pawn', 			'Divide' => true, 	'Defaults' => [  null, null, null,             null, null] ],
			'Texto'       => [ 'Icon' => 'md-format-quote', 	'Divide' => false, 	'Defaults' => [  null, null, null,             null, null] ],
			'TextoLargo'  => [ 'Icon' => 'md-insert-comment', 	'Divide' => true, 	'Defaults' => [  1000, null, null,             null, null] ],
			'Entero'      => [ 'Icon' => 'my-entero', 			'Divide' => false, 	'Defaults' => [  null, null, null,             null, null] ],
			'Decimal'     => [ 'Icon' => 'my-decimal', 			'Divide' => false, 	'Defaults' => [  null, null,    1,             null, null] ],
			'Dinero'      => [ 'Icon' => 'md-money', 			'Divide' => true, 	'Defaults' => [  null, null,    1,             null, null] ],
			'Booleano'    => [ 'Icon' => 'md-toggle-on', 		'Divide' => true, 	'Defaults' => [  null, null, null,             'Si', 'No'] ],
			'Fecha'       => [ 'Icon' => 'md-calendar-event', 	'Divide' => false, 	'Defaults' => [  null, null, null,          'Y-m-d', null] ],
			'Hora'        => [ 'Icon' => 'md-time', 			'Divide' => false, 	'Defaults' => [  null, null, null,          'H:i:s', null] ],
			'FechaHora'   => [ 'Icon' => 'md-timer', 			'Divide' => true, 	'Defaults' => [  null, null, null,    'Y-m-d H:i:s', null] ],
			'Color'   	  => [ 'Icon' => 'md-color', 			'Divide' => false, 	'Defaults' => [  null, null, null,             null, null] ],
		];

		$TC['Fecha']['Formatos']      = [ ['Y-m-d','2019-12-31'], ['Ymd', '20191231'] ];
		$TC['Hora']['Formatos']  	  = [ ['H:i:s',  '23:59:59'], ['His',   '235959'], ['H:i',  '23:59'], ['Hi',   '2359'] ];
		$TC['FechaHora']['Formatos']  = [ ['Y-m-d H:i:s', '2019-12-31 23:59:59'], ['YmdHis', '20191231235959'], ['Y-m-d H:i', '2019-12-31 23:59'], ['YmdHi', '201912312359'] ];

		$TC = collect($TC)->transform(function($T){
			$Def = [];
			foreach ($T['Defaults'] as $k => $d) { $Def['Op'.($k+1)] = $d; };
			$T['Defaults'] = $Def;
			return $T;
		});

		

		return $TC;
	}

	public static function getTableSchema($Tabla, $SchemaDef)
    {
        if (strpos($Tabla, '.') !== false) {
            $ST = explode('.', $Tabla);
            $SchemaDef = $ST[0];
            $Tabla = $ST[1];
        }
        return [ $SchemaDef, $Tabla, "$SchemaDef.$Tabla" ];

	}

	public static function autoget($Bdd, $Entidad, $Campos)
	{

        $Conn = ConnHelper::getConn($Bdd);
        $SchemaTabla = self::getTableSchema($Entidad['Tabla'], $Bdd->Op3);

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

	

    public static function getBaseQuery($Entidad)
    {
    	$Bdd = \App\Models\BDD::where('id', $Entidad['bdd_id'])->first();
    	$Conn = ConnHelper::getConn($Bdd);
        $Conn->setFetchMode(\PDO::FETCH_NUM);
        $SchemaTabla = self::getTableSchema($Entidad['Tabla'], $Bdd->Op3);

        return $Conn->table($SchemaTabla[2]." AS t0");
    }



    public static function getUniqueTable($Ruta, $Llaves)
    {
    	$tabla_id = "t";
        foreach ($Ruta as $k => $t) {
            if($k>0) $tabla_id .= "k".$Llaves[$k]."j";
            $tabla_id .= $t;
        };
        return $tabla_id;
    }


    public static function getElm($Collection, $Value, $Key = 'id')
    {
        return collect($Collection)->filter(function ($elm) use ($Key, $Value){
            return $elm[$Key] == $Value;
        })->first();
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

    public static function prepData($Campos, $Datos)
    {
        $Datos = collect($Datos)->transform(function($row) use ($Campos){
            return collect($row)->transform(function($c,$i) use ($Campos){
                return self::prepDato($Campos[$i], $c);
            });
        });

        return $Datos;
    }

}