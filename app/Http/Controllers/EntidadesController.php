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
        $Entidad = Entidad::where('id', $entidad_id)->first();
        $q       = GridHelper::getQ($Entidad, true, \PDO::FETCH_ASSOC)->limit($search_elms);

        $campos = [ $Entidad['campo_llaveprim'] ];
        for ($i=1; $i <= 5; $i++) { $campos[] = $Entidad->config['campo_desc'.$i]; };

        foreach ($campos as $k => $campo_id) {
            if(!is_null($campo_id)){
                $Campo = EntidadCampo::where('id', $campo_id)->first();
                $columna_name = DB::raw(CamposHelper::getColName('t0', $Campo['Columna']));
                
                $q->addSelect(DB::raw("$columna_name AS C$k"));
                $q->orWhere($columna_name, 'like', "%".strtoupper($searchText)."%");
            };
        };

        //return $Entidad;
        $res = collect($q->get())->transform(function($row){
            return  collect($row)->transform(function($D){
                return utf8_encode(trim($D));
            });
        });

        return $res;
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
        GridHelper::getData($Grid, $q);

        return compact('Grid');
    }

    public function postGridsReloadData()
    {
        $DaGrid = request('Grid');
        $Grid    = GridHelper::getGrid($DaGrid['id']);
        $q       = GridHelper::getQ($Grid->entidad);

        foreach ($DaGrid['columnas'] as $C) { $q->addSelect([DB::raw($C['select'])]); };
        foreach ($DaGrid['uniones']  as $U) { $q->leftJoin($U[0],$U[1],$U[2],$U[3]); };
        GridHelper::addRestric($q, $DaGrid['filtros']);

        GridHelper::getData($Grid, $q, true);
        return $Grid;
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
        $editor_id = request('editor_id');
        return EntidadEditor::with(['campos','campos.campo','campos.campo.entidadext'])->where('id', $editor_id)->first();
    }



    //Cargadores
    public function postCargadores()
    {
        $CRUD = new CRUD('App\Models\EntidadCargador');
        return $CRUD->call(request()->fn, request()->ops);
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

        $registros = \Excel::load($file, function($reader) use ($Cargador){
            $reader->noHeading = !$Cargador->Config['with_headers'];
        })->get();

        $load_data = [];

        foreach ($registros as $kR => $R) {
            foreach ($Cargador->entidad->campos as $kC => $C) {
                $C_Conf = $Cargador->Config['campos'][$C['id']];
                
                     if($C_Conf['tipo_valor'] == 'Sin Valor'){ $val = null; }
                else if($C_Conf['tipo_valor'] == 'Columna'  ){ $val = $R[$C_Conf['Defecto'] - 1]; };

                $load_data[$kR][$kC] = $val;
            }
        }
        
        //return $load_data;
        return ['entidad' => $Cargador->entidad, 'load_data' => $load_data];
    }

}
