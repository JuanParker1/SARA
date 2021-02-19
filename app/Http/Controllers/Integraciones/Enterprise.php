<?php

namespace App\Http\Controllers\Integraciones;

use Carbon\Carbon;
use App\Functions\ConnHelper;
use App\Functions\Helper;
use GuzzleHttp\Client;

class Enterprise {

    public static function upload()
    {
        $folder   = 'temp';
        $filename = 'Import_Enterprise.xlsx';
        request()->file('file')->move($folder, $filename);

        $BaseRegs = Helper::readTableFile($folder.'/'.$filename, [
            'col_ini' => 1, 'col_fin' => 25, 'row_ini' => 8, 'row_fin' => null,
            'headers' => [
                'CODIGO','SECCION','ORDEN','TIPO_ORDEN','HISTORIA','APELLIDOS','NOMBRES','SEXO','EDAD','EXAMEN','ESTADO','MUESTRA','RESULTADO','UNIDAD','VALORMINIMO','MAXIMO','TIPO_RESULTADO','FECHA_VALIDACION','COMENTARIO','SEDE','CONVENIO','CENTRO_COSTOS','USUARIO_ING','FECHA_INGRESO','FECHA_VERIFICA'
            ]
        ]);

        return $BaseRegs;

        $BaseRegs->transform(function($Reg){
            
            $Reg['FECHANAC'] = Carbon::createFromFormat('d/m/Y', str_replace("'", "", $Reg['FECHANAC']))->format('Y-m-d');
            if(config('app.encode_utf8')){
                $Fields = ['TIPOPARTO', 'MADRENOM', 'MADREAPE'];
                foreach ($Fields as $F) {
                    $Reg[$F] = utf8_decode($Reg[$F]);
                }
                
            }
            return $Reg;
        });

        //return $BaseRegs;

        $Bdd = \App\Models\BDD::where('id', 1)->first();
        $Conn = ConnHelper::getConn($Bdd);

        $Conn->statement('TRUNCATE TABLE BDSALUD.TBSRRUAFNV');

        foreach (array_chunk($BaseRegs->toArray(), 100) as $Regs) {
            $Conn->table('BDSALUD.TBSRRUAFNV')->insert($Regs);
        }

        //$Conn->table('BDSALUD.TBSRRUAFNV')->insert($BaseRegs->toArray());

        return [ 'regs' => count($BaseRegs) ];
    }

}