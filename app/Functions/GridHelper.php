<?php 

namespace App\Functions;
use Carbon\Carbon;
use DB;

use App\Models\Entidad;
use App\Models\EntidadCampo;
use App\Models\EntidadRestriccion;
use App\Models\EntidadGrid;
use App\Models\BDD;

use App\Functions\Helper as H;
use App\Functions\ConnHelper;
use App\Functions\CamposHelper;

class GridHelper
{

	public static function getTableName($Tabla, $SchemaDef)
    {
        if (strpos($Tabla, '.') !== false) {
            $ST = explode('.', $Tabla);
            $SchemaDef = $ST[0];
            $Tabla = $ST[1];
        }
        return [ $SchemaDef, $Tabla, "$SchemaDef.$Tabla" ];
	}


	public static function getGrid($grid_id, $with = ['columnas', 'filtros','entidad', 'entidad.restricciones'])
    {
        $Grid = EntidadGrid::where('id', $grid_id)->with($with)->first();
        $Grid->rowsLimit = $Grid->entidad->max_rows ?: 100;
        return $Grid;
    }

    public static function getQ($Entidad, $addRestric = true, $fetchMode = \PDO::FETCH_NUM)
    {
    	$Bdd  = BDD::where('id', $Entidad['bdd_id'])->first();
    	$SchemaTabla = self::getTableName($Entidad['Tabla'], $Bdd->Op3);
    	$Conn = ConnHelper::getConn($Bdd);
        $Conn->setFetchMode($fetchMode);

        $q = $Conn->table(DB::raw($SchemaTabla[2]." AS t0"));
        if($addRestric) self::addRestric($q, $Entidad->restricciones, "t0");
        return $q;
    }

    public static function getUniqueTable($Ruta, $Llaves)
    {
    	$tabla_id = "t";
        foreach ($Ruta as $k => $t) {
            if($k>0) $tabla_id .= "k".$Llaves[$k]."j";
            $tabla_id .= $t;
        };
        return $tabla_id;
    }

    public static function addGuideCol($Grid)
    {
    	//Columna Guia
        $col_guia = new \App\Models\EntidadGridColumna([
            'grid_id'  => $Grid->id,
            'Indice'   => 0,
            'Ruta'     => [$Grid->entidad_id],
            'Llaves'   => [null],
            'Visible'  => false,
            'campo_id' => $Grid->entidad->campo_llaveprim,
            'tabla_consec' => "t0",
        ]);
        $Grid->columnas->prepend($col_guia);
    }

    public static function addCols($Grid, $q)
    {
    	self::addGuideCol($Grid);
    	$header_index = 0;
        foreach ($Grid->columnas as $C) {
            
            $Col = $C->campo->getColName($C['tabla_consec']);
            $C['select'] = "$Col AS c$header_index";
            $C['header_numeric'] = in_array($C->campo->Tipo, ['Entero','Decimal','Dinero']);
            $C['header_index'] = $header_index; $header_index++;
            $q->addSelect([DB::raw($C['select'])]);
        };
    }


    public static function calcJoins($Grid)
    {
    	$entidades_ids  = [];
        $tablas         = [];
        $tablas_consec  = 0;

        foreach ($Grid->columnas as $C) {
            for ($i=0; $i < count($C->Ruta); $i++) { 
                $tabla_ruta = array_slice($C->Ruta,0,($i+1));
                $tabla_id = self::getUniqueTable($tabla_ruta, $C->Llaves);

                if(!array_key_exists($tabla_id, $tablas)){
                    $entidad_destino = array_slice($tabla_ruta,-1,1)[0];
                    $entidades_ids[] = $entidad_destino;

                    $tablas[$tabla_id] = [
                        'id'              => $tabla_id,
                        'origen_id'       => self::getUniqueTable(array_slice($C->Ruta,0,($i)), $C->Llaves),
                        'nivel'           => count($tabla_ruta),
                        'entidad_origen'  => array_slice($tabla_ruta,-2,1)[0],
                        'llave_id'        => $C->Llaves[$i],
                        'entidad_destino' => $entidad_destino,
                        'consec'          => $tablas_consec++,
                    ];
                }
            };
            $C['tabla_consec'] = "t".$tablas[self::getUniqueTable($C->Ruta, $C->Llaves)]['consec'];
        };

        $entidades_ids = array_unique($entidades_ids);
        $Grid->tablas = compact('entidades_ids', 'tablas');
    }


