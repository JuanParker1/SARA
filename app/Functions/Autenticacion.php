<?php 

namespace App\Functions;

use App\Functions\cryptastic;

class Autenticacion {
	
	public static function CargarUsuario($username, $servicio, $llave)
	{
		$arreglo = array( 'llave' => $llave, 'servicio' => $servicio, 'login' => $username); 
		$token = urlencode(self::CryptasticCrypt(json_encode($arreglo))); 
		$url = "http://sec.comfamiliar.com/usuario/" . $username . "?token=" . $token; $contenido = ""; 
		$dh = fopen("$url", 'r'); 
		$cnt = 3; 
		while ($cnt and ! feof($dh)) { 
			$contenido .= fread($dh, 8192); 
			$cnt--; 
		} 
		$contenido = substr($contenido, 1, count($contenido) - 2); 
		$usuario = json_decode($contenido); 
		if (!$usuario) { 
			return false; 
		} 
		foreach ($usuario->credenciales as &$rol) { 
			$rol = "ROLE_" . strtoupper($rol); 
		} 
		return $usuario;
	}

	public static function detallesUsuario($usuario, $clave)
	{ 
		$server = (config('app.env') == 'production');

		if(!$server) return false;

		$ldaprdn = 'uid=' . $usuario . ',ou=people,dc=comfamiliar,dc=com'; 
		$ldappass = $clave; 
		$ldapconn = ldap_connect("10.25.2.64") or die("Could not connect to LDAP server."); 
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3); 
		if ($ldapconn) { 
			$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass); 
			if ($ldapbind) { 
				$sr = ldap_search($ldapconn, "ou=people,dc=comfamiliar,dc=com", "(uid=" . $usuario . ")"); 
				$info = ldap_get_entries($ldapconn, $sr); 
				for ($i = 0; $i < $info["count"]; $i++) { 
					//return $info[$i]["sn"][0]; 
					return $info[$i];
				} 
				ldap_close($ldapconn);
			} else { 
				return false;
			}
		}
	}

	public static function validarUsuario($usuario, $clave){ 
		$token = md5($usuario . $clave);
		$usuarioe = base64_encode($usuario);
		$clavee = base64_encode($clave);
		$url = "http://sec.comfamiliar.com/login/" . $usuarioe . "/" . $clavee . "/" . $token . ".xml";
		echo "Validando";
		print_r($url);
		exit();
		$output = file_get_contents($url);
		$xml = simplexml_load_string($output);
		$clave_recuperada = $xml->mensaje;
		if ($clave_recuperada == md5($clave)) return true; else return false;
	} 

	
	public static function traducirUsuario($usuario, $clave, $sistema) {
		$usuarioe = base64_encode($usuario);
		$clavee = base64_encode($clave);
		if ($clavee == "") {
	 		$resultado['inicio'] = false;
	 		$resultado['errorCode'] = "401";
	 		$resultado['errorMessage'] = "No se suministro una clave";
	 	} else { 
	 		$token = md5($usuarioe . $clavee . $sistema);
	 		$url = "http://sec.comfamiliar.com/login/" . $usuarioe . "/" . $clavee . "/" . $token . "/" . $sistema . ".xml";
	 		$output = file_get_contents($url);
	 		$xml = simplexml_load_string($output);
	 		if ($output == false) {
	 			$resultado['error'] = "No se pudo acceder al servicio de autenticacion";
	 		}
	 	}
	 	$resultado['inicio'] = false;
	 	foreach ($xml as $key => $value) {
			if ($key == "errorCode") $resultado['errorCode'] = (string) $value;
			if ($key == "errorMessage") $resultado['errorMessage'] = (string) $value;
			foreach ($value as $key2 => $value2) {
	 			if ($key2 == "errorCode") $resultado['errorCode'] = (string) $value2;
	 			if ($key2 == "errorMessage") $resultado['errorMessage'] = (string) $value2;
	 			if ($key2 == "login") $resultado['login'] = (string) $value2;
	 			if ($key2 == "clave") $resultado['clave'] = (string) $value2;
	 			if ($key2 == "cedula") {
	 				$resultado['cedula'] = (string) $value2;
	 				$resultado['inicio'] = true;
	 			} 
	 			if ($key2 == "credenciales") {
					$credenciales = array();
					foreach ($value2 as $key3 => $value3) {
	 					$tmp = array();
	 					foreach ($value3 as $key4 => $value4) {
							if ($key4 == "id") $tmp['id'] = (string) $value4;
							if ($key4 == "nombre") $tmp['nombre'] = (string) $value4;
							if ($key4 == "descripcion") $tmp['descripcion'] = (string) $value4;
						}
						$credenciales[] = $tmp;
					}
					$resultado['credenciales'] = $credenciales;
				}
			}
		}
		return $resultado;
	} 	


	public static function traducirToken($token) {
		$salt = "zk6X3fDQ";
		$pass = "ppU4Nqu7";
		$cryptastic = new cryptastic;
		$key = $cryptastic->pbkdf2($pass, $salt, 1000, 32);
		$msg = $cryptastic->decrypt($token, $key);
		$v = explode("|", $msg);
		$data = array();
		$data["error"] = true;
		if ($v != false and count($v) == 3 and is_numeric($v[2])) {
			$data["usuario"] = $v[0];
			$data["clave"] = $v[1];
			$data["fecha"] = $v[2];
			$now = time();
			if (abs($now - $v[2]) <= 30)
				$data["error"] = false;
		}
		return $data;
	} 	


	public static function CryptasticCrypt($data){
		$salt = "Av6ANVGnWJJYZfnJ";
		$pass = "dfKeGwAXNvx2uUJf";
		$cryptastic = new cryptastic;
		$key = $cryptastic->pbkdf2($pass, $salt, 1000, 32);
		$data_crypted = $cryptastic->encrypt($data, $key); return $data_crypted;
	}
}
?>