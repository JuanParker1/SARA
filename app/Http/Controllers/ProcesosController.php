<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;

use App\Models\Proceso;

class ProcesosController extends Controller
{
    public function postIndex()
    {
    	$Procesos = Proceso::orderBy('Ruta')->get();

    	foreach ($Procesos as $P) {
    		$P->children = $Procesos->filter(function ($DaP) use ($P) { return $DaP->padre_id == $P->id; })->count();
    		//$P->Ruta = ($P->children > 0) ? $P->fullruta : dirname($P->fullruta);
    	};

    	return $Procesos;
    }

    public function postUpdate()
    {
        extract(request()->all());

        $DaProceso = Proceso::where('id', $Proceso['id'])->first();
        $DaProceso->fillit($Proceso);
        $DaProceso->save();
    }

    public function postCreate()
    {
        extract(request()->all());
        Proceso::create($Proceso);
    }
}
