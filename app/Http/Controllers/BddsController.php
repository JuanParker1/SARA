<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;

use App\Models\BDD;
use App\Functions\ConnHelper;
use App\Functions\GridHelper;
use App\Functions\CamposHelper;
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

        if(get_class($Conn) == 'Illuminate\Http\JsonResponse') return $Conn;

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




    //Listas
    public function postListas()
    {
        $CRUD = new CRUD('App\Models\BDDListas');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postGetListas()
    {
        extract(request()->all()); //bdd_id
        return \App\Models\BDDListas::where('bdd_id', $bdd_id)->get();
    }

    public function postGetIndices()
    {
        extract(request()->all());

        $Lista = \App\Models\BDDListas::where('id', $lista_id)->with('bdd')->first();
        $Conn = ConnHelper::getConn($Lista->bdd);
        $Indice = GridHelper::getTableName($Lista->Indice, $Lista->bdd->Op3);
        $Indices = collect($Conn->table($Indice[2])->get([ $Lista->IndiceCod, $Lista->IndiceDes ]))->transform(function($Row) use ($Lista){
            return [ 'IndiceCod' => $Row[ $Lista->IndiceCod ], 'IndiceDes' => utf8_encode(trim($Row[ $Lista->IndiceDes ])) ];
        });

        return compact('Indices');
    }

    public function postGetListadetalles()
    {
        extract(request()->all()); //lista_id, indice_cod
        $Lista = \App\Models\BDDListas::where('id', $lista_id)->with('bdd')->first();
        $Conn = ConnHelper::getConn($Lista->bdd);
        
        $Detalle = GridHelper::getTableName($Lista->Detalle, $Lista->bdd->Op3);

        $Detalles = collect($Conn->table($Detalle[2])->where($Lista->Llave, $indice_cod)->get([ $Lista->DetalleCod, $Lista->DetalleDes ]))->transform(function($Row) use ($Lista){
            return [ 'DetalleCod' => trim($Row[ $Lista->DetalleCod ]), 'DetalleDes' => utf8_encode(trim($Row[ $Lista->DetalleDes ])) ];
        });

        return $Detalles;
    } 

}
