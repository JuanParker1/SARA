<?php 

namespace App\Functions;
use Config;
use DB;

class ConnHelper
{
	public static function getConn($BDD)
	{
		$conection_name = 'database.connections.db_'.$BDD->id;
		if(substr($BDD->Tipo,0,4) == 'ODBC'){
			Config::set($conection_name, [
				'driver'   => 'odbc',
				'dsn'      => 'odbc:\\\\'.$BDD->Op1,
				'username' => $BDD->Usuario,
				'password' => $BDD->Contraseña,
				'host'     => $BDD->Op2,
				'database' => $BDD->Op3,
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
		};

		$conn_id = 'db_'.$BDD->id;
		return DB::connection($conn_id);
	}
}