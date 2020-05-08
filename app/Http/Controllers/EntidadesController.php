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
use App\Models\EntidadEditor;
use App\Models\EntidadCargador;
use App\Models\BDD;

use App\Functions\Helper as H;
use App\Functions\EntidadHelper;
use App\Functions\GridHelper;
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

    public function postSearch()
    {
        extract(request()->all());
        return EntidadHelper::searchElms($entidad_id, $searchText, $search_elms, true);
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

        foreach ($newCampos as $nC) {
            unset($nC['changed']);
            $Campo = EntidadCampo::create($nC);
            $Campo->save();
        };
        //EntidadCampo::insert($newCampos);
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

    public function postGridsGet()
    {
        return EntidadGrid::with(['entidad'])->get()->transform(function($G){
            $G->TituloComp = $G->entidad->Nombre .' - '. $G->Titulo;
            return $G;
        })->sortBy('TituloComp');
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
            $DaFilter->prepSave($F);
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
        $Grid    = GridHelper::getGrid($grid_id);
        $q       = GridHelper::getQ($Grid->entidad);

        GridHelper::calcJoins($Grid);
        GridHelper::addJoins($Grid, $q);
        GridHelper::addCols($Grid, $q);
        GridHelper::addFilters($Grid->filtros, $Grid, $q);
        GridHelper::addOrders($Grid, $q);
        $Data = GridHelper::getData($Grid, $q, true);

        return compact('Grid', 'Data');
    }

    public function postGridsReloadData()
    {
        $DaGrid = request('Grid');
        $Grid    = GridHelper::getGrid($DaGrid['id']);
        $q       = GridHelper::getQ($Grid->entidad);

        GridHelper::addGuideCol($Grid);
        foreach ($DaGrid['columnas'] as $C) { $q->addSelect([DB::raw($C['select'])]); };
        foreach ($DaGrid['uniones']  as $U) { $q->leftJoin(DB::raw($U[0]), DB::raw($U[1]),$U[2],DB::raw($U[3])); };
        GridHelper::addRestric($q, $DaGrid['filtros']);

        $Data = GridHelper::getData($Grid, $q, true);
        return compact('Grid', 'Data');
    }

    public function getGrid()
    {
        return $this->postGridsGetData();
    }



    //Editores
    public function postEditores()
    {
        $CRUD = new CRUD('App\Models\EntidadEditor');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postEditoresCampos()
    {
        $CRUD = new CRUD('App\Models\EntidadEditorCampo');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postEditoresSearch()
    {
        $searchText = request('searchText');
        return EntidadEditor::select(['id','Titulo AS display'])->where('Titulo', 'LIKE', "%{$searchText}%")->get();
    }

    public function postEditorGet()
    {
        extract(request()->all()); //$editor_id, $Obj, $Config
        $Editor = EntidadEditor::with(['entidad','campos','campos.campo','campos.campo.entidadext'])->where('id', $editor_id)->first();
        if(isset($Config)) $Editor->prepFields($Config, $Obj);
        return $Editor;
    }

    public function postEditorSave()
    {
        extract(request()->all()); //$Editor, $Config

        $Obj = [];
        $campo_llaveprim = $Editor['entidad']['campo_llaveprim'];
        $llaveprim_col = null;
        $llaveprim_val = null;

        if($Config['modo'] != 'Crear' AND !is_null($Editor['primary_key_val']) ){ 
            $llaveprim_val = $Editor['primary_key_val'];
            $CampoLlavePrim = EntidadCampo::where('id', $Editor['entidad']['campo_llaveprim'])->first();
            $llaveprim_col = CamposHelper::getColName("", $CampoLlavePrim['Columna']);
        };

        foreach ($Editor['campos'] as $F) {
            if($F['campo_id'] == $campo_llaveprim) continue;
            $Columna = CamposHelper::getColName("",$F['campo']['Columna']);
            $Obj[$Columna] = CamposHelper::prepDatoUp($F['campo'], $F['val']);
        };

        if(is_null($llaveprim_val)){ //Nuevo elemento
            EntidadHelper::insertRows($Editor['entidad'], collect([$Obj]));
        }else{
            EntidadHelper::updateRow($Editor['entidad'], [ $llaveprim_col, $llaveprim_val ], collect($Obj));
        };

        return compact('Obj', 'llaveprim_val', 'Editor', 'Config');
    }



    //Cargadores
    public function postCargadores()
    {
        $CRUD = new CRUD('App\Models\EntidadCargador');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postCargadoresGet()
    {
        return EntidadCargador::get();
    }

    public function postCargadorGet()
    {
        $cargador_id = request('cargador_id');
        $Cargador    = EntidadCargador::where('id', $cargador_id)->first();
        return $Cargador;
    }

    public function postCargadorUpload()
    {
        $file = request()->file('file');
        $Cargador    = EntidadCargador::where('id', request('cargador_id'))->with(['entidad', 'entidad.campos'])->first();


        \Excel::setDelimiter(';');

        $registros = \Excel::load($file, function($reader) use ($Cargador){
            $reader->noHeading = !$Cargador->Config['with_headers'];
        })->get();

        //return $registros;

        $load_data = [];

        foreach ($registros as $kR => $R) {
            foreach ($Cargador->entidad->campos as $kC => $C) {
                $C_Conf = $Cargador->Config['campos'][$C['id']];
                

                     if($C_Conf['tipo_valor'] == 'Sin Valor'){ $val = null; }
                else if($C_Conf['tipo_valor'] == 'Columna'  ){
                    $Indice = $C_Conf['Defecto'] - 1;
                    $val = $R->has($Indice) ? $R[$Indice] : null; 
                };

                $Cargador->entidad->campos[$kC]['tipo_valor'] = $C_Conf['tipo_valor'];
                $load_data[$kR][$kC] = $val;
            }
        }
        
        //return $load_data;
        return ['entidad' => $Cargador->entidad, 'load_data' => $load_data];
    }

    public function postCargadorInsert()
    {
        extract(request()->all());

        $load_data = collect($load_data)->transform(function($R) use ($Cargador, $Entidad){
            $row = [];
            foreach ($Entidad['campos'] as $C) {
                if($C['tipo_valor'] == 'Sin Valor') continue;
                $ConfigCampo = $Cargador['Config']['campos'][$C['id']];
                if(array_key_exists('formato', $ConfigCampo)) $C['formato'] = $ConfigCampo['formato'];
                if($C['tipo_valor'] == 'Columna')             $Val = CamposHelper::prepDatoIns($C, $R[$C['Indice']]);
                if($C['tipo_valor'] == 'Variable de Sistema') $Val = CamposHelper::getSysVariable($ConfigCampo['Defecto']);

                //if(empty($Val)) $Val = null;
                $row[CamposHelper::getColName("",$C['Columna'])] = $Val;
            };

            return $row;
        });

        EntidadHelper::insertRows($Entidad, $load_data, 50);
    }

}
