<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use File;



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

}
