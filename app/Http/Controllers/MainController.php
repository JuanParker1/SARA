<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use File;
use App\Functions\CRUD;
use App\Functions\Helper;

use Carbon\Carbon;

class MainController extends Controller
{

    public function getBase(){ 
    	
    	if(!config('app.online')){
    		return view('Offline');
    	};

    	$AppName = Helper::getAppName();

    	/*if(request()->has('token')){
    		$token = request()->token;
    		session(['token_sec' => $token]);
    		return redirect('/');
    	};*/

    	return view('Base', compact('AppName'));
    }
    public function getLogin(){ 
    	return view('Login'); 
   	}
   	public function getApp(){ 
    	return view('Apps.App_View');
   	}
   	
   	public function getIntView($IntId){ 
    	return view('Integraciones.'.$IntId);
   	}
    
    public function getHome(){  return view('Home'); }

    public function openView($vista, $data)
    {
    	if (view()->exists($vista))
		{
			return view($vista, $data);
		}else{
			$resp = "<h2 class='md-display-1 margin'>$vista en desarrollo...</h2>";
			return $resp;
		}
    }

    public function GetSection($section)
	{
		$vista = "$section.$section";
		return $this->openView($vista, compact('section'));		
	}

	public function GetSubsection($section, $subsection)
	{
		$vista = $section.".".$section."_".$subsection;
		//implode('.', [$section, $subsection]);
		return $this->openView($vista, compact('section', 'subsection'));	
	}

	public function GetFragment($fragment)
	{
		return $this->openView($fragment, request()->all());
	}



	public function GetFile()
	{
		$filename = public_path(request()->file);
		return response()->download($filename);
	}


	public function postLog()
	{
		$d = request()->all();
		new Log($d['Entity'], $d['Entity_id'], $d['Msj']);
	}


	public function getPass($Value)
	{
		return \Hash::make($Value);
	}

	public function getIp()
	{
		return $_SERVER;
		return request()->ip();
	}

	public function getIconos()
	{
		$Iconos = \App\Models\Icono::all();
		$Categorias = $Iconos->unique('Categoria')->pluck('Categoria')->toArray();
		sort($Categorias);
		return compact('Iconos','Categorias');
	}

	//Comentarios
	public function postComentarios()
	{
		$CRUD = new \App\Functions\CRUD('App\Models\Comentario');
        return $CRUD->call(request()->fn, request()->ops);
	}


	//Búsqueda
	public function postMainSearch()
	{
		extract(request()->all()); //searchText
		$Usuario = Helper::getUsuario();
		$Usuario->getApps();

		$searchText = trim($searchText);

		$res = collect([]);
		$groups = [];

		//Indicadores
		$Indicadores = \App\Models\Indicador::buscar($searchText)->get()->transform(function($E){
			$E['Tipo'] = 'Indicador';
			$E['Secundario'] = $E['proceso']['Proceso'];
			$E['Icono'] = 'fa-chart-line';
			return $E;
		});
		if($Indicadores->count() > 0) $groups[] = 'Indicadores'; 

		//Variables
		$Variables = \App\Models\Variable::buscar($searchText)->get()->transform(function($E){
			$E['Tipo'] = 'Variable';
			$E['Secundario'] = $E['proceso']['Proceso'];
			$E['Icono'] = 'fa-superscript';
			return $E;
		});
		if($Variables->count() > 0) $groups[] = 'Variables'; 

		//Scorecards
		/*$Scorecards = \App\Models\Scorecard::buscar($searchText)->get()->transform(function($E){
			$E['Tipo'] = 'Tablero';
			$E['Secundario'] = null;
			$E['Icono'] = 'fa-th-large';
			return $E;
		});
		if($Scorecards->count() > 0) $groups[] = 'Tableros';*/

		//Informes
		$Informes = $Usuario->Apps->filter(function($I) use ($searchText){
			return !(stripos( $I['Titulo'], $searchText) === FALSE);
		})->map(function($I){
			$I['Tipo'] = 'Reporte';
			return $I;
		});
		if($Informes->count() > 0) $groups[] = 'Reportes'; 
	




		$res = $res->merge($Informes);
		$res = $res->merge($Indicadores);
		$res = $res->merge($Variables);
		

		return [ 'results' => $res, 'groups' => $groups ];


	}
	
	public function postAddLog()
	{
		$Log = request()->all();

		//if(!array_key_exists('usuario_id', $Log)) $Log['usuario_id'] = 

		\App\Models\Log::create($Log);
	}

	public function postGetFavorites()
	{
		$Usuario = Helper::getUsuario();
		$Usuario->getApps(true);

		$Pages = [];
		foreach ($Usuario->Apps as $A) {
			foreach ($A->pages as $P) {
				$Pages[$P->id] = $A;
			}
		}

		$Recientes = [];
		$pages_done = [];
		$FromDay = Carbon::now()->subDays(10);
		$Logs = \App\Models\Log::where('Evento', 'AppPage')->where('usuario_id', $Usuario->id)->where('created_at', '>=', $FromDay)->orderBy('created_at', 'DESC')->get();

		foreach ($Logs as $L) {
			if(in_array($L->Op1, $pages_done)) continue;
			if(!array_key_exists($L->Op1, $Pages)) continue;

			$app  = $Pages[$L->Op1];
			$page = $app->pages->where('id', $L->Op1)->first();

			$Recientes[] = [
				'app' => $app,
				'page' => $page
			];

			$pages_done[] = $L->Op1;
		}

		/*$DaRecientes = \App\Models\Recientes::where('usuario_id', $Usuario->id)->limit(50)->get();

		foreach ($DaRecientes as $R) {
			if(!array_key_exists($R->Url, $Recientes) AND count($Recientes) < 7) $Recientes[$R->Url] = $R;
		}

		$Recientes = array_values($Recientes);*/

		return compact('Recientes');
	}

	public function postUploadImage()
	{
		extract(request()->all()); //width, height, imagemode, savepath
		$img = \Image::make($_FILES['file']['tmp_name']);
		
		if($imagemode == 'Recortar'){      $img->fit($width, $height); }
		if($imagemode == 'Ajustar Ancho'){ $img->resize($width, null,    function ($constraint){ $constraint->aspectRatio(); }); }
		if($imagemode == 'Ajustar Alto'){  $img->resize(null,   $height, function ($constraint){ $constraint->aspectRatio(); }); }
		if($imagemode == 'Contener'){      $img->fit($width, $height); }

		if(!isset($savepath) OR $savepath == 'null'){
			$uid = uniqid('image_');
			$savepath = "temp/$uid.jpg";
		};

		$img->save($savepath);

		return $savepath;
	}


	public function postFeedback()
	{
		extract(request()->all()); //$Subject, $feedbackComment, $usuario_id
		$feedback = new \App\Models\Feedback([
			'Tema' => $Subject,
			'Comentario' => $feedbackComment,
			'usuario_id' => $usuario_id
		]);

		$feedback->save();

	}



	public function postGetConfiguracion()
	{
		return Helper::getConfiguracion();
	}

	public function postSaveConfiguracion()
	{
		extract(request()->all()); //$Conf
		Helper::saveConfiguracion($Conf);
	}

}
