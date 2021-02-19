<?php

namespace App\Http\Controllers\Integraciones;

use Carbon\Carbon;
use App\Functions\ConnHelper;
use App\Functions\Helper;
use GuzzleHttp\Client;

class SOMA {
	
	public static function getrows()
	{
		set_time_limit(0);

		extract(request()->all());
        $Fecha = Carbon::parse($Desde)->setTimezone('America/Bogota')->format('Ymd');
        $Bdd = \App\Models\BDD::where('id', 1)->first();
        $Conn = ConnHelper::getConn($Bdd);

        $Tables = [
            'GCFR' => 'ZZVISTASAL.VTPGPSOMA',
            'ONC'  => 'ZZVISTASAL.VTPGPSOMAONC',
        ];

        $Rows = collect($Conn->table($Tables[$Tipo])->where('FECHAEV', $Fecha)->get())->transform(function($R){
        	foreach ($R as $C => &$Valor) {
        		$Valor = utf8_encode(trim($Valor));
        		if($C == 'ACTIVIDAD' AND !$Valor) $Valor = 'SIN DESCRIPCION'; 

                if(in_array($C, ['DIAG_PPAL', 'DIAG_REL'])){
                	if(!$Valor){ $Valor = 'Z000'; }
                	if(in_array($Valor, ['0885','1941','1332','2135','0555','2105','0582','1447'])){ $Valor = 'Z001'; } 
                }

        	}
        	return $R;
        });

        return $Rows;
	}

	public static function download()
	{
        $Rows = self::getrows();
        $Columns = ['TIPO'
        	,'IDENTIFICACION'
        	,'DIAG_PPAL'
        	,'DIAG_REL'
        	,'FECHA_EVENTO'
        	,'COD_ACTIVIDAD'
        	,'ACTIVIDAD'
        	,'AMBITO'
        	,'CONTRATACION'
        	,'DIAS_ESTANCIA'
        	,'VALOR'
        	,'CUOTA_MODERADORA'
        	,'COPAGO'
        	,'TIPO_SERVICIO'
        	,'NO_FACTURA'
        	,'DURACION_TRATAMIENTO'
        	,'FECHA_SOLICITUD'
        	,'FECHA_SOLICITUD_MEDICA'
        	,'PLAN'
        	,'PE_DT'
        ];

        $Text = "";
        foreach ($Rows as $k => $R) {
            foreach ($Columns as $C) {
                $Valor = $R[$C];
                $Text .= $Valor;
                if($C !== 'PE_DT') $Text .= '|';
            }
            $Text .= "\r\n";
        }

        return $Text;
	}


	public static function send()
	{
		$Rows = self::getrows();

		$Rows->transform(function($R){
			return [
	    		"tipoId" 					=> $R['TIPO'],
		        "id"                   		=> $R['IDENTIFICACION'],
		        "codigoDiagnostico"   		=> $R['DIAG_PPAL'],
		        "codigoDiagnosticoRel" 		=> $R['DIAG_REL'],
		        "fechaPrestacion" 			=> $R['FECHA_EVENTO'],
		        "codigoPrestacion" 			=> $R['COD_ACTIVIDAD'],
		        "descripcionPrestacion" 	=> $R['ACTIVIDAD'],
		        "ambito" 					=> $R['AMBITO'],
		        "formaReconocimiento" 		=> $R['CONTRATACION'],
		        "cantidad" 					=> $R['DIAS_ESTANCIA'],
		        "valorActividad" 			=> $R['VALOR'],
		        "cuotaModeradora" 			=> $R['CUOTA_MODERADORA'],
		        "copago" 					=> $R['COPAGO'],
		        "tipoPrestacion" 			=> $R['TIPO_SERVICIO'],
		        "numeroFactura" 			=> $R['NO_FACTURA'],
		        "duracionTratamiento" 		=> $R['DURACION_TRATAMIENTO'],
		        "fechaSolicitudServicio" 	=> $R['FECHA_SOLICITUD'],
		        "fechaAutorizacionServicio" => $R['FECHA_SOLICITUD_MEDICA'],
		        "plan" 						=> $R['PLAN'],
		        "actividadPeDt" 			=> $R['PE_DT'],
	    	];
		});

		$Auth = [
            'GCFR' => ['cldape6601', 'sos2017'],
            'ONC'  => ['ATICA2019',  'sos2019'],
        ];

        //$url = 'https://pruebascloud.sos.com.co/AutorizadorPrestacionesServiceRESTWeb/rest/validador/pgp';
        $url = 'https://centralaplicaciones.sos.com.co/AutorizadorPrestacionesServiceRESTWeb/rest/validador/pgp';

		$client = new Client();
		$res = $client->request('POST', $url, [
			'verify' => false,
		    'auth' => $Auth[request('Tipo')],
		    'json' => $Rows
		]);

		return $res->getBody();
	}

}