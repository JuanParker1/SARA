<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Functions\ConnHelper;
use App\Functions\Helper;
use App\Functions\CRUD;
use App\Http\Controllers\Integraciones\SOMA;
use App\Http\Controllers\Integraciones\RUAF;
use App\Http\Controllers\Integraciones\Enterprise;
use App\Http\Controllers\Integraciones\Ikono;
use App\Http\Controllers\Integraciones\Solgein;

class IntegracionesController extends Controller
{
    public function postCrud()
    {
        $CRUD = new CRUD('App\Models\Integracion');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postSoma()
    {
        return SOMA::download();
    }

    public function postSomaSend()
    {
        return SOMA::send();
    }

    public function postRuaf()
    {
        return RUAF::upload();
    }

    public function postEnterprise()
    {
        return Enterprise::upload();
    }

    public function postIkono()
    {
        return Ikono::upload();
    }


    public function postSolgein()
    {
        return Solgein::upload_valores();
    }

    public function postSolgeinComments()
    {
        return Solgein::upload_comments();
    }

    public function getSolgeinComentarios()
    {
        set_time_limit(5*60);
        $folder   = 'temp';
        $filename = 'Análisis 2020-09.xls';
        //request()->file('file')->move($folder, $filename);
        
        $BaseRegs = Helper::readTableFile($folder.'/'.$filename, [
            'col_ini' => 3, 'col_fin' => 12, 'row_ini' => 7, 'row_fin' => null,
            'headers' => ['Periodo','Nivel','Proceso','Persp','Objetivo', 'Indicador', 'Cumplio', 'Usuario', 'Fecha', 'Comentario']
        ]);

        $NamesList = collect([
            'jhonfredypatinoramirez' => 2133,
            'claudialuciaquinteroperez' => 2809,
            'lilianandreacastromiranda' => 1461,
            'luzadrianaloaizagomez' => 594,
            'monicajhoanacardona' => 738,
            'ximenasilva' => 3055,
            'beatrizcardonat' => 2061,
            'paolaandreaecheverri' => 892,
            'gloriamilenahernandezcifuentes' => 422, //Bonilla
        ]);

        $ProcessList = collect([
            'auditoriainterna' => 21,
            'controllegaladtvoygestionderiesgo' => 11,
            'subsidiofamiliar' => 26,
            'credito' => 28,
            'parqueaderocomfamiliar' => 18,
            'granja' => 123,
            'atencionintegralalaninez' => 38,
            'jornadasescolarescomplementarias' => 37,
            'consultaexternamedicayespecializada' => 89,
            'serviciotransfusional' => 66,
            'hemato-oncologiaambulatoria' => 110,
            'unidaddecardiologiainvasiva' => 112,
            'hospitalizacionginecobstetricia' => 57,
            'unidaddecuidadosintensivosadultos' => 63,
            'nefrologiahospitalaria' => 59,
            'mejoramientocontinuo' => 20,
            'defensoriadelusuario' => 20,
            'ipsodontologica' => 51,
            'administraciondeaportes' => 26,
            'programadeatencionaladiscapacidad' => 36
        ]);

        $Regs = [];
        $Usuarios = \App\Models\Usuario::get()->transform(function($U){
            $U['nombre_comp'] = Helper::prepValComp($U['Nombres']);
            return $U;
        })->keyBy('nombre_comp')->transform(function($U){ return $U['id']; });

        $Procesos = \App\Models\Proceso::get()->transform(function($U){
            $U['nombre_comp'] = Helper::prepValComp($U['Proceso']);
            return $U;
        })->keyBy('nombre_comp')->transform(function($U){ return $U['id']; });

        $Indicadores = \App\Models\Indicador::get([ 'id', 'Indicador', 'TipoDato', 'proceso_id' ])
        ->keyBy(function($I){
            return $I->proceso_id .'___'. strtolower(trim(str_replace(['s', ' Acum', '  '], '', $I->Indicador)));
        });

        //return $Procesos->all();
        $MissingNames = [];
        $MissingProcesos = [];
        $MissingInds = [];

        foreach ($BaseRegs as &$R) {
            if(is_null($R['Usuario'])) continue;

            //Buscar el Usuario
            $Nombre = Helper::prepValComp($R['Usuario']);
            $usuario_id = $NamesList->get($Nombre);
            if(!$usuario_id) $usuario_id =  $Usuarios->get($Nombre);

            if(!$usuario_id){
                $MissingNames[] = $Nombre;
            }

            //Buscar el proceso
            $Proceso = Helper::prepValComp($R['Proceso']);
            $proceso_id = $ProcessList->get($Proceso);
            if(!$proceso_id)  $proceso_id = $Procesos->get($Proceso);

            if(!$proceso_id){
                $MissingProcesos[] = $Proceso;
            }

            if(!$usuario_id) continue;
            if(!$proceso_id) continue;

            //Buscar el Indicador
            $indicador_uid = $proceso_id .'___'. strtolower(trim(str_replace(['s', ' Acum', '  '], '', $R['Indicador'])));
            $Indicador = $Indicadores->get($indicador_uid);

            if(!$Indicador){
                $MissingInds[] = $R['Proceso'] .' - '. $R['Indicador'];
                continue;
            }

            $R['nombre'] = $Nombre;
            $R['usuario_id'] = $usuario_id;
            $R['proceso_id'] = $proceso_id;
            $R['Indicador_sara'] = $Indicador;

            $R['Comentario'] = str_replace(
                ['¿', '_x000D_'],
                ["'", ''],
                $R['Comentario']
            );

            $UNIX_DATE = ($R['Fecha'] - 25569) * 86400;
            $R['created_at'] = gmdate("Y-m-d H:i:s", $UNIX_DATE);

            $Regs[] = $R;
        }

        if(!empty($MissingNames))    return collect($MissingNames)->unique()->toArray();
        if(!empty($MissingProcesos)) return collect($MissingProcesos)->unique()->toArray();
        //if(!empty($MissingInds)) return $MissingInds;

        foreach ($Regs as $R) {

            $DaComment = new \App\Models\Comentario([
                'Entidad' => 'Indicador', 'Entidad_id' => $R['Indicador_sara']['id'],
                'Grupo' => 'Comentario',
                'usuario_id' => $R['usuario_id'],
                'Comentario' => $R['Comentario'],
                'Op1' => $R['Periodo'],
                'created_at' => $R['created_at'],
                'updated_at' => $R['created_at']
            ]);
            $DaComment->save();
        }

        return count($Regs);
    }


    public function getComfamiliarSyncUsuarios()
    {
        set_time_limit(0);

        $Usuarios = \App\Models\Usuario::get();
        $images = 0;
        foreach ($Usuarios as $U) {
            if(!$U->Documento) continue;

            $url = "http://sec.comfamiliar.com/images/fotosEmpleados/{$U->Documento}.jpg";
            $savepath = "fs/frt54s/avatars/{$U->id}.jpg";

            if(!file_exists($savepath)){
                try {
                    $img = \Image::make($url);
                    $img->resize(120, null,    function ($constraint){ $constraint->aspectRatio(); });
                    $img->crop(120, 120, 0, 0);
                    $img->save($savepath);
                    $images++;
                }catch (\Exception $e){

                }
            }
        }

        return $images;
    }


    public static function prepPostgresqlDatatype($C)
    {
        $TranslateType = [ //Translation, length
            'varchar'    => ['varchar',           'CHARACTER_MAXIMUM_LENGTH', null],
            'enum'       => ['varchar',           'CHARACTER_MAXIMUM_LENGTH', null],
            'int'        => ['integer',           null, null],
            'tinyint'    => ['smallint',          null, null],
            'decimal'    => ['decimal',           'NUMERIC_PRECISION', 'NUMERIC_SCALE'],
            'date'       => ['date',              null],
            'datetime'   => ['timestamp',         null],
            'timestamp'  => ['timestamp',         null],
        ];

        $TypeObj  = array_key_exists($C['DATA_TYPE'], $TranslateType) ? $TranslateType[$C['DATA_TYPE']] : [trim($C['DATA_TYPE']),null,null];

        $Type = "{$TypeObj[0]}";

        if(!is_null($TypeObj[1])){
            $Type .= "(";
            $Type .= $C[$TypeObj[1]];
            if(!is_null($TypeObj[2])) $Type .= ",".$C[$TypeObj[2]];
            $Type .= ")";
        };

        return $Type;
    }

    public function postExportPostgresql()
    {
        extract(request()->all()); //filters, Entidades

        $f = "";

        if($filters['DatabaseOp'] == 'drop') $f .= "DROP DATABASE IF EXISTS {$filters['Database']};";
        if(in_array($filters['DatabaseOp'], ['create','drop'])) $f .= "\nCREATE DATABASE {$filters['Database']} WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE = 'Spanish_Colombia.1252';\n";

        $f .= "\n\connect {$filters['Database']}\n\n";

        $f .= "SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_table_access_method = heap;\n";

        $Bdds = \App\Models\BDD::get()->keyBy('id');

        foreach ($Entidades as $E) {
            $f .= "\n-- {$E['Nombre']}";

            $Bdd = $Bdds->get($E['bdd_id']);
            $tableRoute = \App\Functions\CamposHelper::getTableRoute($Bdd, $E['Tabla']);
            $Conn = \App\Functions\ConnHelper::getConn($Bdd);
            $Helper = \App\Functions\CamposHelper::getHelper($Bdd);

            if($E['estructura']){

                $Columnas = $Helper->getColumns($Conn, $tableRoute);
                $ColCount = count($Columnas);

                $f .= "\nDROP TABLE IF EXISTS {$filters['Schema']}.{$tableRoute['Table']};\nCREATE TABLE {$filters['Schema']}.{$tableRoute['Table']} (";

                foreach ($Columnas as $kC => $C) {
                    $DataType = self::prepPostgresqlDatatype($C);
                    
                    $f .= "\n\t{$C['COLUMN_NAME']} $DataType";
                    if($C['IS_NULLABLE'] == 'YES') $f .= " NOT NULL";
                    if($kC < ($ColCount - 1)) $f .= ",";
                }

                $f .= "\n);";
            };

            $f .= "\n";
        }


        return [ 'sql' => $f ];
        //return $Entidades;
    }

}
