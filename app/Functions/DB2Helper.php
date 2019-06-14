<?php 

namespace App\Functions;

class DB2Helper
{
	public static function getColumns($Conn, $Schema, $Table)
	{
		$Columns = $Conn->table('QSYS2.SYSCOLUMNS')->where('TABLE_SCHEMA', $Schema)->where('TABLE_NAME', $Table)->limit(1000)->get();
        return collect($Columns)->transform(function($row){
            return array_map('utf8_encode', $row);
        });
	}

	public static function standarizeColumns($newCampos, $Bdd, $Entidad, $Campos)
    {
        $Tipos = [
            'Texto'      => ['CHAR', 'VARCHAR'],
            'TextoLargo' => ['CLOB', 'BLOB', 'GRAPHIC'], 
            'Entero'     => ['INTEGER', 'NUMERIC', 'FLOAT', 'BIGINT','SMALLINT'],
            'Decimal'    => ['DECIMAL', ],
            'Booleano'   => [],
            'Fecha'      => ['DATE'],
            'Hora'       => ['TIME'],
            'FechaHora'  => ['TIMESTMP'],  
        ];

        $ColumnasExistentes = collect($Campos)->pluck('Columna')->toArray();

        return collect($newCampos)->transform(function($R) use ($Tipos, $Entidad){

            $Tipo = 'Texto';
            foreach ($Tipos as $T => $Valores) {
                if(in_array($R['DATA_TYPE'], $Valores)){ $Tipo = $T; }
            };

            $Op1 = null; $Op2 = null; $Op3 = null; $Op4 = null; $Op5 = null;

            if(in_array($Tipo, ['Entero', 'Decimal'])){
                     if(intval($R['LENGTH']) == 8){ $Tipo = 'Fecha'; $Op4 = 'Ymd'; }
                else if(intval($R['LENGTH']) == 6){ $Tipo = 'Hora';  $Op4 = 'His'; }
                else{   $Op1 = 0; }
            };

            if(in_array($Tipo, ['Texto', 'TextoLargo'])) $Op2 = intval($R['LENGTH']);
            if(in_array($Tipo, ['Entero','Decimal']))    $Op2 = pow(10,intval($R['LENGTH'])) - 1;

            return [
                'entidad_id'=> $Entidad['id'],
                'Columna'   => ".".$R['COLUMN_NAME'],
                'Alias'     => $R['COLUMN_TEXT'],
                'Defecto'   => ($R['COLUMN_DEFAULT'] == "NULL" ? null : $R['COLUMN_DEFAULT']),
                'Tipo'      => $Tipo,
                'Requerido' => ($R['IS_NULLABLE']  == 'N' ? true : false),
                'Visible'   => ($R['HIDDEN']       == 'N' ? true : false),
                'Editable'  => ($R['IS_UPDATABLE'] == 'Y' ? true : false),
                'Buscable'  => 0,
                'Op1'       => $Op1, 'Op2' => $Op2, 'Op3' => $Op3, 'Op4' => $Op4, 'Op5' => $Op5,
                //'obj' => $R,
            ];
        })->filter(function($R) use ($ColumnasExistentes){
            return !in_array($R['Columna'], $ColumnasExistentes);
        })->values();
    }
}