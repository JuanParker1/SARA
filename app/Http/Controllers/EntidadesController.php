<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;

use App\Models\Entidad;
use App\Models\EntidadCampo;
use App\Models\EntidadRestriccion;
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


    //Restricciones
    public function postRestricciones()
    {
        $CRUD = new CRUD('App\Models\EntidadRestriccion');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postRestriccionesUpdate()
    {
        $Restricciones = request()->Restricciones;
        foreach ($Restricciones as $R) {
            if(!array_key_exists('changed', $R)) continue;
            if(!$R['changed']) continue;
            $DaRest = EntidadRestriccion::where('id', $R['id'])->first();
            $DaRest->fillit($R);
            $DaRest->save();
        }
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

    public function postGridsFiltrosUpdate()
    {
        $Filtros = request()->Filtros;
        foreach ($Filtros as $F) {
            if(!array_key_exists('changed', $F)) continue;
            if(!$F['changed']) continue;
            $DaFilter = \App\Models\EntidadGridFiltro::where('id', $F['id'])->first();
            $DaFilter->fillit($F);
            $DaFilter->save();
        }
    }

    //Grids - Filtros
    public function postGridsFiltros()
    {
        $CRUD = new CRUD('App\Models\EntidadGridFiltro');
        return $CRUD->call(request()->fn, request()->ops);
    }


    public function postGridsGetData()
    {
        $grid_id = request()->grid_id;
        $Grid = EntidadGrid::where('id', $grid_id)
                ->with(['columnas', 'filtros','entidad', 'entidad.restricciones'])->first();

        $RowsLimit = $Grid->entidad->max_rows ?: 100;

        $q = CamposHelper::getBaseQuery($Grid->entidad)->limit($RowsLimit);

        CamposHelper::addRestric($q, $Grid->entidad->restricciones, "t0");

        $entidades_ids = [];
        $tablas = [];
        $tablas_consec = 0;

        //Columna Guia
        $col_guia = new \App\Models\EntidadGridColumna([
            'grid_id'  => $grid_id,
            'Indice'   => -1,
            'Ruta'     => [$Grid->entidad_id],
            'Llaves'   => [null],
            'Visible'  => false,
            'campo_id' => $Grid->entidad->campo_llaveprim,
        ]);
        $Grid->columnas->prepend($col_guia);

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
        
        $DaCampos = []; $header_index = 0;
        foreach ($Grid->columnas as $C) {
            $Campo = CamposHelper::getElm($Campos,  $C['campo_id']);
            $Col = $Campo->getColName($C['tabla_consec']);
            $Alias = $Campo->Alias ?: $Campo->getColName('');

            $q = $q->addSelect([DB::raw("$Col AS \"$Alias\"")]);
            $DaCampos[] = $Campo;
            $C['campo'] = $Campo;
            $C['header'] = $C->Cabecera ?: $Campo->Alias ?: $Campo->getColName('');
            $C['header_numeric'] = in_array($Campo->Tipo, ['Entero','Decimal','Dinero']);
            $C['header_index'] = $header_index; $header_index++;
        };

        //Filtrar
        $last_filter = null;
        foreach ($Grid->filtros as $F) {
            $Columna = CamposHelper::getElm($Grid->columnas,  $F['columna_id']);
            $F['columna'] = $Columna;
            $F['campo'] = $Columna['campo'];
            $F['filter_header'] = $Columna['header'];
            $F['filter_comparator'] = CamposHelper::getFilterComp($F, $Columna['campo']);
            $F['default'] = CamposHelper::getFilterVal($F, $Columna['campo']);
            $F['val'] = $F['default'];
            
            $F['filter_cont'] = ($F['filter_header'] == $last_filter);
            $last_filter = $F['filter_header'];


            CamposHelper::addRestric($q, [$F], $Columna['tabla_consec']);
        }

        //Ordenar
        if(!is_null($Grid->entidad->campo_orderby)){
            $CampoOrder = CamposHelper::getElm($Campos, $Grid->entidad->campo_orderby);
            $q = $q->orderBy($CampoOrder->getColName("t0"), $Grid->entidad->campo_orderbydir);
        };

        

        $Grid->sql = [ 'query' => $q->toSql(), 'bindings' => $q->getBindings() ];
        $Grid->data = CamposHelper::prepData($DaCampos, $q->get());

        //Prep Filtros
        foreach ($Grid->filtros as $F) {
            if(in_array($F->Comparador,['lista','radios'])){
                $Ops = $Grid->data->pluck($F['columna']['header_index'])->unique()->sort()->values();
                $F['options'] = $Ops;
            };
        }

        return compact('Grid');
    }

    public function getGrid()
    {
        return $this->postGridsGetData();
    }

}
