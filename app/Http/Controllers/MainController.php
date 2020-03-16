<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use File;
use App\Functions\CRUD;


class MainController extends Controller
{
    


    public function getBase(){ 
    	
    	if(!config('app.online')){
    		return view('Offline');
    	};


    	if(request()->has('token')){
    		$token = request()->token;
    		session(['token_sec' => $token]);
    		return redirect('/');
    	};

    	return view('Base');
    }
    public function getLogin(){ 
    	return view('Login'); 
   	}
   	public function getApp(){ 
    	return view('Apps.App_View');
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


	public function getTest()
	{
		$User = \App\Models\Usuario::where('Email', 'corrego@comfamiliar.com')->first();
		$Pass = \Crypt::decrypt($User->Password);
		return $User;
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
		$CRUD = new CRUD('App\Models\Comentario');
        return $CRUD->call(request()->fn, request()->ops);
	}


	//Búsqueda
	public function postMainSearch()
	{
		extract(request()->all()); //searchText

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
		$Scorecards = \App\Models\Scorecard::buscar($searchText)->get()->transform(function($E){
			$E['Tipo'] = 'Tablero';
			$E['Secundario'] = null;
			$E['Icono'] = 'fa-th-large';
			return $E;
		});
		if($Scorecards->count() > 0) $groups[] = 'Tableros'; 

		$res = $res->merge($Scorecards);
		$res = $res->merge($Indicadores);
		$res = $res->merge($Variables);
		

		return [ 'results' => $res, 'groups' => $groups ];


	}
	


}
