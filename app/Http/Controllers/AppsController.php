<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Apps;
use App\Models\Usuario;

class AppsController extends Controller
{

    public function postIndex()
    {
        return Apps::all();
    }

    public function postFavorito()
    {
    	extract(request()->all()); //$favorito
		\App\Models\UserApps::where(compact('usuario_id','app_id'))->update(['favorito' => $favorito]);
    }

}
