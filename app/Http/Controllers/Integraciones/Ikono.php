<?php

namespace App\Http\Controllers\Integraciones;

use Carbon\Carbon;
use App\Functions\ConnHelper;
use App\Functions\Helper;
use GuzzleHttp\Client;

class Ikono {

    public static function CalcularSegundos($Hora)
    {
        if(!$Hora) return null;
        $HoraArr = array_map('intval', explode(":", $Hora));
        return ( $HoraArr[0] * 3600 ) + ( $HoraArr[1] * 60 ) + ( $HoraArr[2] );
    } 


    public static function upload()
    {
        $folder   = 'temp';
        $filename = 'Import_Ikono.csv';
        request()->file('file')->move($folder, $filename);

        $BaseRegs = Helper::readTableFile($folder.'/'.$filename, [
            'col_ini' => 1, 'col_fin' => 20, 'row_ini' => 2, 'row_fin' => null,
            'headers' => [
                'CODIGO','FECHAHORA','COLA','CAMPANA','SUBCAMPANA','DEVICE','CODAGENTE','TESPERA','THABLA','ORIGEN','NOMBRE','DNID','RDNIS','ANI','TIVR','TRETENCION','ESTADO','ESTADODET','CALIF','DETALLE'
            ]
        ]);

        $BaseRegs->transform(function($Reg){
            
            foreach (['TESPERA','THABLA','TIVR','TRETENCION'] as $Campo) {
                $Reg[$Campo] = self::CalcularSegundos($Reg[$Campo]);
            }

            $Reg['FECHA'] = substr($Reg['FECHAHORA'], 0, 10);
            $FechaArr = explode('-', $Reg['FECHA']);
            if(count($FechaArr) < 2) dd($Reg);
            $Reg['PERIODO'] = $FechaArr[0] . $FechaArr[1];
            
            unset($Reg['ANI']);
            unset($Reg['DNID']);
            unset($Reg['RDNIS']);

            return $Reg;
        });

        $Bdd = \App\Models\BDD::where('id', 1)->first();
        $Conn = ConnHelper::getConn($Bdd);

        $Conn->statement('TRUNCATE TABLE BDSALUD.TBSRRUAFNV');

        foreach (array_chunk($BaseRegs->toArray(), 1000) as $Regs) {
            $Conn->table('BDSALUD.TBSRCCLLAM')->insert($Regs);
        }

        $Conn->table('BDSALUD.TBSRRUAFNV')->insert($BaseRegs->toArray());

        return [ 'regs' => count($BaseRegs) ];
    }

}