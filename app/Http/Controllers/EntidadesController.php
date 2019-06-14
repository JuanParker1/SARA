<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;

use App\Models\Entidad;
use App\Models\EntidadCampo;
use App\Models\EntidadGrid;
use App\Models\BDD;

use App\Functions\CamposHelper;
use DB;
use Carbon\Carbon;

class EntidadesController extends Controller
{
    
	//Entidades
    public function postIndex()
    {
        $CRUD = new CRUD('App\Models\Entidad');
        return $CRUD->call(request()->fn, request()->ops);
    }



    //Campos
    public function postCampos()
    {
        $CRUD = new CRUD('App\Models\EntidadCampo');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postTiposCampo()
    {
    	return CamposHelper::getTipos();
    }

    public function postCamposDelete()
    {
        $ids = request()->ids;
        EntidadCampo::whereIn('id', $ids)->delete();
        return 'OK';
    }

    public function postCamposUpdate()
    {
        $Campos = request()->Campos;
        foreach ($Campos as $C) {
            if(!array_key_exists('changed', $C)) continue;
            if(!$C['changed']) continue;
            $DaCampo = EntidadCampo::where('id', $C['id'])->first();
            $DaCampo->fillit($C);
            $DaCampo->save();
        }
    }

    public function postCamposAutoget()
    {
        extract(request()->all()); //Bdd, Entidad, Campos
        $Bdd = BDD::where('id', $Bdd['id'])->first();
        return CamposHelper::autoget($Bdd, $Entidad, $Campos);
    }

    public function postCamposAdd()
    {
        extract(request()->all()); //newCampos
        EntidadCampo::insert($newCampos);
    }



    //Grids
    public function postGrids()
    {
        $CRUD = new CRUD('App\Models\EntidadGrid');
        return $CRUD->call(request()->fn, request()->ops);
    }


    //Grids - Columnas
    public function postGridsColumnas()
    {
        $CRUD = new CRUD('App\Models\EntidadGridColumna');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postGridsColumnasUpdate()
    {
        $Columnas = request()->Columnas;
        foreach ($Columnas as $C) {
            if(!array_key_exists('changed', $C)) continue;
            if(!$C['changed']) continue;
            $DaColumna = \App\Models\EntidadGridColumna::where('id', $C['id'])->first();
            $DaColumna->fillit($C);
            $DaColumna->save();
        }
    }


    public function postGridsGetData()
    {
        
        $grid_id = request()->grid_id;
        $Grid = EntidadGrid::where('id', $grid_id)->with(['columnas', 'entidad'])->first();

        $RowsLimit = $Grid->entidad->max_rows ?: 100;

        $q = CamposHelper::getBaseQuery($Grid->entidad)->limit($RowsLimit);
        $entidades_ids = [];
        $tablas = [];
        $tablas_consec = 0;

        foreach ($Grid->columnas as $C) {

            //Registrar tablas de la ruta
            for ($i=0; $i < count($C->Ruta); $i++) { 
                $tabla_ruta = array_slice($C->Ruta,0,($i+1));
                $tabla_id = CamposHelper::getUniqueTable($tabla_ruta, $C->Llaves);


                if(!array_key_exists($tabla_id, $tablas)){
                    $entidad_destino = array_slice($tabla_ruta,-1,1)[0];
                    $entidades_ids[] = $entidad_destino;

                    $tablas[$tabla_id] = [
                        'id'              => $tabla_id,
                        'origen_id'       => CamposHelper::getUniqueTable(array_slice($C->Ruta,0,($i)), $C->Llaves),
                        'nivel'           => count($tabla_ruta),
                        'entidad_origen'  => array_slice($tabla_ruta,-2,1)[0],
                        'llave_id'        => $C->Llaves[$i],
                        'entidad_destino' => $entidad_destino,
                        'consec'          => $tablas_consec++,
                    ];
                }
            };

            $C['tabla_consec'] = "\"t".$tablas[CamposHelper::getUniqueTable($C->Ruta, $C->Llaves)]['consec']."\"";
        };

        $entidades_ids = array_unique($entidades_ids);
        $Entidades = Entidad::whereIn('id',              $entidades_ids)->get();
        $Campos    = EntidadCampo::whereIn('entidad_id', $entidades_ids)->get();

        foreach ($tablas as $tb) {
            if($tb['nivel'] == 1) continue;

            $EntidadOrigen  = CamposHelper::getElm($Entidades, $tb['entidad_origen']);
            $EntidadDestino = CamposHelper::getElm($Entidades, $tb['entidad_destino']);

            $CampoOrigen  = CamposHelper::getElm($Campos,  $tb['llave_id']);
            $CampoDestino = CamposHelper::getElm($Campos, $EntidadDestino->campo_llaveprim);

            if(is_null($CampoDestino)) dd("Error de configuración, entidad '{$EntidadDestino->Nombre}' sin llave primaria.");

            $q = $q->leftJoin($EntidadDestino->getTableName()[2]." AS t".$tb['consec']
                              ,$CampoOrigen->getColName("t".$tablas[$tb['origen_id']]['consec']) ,'=' 
                              ,$CampoDestino->getColName("t".$tb['consec']));
        };
        
        $DaCampos = [];
        foreach ($Grid->columnas as $C) {
            $Campo = CamposHelper::getElm($Campos,  $C['campo_id']);
            $Col = $Campo->getColName($C['tabla_consec']);
            $Alias = $Campo->Alias ?: $Campo->getColName('');

            $q = $q->addSelect([DB::raw("$Col AS \"$Alias\"")]);
            $DaCampos[] = $Campo;
            $C['campo'] = $Campo;
            $C['header'] = ($Campo->Alias == null) ? $Campo->getColName('') : $Campo->Alias;
            $C['header_numeric'] = in_array($Campo->Tipo, ['Entero','Decimal','Dinero']);
        };

        //Ordenar
        if(!is_null($Grid->entidad->campo_orderby)){
            $CampoOrder = CamposHelper::getElm($Campos, $Grid->entidad->campo_orderby);
            $q = $q->orderBy($CampoOrder->getColName("t0"), $Grid->entidad->campo_orderbydir);
        };

        $q = $q->where(DB::raw('"t1".BENUMDOCBE'), 'LIKE', '1093217141');

        $Grid->sql = [ 'query' => $q->toSql() ];
        //return $Grid->sql['query'];
        $Grid->data = CamposHelper::prepData($DaCampos, $q->get());
        return compact('Grid');
    }

    public function getGrid()
    {
        return $this->postGridsGetData();
    }

}
