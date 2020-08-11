<?php 

namespace App\Functions;

use App\Functions\ConnHelper;
use App\Functions\GridHelper;
use App\Models\BDD;
use App\Models\Entidad;
use App\Models\EntidadCampo;
use DB;

class EntidadHelper
{
	public static function searchElms($entidad_id, $searchText, $limit = 1, $multiple = false)
    {
        $Entidad = Entidad::where('id', $entidad_id)->first();
        $q       = GridHelper::getQ($Entidad, $multiple, \PDO::FETCH_ASSOC)->limit($limit);

        $campos = [ $Entidad['campo_llaveprim'] ];
        for ($i=1; $i <= 5; $i++) { $campos[] = $Entidad->config['campo_desc'.$i]; };

        foreach ($campos as $k => $campo_id) {
            if(!is_null($campo_id)){
                $Campo = EntidadCampo::where('id', $campo_id)->first();
                $columna_name = DB::raw(CamposHelper::getColName('t0', $Campo['Columna']));
                $q->addSelect(DB::raw("$columna_name AS C$k"));
            };
        };

        $q->where(function($query) use ($campos, $searchText, $multiple){

            foreach ($campos as $k => $campo_id) {
                if(!is_null($campo_id)){
                    $Campo = EntidadCampo::where('id', $campo_id)->first();
                    $columna_name = DB::raw(CamposHelper::getColName('t0', $Campo['Columna']));
                    if($multiple){
                            $query->orWhere(DB::raw("UPPER( $columna_name )"), 'like', "%".strtoupper($searchText)."%");
                    }else{
                        if($k == 0) $query->where($columna_name, '=', DB::raw("'$searchText'") );
                    };
                };
            };

        });

        //dd([$q->toSql(), $q->getBindings()]);

        //return $Entidad;
        $res = collect($q->get())->transform(function($row){
            return  collect($row)->transform(function($D){
                return utf8_encode(trim($D));
            });
        });

        if($multiple) return $res;

        if($res) return $res[0];
        return null;
    }

    public static function getTableConn($Entidad)
    {
        $Bdd  = BDD::where('id', $Entidad['bdd_id'])->first();
        $SchemaTabla = GridHelper::getTableName($Entidad['Tabla'], $Bdd->Op3);
        $Conn = ConnHelper::getConn($Bdd);
        //dd($Conn->getPdo());
        return $Conn->table($SchemaTabla[2]);
    }

    public static function insertRows($Entidad, $rows, $batchSize = 0)
    {
        $Tb = self::getTableConn($Entidad);

        if($batchSize == 0){
            $Tb->insert($rows->toArray());
        }else{
            $data_chunk = $rows->chunk($batchSize);
            foreach ($data_chunk as $chunk) {
                $Tb->insert($chunk->toArray());
            };
        }        
    }

    public static function updateRow($Entidad, $primary_key, $Obj)
    {
        $Tb = self::getTableConn($Entidad);
        $Row = $Tb->where($primary_key[0], DB::raw("'{$primary_key[1]}'") );
        if(empty($Row->get())){ // Crear
            self::insertRows($Entidad, $Obj);
        }else{
            $r = $Row->update($Obj->toArray());
        };
    }



}