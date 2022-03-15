<?php 

namespace App\Functions;

class DB2Helper
{
	public function getTableRoute($Bdd, $Table)
    {
        $tableRoute = [
            'Database' => $Bdd['Op3']
        ];

        $TableArr = explode('.', $Table);
        $tableRoute['Table'] = array_pop($TableArr);
        if(!empty($TableArr)) $tableRoute['Database'] = array_pop($TableArr);

        $tableRoute['FullRoute'] = "{$tableRoute['Database']}.{$tableRoute['Table']}";

        return $tableRoute;
    }

    public function getColumns($Conn, $tableRoute)
	{
		$Columns = $Conn->table('QSYS2.SYSCOLUMNS')
            ->where('TABLE_SCHEMA', $tableRoute['Database'])
            ->where('TABLE_NAME',   $tableRoute['Table'])
            ->limit(1000)->get();
        return collect($Columns)->transform(function($row){
            return array_map('utf8_encode', $row);
        });
	}

	public function standarizeColumns($newCampos, $Bdd, $Entidad, $Campos)
    {
        $Tipos = [
            'Texto'      => ['CHAR', 'VARCHAR'],
            'TextoLargo' => ['CLOB', 'BLOB', 'GRAPHIC'], 
            'Entero'     => ['INTEGER', 'NUMERIC', 'FLOAT', 'BIGINT','SMALLINT'],
            'Decimal'    => ['DECIMAL', ],
            'Booleano'   => [],
            'Fecha'      => ['DATE'],
            'Hora'       => ['TIME'],
            'FechaHora'  => ['TIMESTAMP', 'DATETIME'],  
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

    public static function getDataType($C)
    {
        $TranslateType = [ //Translation, length
            'CHAR'       => ['CHAR',   1],
            'CHARACTER'  => ['CHARACTER',   1],
            'VARCHAR'    => ['VARCHAR',     1],
            'DECIMAL'    => ['DECIMAL',     2],
            'NUMERIC'    => ['NUMERIC',     2],
            'REAL'       => ['REAL',        2],
            'TIMESTMP'   => ['TIMESTAMP',   0],
        ];

        $Type = array_key_exists($C['DATA_TYPE'], $TranslateType) ? $TranslateType[$C['DATA_TYPE']][0] : trim($C['DATA_TYPE']);
        $Len  = array_key_exists($C['DATA_TYPE'], $TranslateType) ? $TranslateType[$C['DATA_TYPE']][1] : 0;

        if($Len > 0){
            $Type .= "(";
            if($Len >= 1) $Type .= $C['LENGTH'];
            if($Len >= 2) $Type .= ",".($C['NUMERIC_SCALE']);
            $Type .= ")";
        };

        return $Type;
    }

    public static function getCreateStatement($Conn, $tableRoute, $Entidad)
    {
        $Refs = [
            'Vista' => ['QSYS2.SYSVIEWS',  'VIEW_DEFINER' ],
            'Tabla' => ['QSYS2.SYSTABLES', 'TABLE_DEFINER']
        ];

        $Reg = $Conn->table($Refs[$Entidad['Tipo']][0])
            ->where('TABLE_SCHEMA', $tableRoute['Database'])
            ->where('TABLE_NAME',   $tableRoute['Table'])
            ->first();

        $Res = [
            'definition' => null,
            'definer'    => null
        ];

        if(empty($Reg)) abort(512, "{$Entidad['Tipo']}: {$tableRoute['FullRoute']} no encontrada");

        $Res['definer']    = $Reg[$Refs[$Entidad['Tipo']][1]];

        if($Entidad['Tipo'] == 'Vista'){

            $Res['definition'] = "CREATE OR REPLACE VIEW {$tableRoute['FullRoute']} AS ".$Reg['VIEW_DEFINITION'].";\n";
            
        }else if($Entidad['Tipo'] == 'Tabla'){
            $sql = "CREATE TABLE {$tableRoute['FullRoute']} (";

            $Columns = (new self)->getColumns($Conn, $tableRoute);
            $NumColumns = $Columns->count();
            $primaryKey = false;
            $maxLen = 1;

            foreach ($Columns as $kC => $C) {
                $maxLen = max($maxLen, strlen(trim($C['COLUMN_NAME'])));
            }
            foreach ($Columns as $kC => $C) {
                $data_type = self::getDataType($C);
                $column = str_pad(trim($C['COLUMN_NAME']), $maxLen);
                $sql .= "\n\t$column $data_type";
                if($C['IS_NULLABLE'] !== "Y") $sql .= " NOT NULL";
                if($C['IS_IDENTITY'] == 'YES' AND !$primaryKey){
                    $primaryKey = $C['COLUMN_NAME'];
                    $sql .= " GENERATED ALWAYS AS IDENTITY (START WITH 1 INCREMENT BY 1)";
                }
                if($kC <> ($NumColumns-1)) $sql .= ",";
            }

            if($primaryKey){
                $sql .= ",\n\n\tPRIMARY KEY($primaryKey)";
            };

            $sql .= "\n);\n";

            
            $Res['columns'] = $Columns;
            
            $Res['definition'] = $sql;
        };

        return $Res;

        //[0]['VIEW_DEFINITION'];
    }
}