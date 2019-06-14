<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Usuario;
use App\Functions\Logger as Log;
use Hash;
use Crypt;
use App\Functions\Autenticacion;
use Cache;
use GuzzleHttp\Client;

class UsuarioController extends Controller
{

	public function login($User, $Pass)
	{
		//Agregar la arroba si falta
		$Email = $User;
		$Pos = strpos($User, "@");
		
		if($Pos !== false) $User = substr($User, 0, $Pos);

		$Email = "$User@comfamiliar.com";

		$DaUser = new Usuario;
		$auth = new Autenticacion;

		if($DaUser->authenticate($Email, $Pass)){

			//Actualizar usuarios TEMPORAL
			$userdata = $auth->detallesUsuario($User, $Pass);

			$DaUsuario = Usuario::where('Email', $Email)->first();
			if($DaUsuario->Cedula == null){
				$DaUsuario->fill([
					'Password' => Hash::make($Pass),
					'Nombres'  => $userdata['cn'][0],
					'Cedula'   => $userdata['sn'][0]
				]);
				$DaUsuario->save();
			};

			return Crypt::encrypt($Email);
		}else{

			//Autenticar con SEC
			$userdata = $auth->detallesUsuario($User, $Pass);
			
			if(!$userdata['cn']){
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



	public function postAutologin()
	{
		
		$sec_token = request()->token;
		$auth = new Autenticacion;
		$data = $auth->traducirToken($sec_token);

		return $this->login($data['usuario'], $data['clave']);
	}




	public function postLogin()
	{
		$Email = strtolower(request()->Email);
		$Pass  = request()->Pass;

		return $this->login($Email, $Pass);
	}



	public function validarComfamiliar($usuario, $clave){
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



	public function postCheckToken()
	{
		
		if(request()->session()->has('token_sec')){
			$auth = new Autenticacion;
			$sec_token = request()->session()->pull('token_sec');
			$data = $auth->traducirToken($sec_token);
			$token = $this->login($data['usuario'], $data['clave']);
		}else{
			$token = request()->token;
		};
		
		if(!$token) return response()->json(['Msg' => 'Usuario no autorizado'], 400);

		$Usuario = new Usuario();
		$Usuario = $Usuario->fromToken($token);
		$Usuario->getSecciones();
		$Usuario->getApps();
		$Usuario['token'] = $token;

		//new Log('USER.ENTER', null);

		return $Usuario;
	}


	public function postUpdate()
	{
		$Usuario = request()->Usuario;
		$Email = $Usuario['Email'];

		$DaUser = Usuario::where('Email', $Email)->first();
		$DaUser->Nombres = $Usuario['Nombres'];
		$DaUser->save();
	}



	public function getCdc()
	{
		$Usuarios = Usuario::whereNull('CDC_id')->whereNotNull('Cedula')->get();
		$client = new Client();

		echo "<table><tbdody>";
		foreach ($Usuarios as $U) {
			$Cedula = trim($U['Cedula']);

			$url = "http://sec.comfamiliar.com/usuario/$Cedula.xml";
			$xml = simplexml_load_file($url);

			echo "<tr>
				<td>{$U['Nombres']}</td>
				<td>{$U['Email']}</td>
				<td>{$U['Cedula']}</td>
				<td><img style='height:100px' src='http://sec.comfamiliar.com/images/fotosEmpleados/$Cedula.jpg' /></td>
				<td>{$xml->cuenta->coddependencia}</td>
				<td>{$xml->cuenta->dependencia}</td>
			</tr>";

			Usuario::where('Cedula', $U['Cedula'])->update(['CDC_id' => $xml->cuenta->coddependencia]);
		};
		echo "</tbdody></table>";
	}


	public function postList()
	{
		return Usuario::get();
	}



}