    public static function addJoins($Grid, $q)
    {
    	$Entidades = Entidad::whereIn('id', 			 $Grid->tablas['entidades_ids'])->get()->keyBy('id');
    	$Campos    = EntidadCampo::whereIn('entidad_id', $Grid->tablas['entidades_ids'])->get()->keyBy('id');

        $uniones = [];
        foreach ($Grid->tablas['tablas'] as $tb) {
            if($tb['nivel'] == 1) continue;

            $EntidadOrigen  = $Entidades[$tb['entidad_origen' ]];
            $EntidadDestino = $Entidades[$tb['entidad_destino']];

            $CampoOrigen  = $Campos[$tb['llave_id']];
            $CampoDestino = $Campos[$EntidadDestino->campo_llaveprim];
            
            $union = [
            	DB::raw("{$EntidadDestino->getTableName()[2]} AS t{$tb['consec']}"),
            	DB::raw($CampoOrigen->getColName("t{$Grid->tablas['tablas'][$tb['origen_id']]['consec']}")), '=', 
            	DB::raw($CampoDestino->getColName("t{$tb['consec']}"))
            ];
            $q->leftJoin($union[0],$union[1],$union[2],$union[3]);
            $uniones[] = $union; 
        };
        $Grid->uniones = $uniones;
    }



    public static function addRestric($q, $restricciones, $t = false)
    {
        foreach ($restricciones as $R) {
            if($t) $R['columna_name'] = CamposHelper::getColName($t, $R['campo']['Columna']);
            $Valor = CamposHelper::prepFilterVal($R['val'], $R['campo']);
            self::addRestricRun($q, $R['columna_name'], $R['Comparador'], $Valor);
        }
    }

    public static function addRestricRun($q, $columna_name, $Comparador, $Valor)
    {
        $columna_name = DB::raw($columna_name);
        if($Comparador == 'nulo'){                          return $q->whereNull($columna_name);                 };
        if($Comparador == 'no_nulo'){                       return $q->whereNotNull($columna_name);              };
        if(in_array($Comparador, ['=','<=','<','>','>='])){ return $q->where($columna_name,$Comparador,$Valor);  };
        if($Comparador == 'like'){                          return $q->where($columna_name, 'like', "%$Valor%"); };
        if($Comparador == 'like_'){                         return $q->where($columna_name, 'like', "$Valor%");  };
        if($Comparador == '_like'){                         return $q->where($columna_name, 'like', "%$Valor");  };
        if($Comparador == 'lista' AND !empty($Valor)){      return $q->whereIn($columna_name, $Valor);           };
    }

    public static function addFilters($Filtros, $Grid, $q)
    {
        foreach ($Filtros as $F) {
            $Columna = H::getElm($Grid->columnas,  $F['columna_id']);
            $F['columna'] = $Columna;
            $F['filter_header'] = $Columna['column_title'];
            $F['filter_comparator'] = CamposHelper::getFilterComp($F, $Columna['campo']);

            self::addRestric($q, [$F], $Columna['tabla_consec']);
        }
    }

    public static function addOrders($Grid, $q)
    {
    	if(!is_null($Grid->entidad->campo_orderby)){
            //$CampoOrder = H::getElm($Campos, $Grid->entidad->campo_orderby);
            //$q->orderBy($CampoOrder->getColName("t0"), $Grid->entidad->campo_orderbydir);
        };
    }

    public static function getData($Grid, $q, $prepOpts = false, $limit = true)
    {
    	set_time_limit(10 * 60);

        //if($limit) $q->limit($Grid->rowsLimit);
        $Grid->sql = [ 'query' => $q->toSql(), 'bindings' => $q->getBindings() ];
        $Data = CamposHelper::prepData($Grid->columnas, $q->get());

        //Prep Filtros Opts
        if($prepOpts){
        	foreach ($Grid->filtros as $F) {
	            if(in_array($F->Comparador,['lista','radios'])){
	                $Ops = $Data->pluck($F['columna']['header_index'])->unique()->sort()->values();
	                $F['options'] = $Ops;
	            };
	        }
        };

        return $Data;
    }

    public static function getGrouper($Agrupador  = 'count', $Col)
    {
        if($Agrupador == 'count')           return "COUNT($Col)";
        if($Agrupador == 'countdistinct')   return "COUNT( DISTINCT $Col )";
        if($Agrupador == 'sum')             return "SUM($Col)";
        if($Agrupador == 'avg')             return "AVG($Col)";
        if($Agrupador == 'min')             return "MIN($Col)";
        if($Agrupador == 'max')             return "MAX($Col)";
    }

    public static function getGroupedData($Grid, $q, $Groupers, $Agrupadores)
    {
        foreach ($Groupers as $G) {
            $q->addSelect(DB::raw($G));
        };

        foreach ($Agrupadores as $A) {
            $Calc = self::getGrouper($A[1], $A[0]);
            $q->addSelect(DB::raw($Calc));
        };

        $q->groupBy($Groupers);
    }





}