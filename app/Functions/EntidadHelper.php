<?php 

namespace App\Functions;

use App\Functions\GridHelper;
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
                if($multiple){
                    $q->orWhere($columna_name, 'like', "%".strtoupper($searchText)."%");
                }else{
                    if($k == 0) $q->where($columna_name, $searchText);
                };
            };
        };

        //return $Entidad;
        $res = collect($q->get())->transform(function($row){
            return  collect($row)->transform(function($D){
                return utf8_encode(trim($D));
            });
        });

        return $multiple ? $res : $res[0];
    }

}