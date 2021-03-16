<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;
use App\Functions\Helper;

use App\Models\Proceso;

class ProcesosController extends Controller
{
    public function postIndex()
    {
    	$Procesos = Proceso::orderBy('Ruta')->get();

    	foreach ($Procesos as $P) {
    		$P->children = $Procesos->filter(function ($DaP) use ($P) { return $DaP->padre_id == $P->id; })->count();
    		$P->Ruta = ($P->children > 0) ? $P->Ruta : Helper::getDir($P->Ruta);
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

    public function postGetProceso()
    {
        extract(request()->all()); //proceso_id, $Anio

        $Proceso = Proceso::where('id', $proceso_id)
            ->with([
                'padre', 'subprocesos',
                'asignaciones', 'asignaciones.usuario', 'asignaciones.perfil',
                'indicadores'
            ])
            ->first();
        $Proceso->getEquipo();
        $Proceso->getBg();

        $Subprocesos = [];
        foreach ($Proceso->subprocesos as $P) { $P->recolectar($Subprocesos); }
        $subprocesos_ids = collect($Subprocesos)->pluck('id');

        $Proceso->subprocesos_all = $Subprocesos;
        Helper::loadIndicadoresValores($Proceso->indicadores, $Anio);

        //Obtener los Tableros
        $TablerosRaw = \App\Models\Scorecard::with(['nodos'])->get();
        $IndsAll  = \App\Models\Indicador::get()->keyBy('id');
        $Tableros = [];

        foreach ($TablerosRaw as $T) {
            $found = false;

            foreach ($T->nodos as $N) {
                if($N->tipo == 'Indicador'){
                    $Ind = $IndsAll[$N->elemento_id];
                    if(substr($Ind->proceso->Ruta, 0, strlen($Proceso->Ruta)) === $Proceso->Ruta){
                        $found = true; break;
                    }
                }
            }

            if($found) $Tableros[] = $T;
        }

        $Proceso->tableros = $Tableros;


        //$IndicadoresSup = \App\Models\Indicador::whereIn('proceso_id', $subprocesos_ids)->orderBy('Ruta', 'Indicador')->get();
        //$Proceso->indicadores_subprocesos = $IndicadoresSup;

        return $Proceso;
    }
}

