<?php 

namespace App\Functions;
use Config;
use DB;

class ConnHelper
{
	public static function getBDDConn($id)
	{
		$BDD = \App\Models\BDD::where('id', $id)->first();
		return self::getConn($BDD);
	}

	public static function getConn($BDD)
	{
		$conection_name = 'database.connections.db_'.$BDD->id;
		if(substr($BDD->Tipo,0,4) == 'ODBC'){
			Config::set($conection_name, [
				'driver'   => 'odbc',
				'dsn'      => 'odbc:'.$BDD->Op1,
				'username' => $BDD->Usuario,
				'password' => $BDD->Contraseña,
				'host'     => $BDD->Op2,
				'database' => $BDD->Op3,
				'schema'   => $BDD->Op3,
				'charset'  => 'UTF-8'
			]);
		}else if($BDD->Tipo == 'MySQL'){
			Config::set($conection_name, [
				'driver'   => 'mysql',
				'username' => $BDD->Usuario,
				'password' => $BDD->Contraseña,
				'host'     => $BDD->Op2,
				'database' => $BDD->Op3,
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',
				'strict'    => false,
			]);
		}else if($BDD->Tipo == 'PostgreSQL'){
			Config::set($conection_name, [
				'driver'   => 'pgsql',
				'username' => $BDD->Usuario,
				'password' => $BDD->Contraseña,
				'host'     => $BDD->Op2,
				'database' => $BDD->Op3,
				'charset'   => 'utf8',
				'prefix'    => '',
				'schema'   => $BDD->Op4,
			]);
		};

		$conn_id = 'db_'.$BDD->id;
		
		try{
			$Conn = DB::connection($conn_id);
		} catch (\Exception $e) {
			return response()->json([ 'Msg' => "Error al crear la conexión al servidor, {$e->getMessage()}", 'e' => $e->getMessage() ], 512);
		}

		/*if(substr($BDD->Tipo,5,3) == 'DB2'){
			$Conn->setSchemaGrammar(new \App\Models\Core\DB2Grammar);
		};*/

		return $Conn;
	}

	public static function getListaValores($lista_id, $indice_cod)
	{
		$Lista = \App\Models\BDDListas::where('id', $lista_id)->with('bdd')->first();
        $Conn = self::getConn($Lista->bdd);
        
        $Detalle = \App\Functions\GridHelper::getTableName($Lista->Detalle, $Lista->bdd->Op3);

        $Detalles = collect($Conn->table($Detalle[2])->where($Lista->Llave, $indice_cod)->get([ $Lista->DetalleCod, $Lista->DetalleDes ]))->transform(function($Row) use ($Lista){
            return [ 'value' => trim($Row[ $Lista->DetalleCod ]), 'desc' => utf8_encode(trim($Row[ $Lista->DetalleDes ])) ];
        });

        return $Detalles;
	}



}