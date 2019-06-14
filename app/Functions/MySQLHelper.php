<?php 

namespace App\Functions;

class MySQLHelper
{
	public static function getColumns($Conn, $Schema, $Table)
    {
        return $Conn->table('information_schema.COLUMNS')->where('TABLE_SCHEMA', $Schema)->where('TABLE_NAME', $Table)->limit(1000)->get();
    }

    public function standarizeColumns($newCampos, $Bdd, $Entidad, $Campos)
    {
        $Tipos = [
            'Texto'      => ['varchar','enum','set','char','varbinary'],
            'TextoLargo' => ['text','longtext','blob','longblob','mediumtext'],
            'Entero'     => ['int','bigint','tinyint','smallint','double','float','mediumint','year'],
            'Decimal'    => ['decimal'],
            'Booleano'   => [],
            'Fecha'      => ['date'],
            'Hora'       => ['time'],
            'FechaHora'  => ['timestamp','datetime'],
        ];

        $ColumnasExistentes = collect($Campos)->pluck('Columna')->toArray();

        return collect($newCampos)->transform(function($R) use ($Tipos){

            $Tipo = 'Texto';
            foreach ($Tipos as $T => $Valores) {
                if(in_array($R['DATA_TYPE'], $Valores)){ $Tipo = $T; }
            };

            return [
                'Columna'   => ".".$R['COLUMN_NAME'],
                'Alias'     => $R['COLUMN_COMMENT'],
                'Defecto'   => ($R['COLUMN_DEFAULT'] == "NULL" ? null : $R['COLUMN_DEFAULT']),
                'Tipo'      => $Tipo,
                'obj' => $R,
            ];
        })->filter(function($R) use ($ColumnasExistentes){
            return !in_array($R['Columna'], $ColumnasExistentes);
        })->values();
    }
}