<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;

use App\Models\BDD;
use App\Functions\ConnHelper;
use DB;

class BddsController extends Controller
{
    //BDDs
    public function postIndex()
    {
        $CRUD = new CRUD('App\Models\BDD');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postAll()
    {
    	return BDD::get(['id','Nombre']);
    }


    public function postProbar($returnConn = false)
    {
    	$BDD = (object) request()->BDD;
    	$Conn = ConnHelper::getConn($BDD);

    	try {
			$Conn->getPdo();
		} catch (\Exception $e) {
			return response()->json([ 'Msg' => "Error al conectar.  Por favor revise las credenciales.", 'e' => $e->getMessage(), 'Conn' => $Conn ], 512);
		}
    }


    function postQuery()
    {
    	$BDD = (object) request()->BDD;
    	$Conn = ConnHelper::getConn($BDD);

    	try { 
			$Rows = collect($Conn->select(request()->Query))->transform(function($row){
				return array_map('utf8_encode', $row);
			});
			return $Rows;
		} catch(\Illuminate\Database\QueryException $ex){
			return response()->json([ 'Msg' => $ex->getMessage(), 'e' => $ex ], 512);
		};
    }



    //Favoritos
    public function postFavoritos()
    {
        $CRUD = new CRUD('App\Models\BDDFavoritos');
        return $CRUD->call(request()->fn, request()->ops);
    }


}
