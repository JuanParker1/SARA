<?php 

namespace App\Functions;

class PostgreSQLHelper
{
	public function getTableRoute($Bdd, $Table)
    {
        $tableRoute = [
            'Database' => $Bdd['Op3'],
            'Schema'   => $Bdd['Op4']
        ];

        $TableArr = explode('.', $Table);
        $tableRoute['Table'] = array_pop($TableArr);
        if(!empty($TableArr)) $tableRoute['Schema']   = array_pop($TableArr);
        if(!empty($TableArr)) $tableRoute['Database'] = array_pop($TableArr);

        $tableRoute['FullRoute'] = "{$tableRoute['Database']}.{$tableRoute['Schema']}.{$tableRoute['Table']}";

        return $tableRoute;
    }

    public function getColumns($Conn, $tableRoute)
    {
        $Columns = $Conn->table('information_schema.columns')
            ->where('table_catalog', $tableRoute['Database'])
            ->where('table_schema',  $tableRoute['Schema'])
            ->where('table_name',    $tableRoute['Table'])
            ->orderBy('ordinal_position')->limit(1000)->get();

        return collect($Columns)->transform(function($row){
            return array_change_key_case($row, CASE_UPPER);
        });
    }

    public function standarizeColumns($newCampos, $Bdd, $Entidad, $Campos, $tiposCampo)
    {
        $Tipos = [
            'Texto'      => ['varchar', 'character varying', 'char', 'character'],
            'TextoLargo' => ['text'],
            'Entero'     => ['integer', 'serial', 'smallint', 'bigint'],
            'Decimal'    => ['decimal', 'numeric', 'real'],
            'Booleano'   => ['boolean'],
            'Fecha'      => ['date'],
            'Hora'       => ['time'],
            'FechaHora'  => ['timestamp'],
        ];

        $ColumnasExistentes = collect($Campos)->pluck('Columna')->toArray();

        return collect($newCampos)->transform(function($R) use ($Tipos, $tiposCampo, $Entidad){

            $Tipo = 'Texto';
            foreach ($Tipos as $T => $Valores) {
                if(in_array($R['data_type'], $Valores)){ $Tipo = $T; }
            };

            $C = [
                'entidad_id'=> $Entidad['id'],
                'Columna'   => ".".$R['column_name'],
                'Alias'     => null,
                'Defecto'   => ($R['column_default'] == "NULL" ? null : $R['column_default']),
                'Tipo'      => $Tipo,
                'Requerido' => ($R['is_nullable'] == 'NO'),
                'Visible'   => true,
                'Unico'     => ($R['is_identity'] == 'YES'),
                'Editable'  => true,
                'Buscable'  => 0,
                //'obj' => $R
            ];

            return array_merge($C, $tiposCampo[$Tipo]['Defaults']);

        })->filter(function($R) use ($ColumnasExistentes){
            return !in_array($R['Columna'], $ColumnasExistentes);
        })->values();
    }
}