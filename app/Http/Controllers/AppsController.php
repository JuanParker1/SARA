<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;
use App\Models\Apps;
use App\Models\Usuario;
use App\Functions\Helper AS H;

class AppsController extends Controller
{

    public function postIndex()
    {
        return Apps::all();
    }

    public function postApps()
    {
    	$CRUD = new CRUD('App\Models\Apps');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postPages()
    {
        $CRUD = new CRUD('App\Models\AppPages');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postSlug()
    {
        $Apps = Apps::all();
        $Slugs = array_filter($Apps->pluck('Slug')->toArray());
        do {
            $Slug = H::randomString(5);
        } while (in_array($Slug, $Slugs));

        return $Slug;
    }

    public function postFavorito()
    {
    	extract(request()->all()); //$favorito
		\App\Models\UserApps::where(compact('usuario_id','app_id'))->update(['favorito' => $favorito]);
    }

    public function postAppGet()
    {
        $app_id = request('app_id');
        $App = Apps::where('Slug', $app_id)->with(['pages'])->first();

        return compact('App');
    }

}
