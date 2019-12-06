<?php 

namespace App\Functions;

class MySQLHelper
{
	public function getColumns($Conn, $Schema, $Table)
    {
        return $Conn->table('information_schema.COLUMNS')->where('TABLE_SCHEMA', $Schema)->where('TABLE_NAME', $Table)->limit(1000)->get();
    }

    public function standarizeColumns($newCampos, $Bdd, $Entidad, $Campos, $tiposCampo)
    {
        $Tipos = [
            'Texto'      => ['varchar','enum','set','char','varbinary'],
            'TextoLargo' => ['text','longtext','blob','longblob','mediumtext'],
            'Entero'     => ['int','bigint','smallint','double','float','mediumint','year'],
            'Decimal'    => ['decimal'],
            'Booleano'   => ['tinyint'],
            'Fecha'      => ['date'],
            'Hora'       => ['time'],
            'FechaHora'  => ['timestamp','datetime'],
        ];

        $ColumnasExistentes = collect($Campos)->pluck('Columna')->toArray();

        return collect($newCampos)->transform(function($R) use ($Tipos, $tiposCampo, $Entidad){

            $Tipo = 'Texto';
            foreach ($Tipos as $T => $Valores) {
                if(in_array($R['DATA_TYPE'], $Valores)){ $Tipo = $T; }
            };

            $C = [
                'entidad_id'=> $Entidad['id'],
                'Columna'   => ".".$R['COLUMN_NAME'],
                'Alias'     => $R['COLUMN_COMMENT'],
                'Defecto'   => ($R['COLUMN_DEFAULT'] == "NULL" ? null : $R['COLUMN_DEFAULT']),
                'Tipo'      => $Tipo,
                'Requerido' => ($R['IS_NULLABLE'] == 'NO'),
                'Visible'   => true,
                'Unico'     => ($R['COLUMN_KEY'] == 'PRI'),
                'Editable'  => true,
                'Buscable'  => 0,
                //'obj' => $R
            ];

            /*
                'entidad_id'=> $Entidad['id'],
                'Columna'   => ".".$R['COLUMN_NAME'],
                'Alias'     => $R['COLUMN_TEXT'],
                'Defecto'   => ($R['COLUMN_DEFAULT'] == "NULL" ? null : $R['COLUMN_DEFAULT']),
                'Tipo'      => $Tipo,
                'Requerido' => ($R['IS_NULLABLE']  == 'N' ? true : false),
                'Visible'   => ($R['HIDDEN']       == 'N' ? true : false),
                'Editable'  => ($R['IS_UPDATABLE'] == 'Y' ? true : false),
                'Buscable'  => 0,
             */

            return array_merge($C, $tiposCampo[$Tipo]['Defaults']);

        })->filter(function($R) use ($ColumnasExistentes){
            return !in_array($R['Columna'], $ColumnasExistentes);
        })->values();
    }
}