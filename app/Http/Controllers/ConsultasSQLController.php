<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\ConnHelper;
use BD;


class ConsultasSQLController extends Controller
{
    

    public function postPgpNt()
    {
    	set_time_limit(600);

    	$Dia = request('Dia');
    	$Dia = str_replace('-', "", $Dia);

    	$Arr = [
    		'fecha'               => $Dia,
    		'detalle_eventos_pre' => 0,
    		'detalle_eventos_pos' => 0,
    		'eventos_pre'         => 0,
    		'eventos_pos'         => 0,
    		'mensaje'             => ''
    	];

		$BDD = \App\Models\BDD::where('id', 1)->first();
		$BD = ConnHelper::getConn($BDD);

		//Detalles
		$CantPre = $BD->select("SELECT COUNT(*) AS CANT FROM ZZVISTASAL.VMPGPEVDET WHERE FECHAEV = $Dia");
		$EvenPre = $BD->select("SELECT COUNT(*) AS CANT FROM ZZVISTASAL.VMPGPEV    WHERE FECHAEV = $Dia");
		$Arr['detalle_eventos_pre'] = intval($CantPre[0]['CANT']);
		$Arr['eventos_pre'] = intval($EvenPre[0]['CANT']);

		$BD->statement("DELETE FROM ZZVISTASAL.VMPGPEVDET WHERE FECHAEV = $Dia");

		$InsertQuery = " 
			INSERT INTO ZZVISTASAL.VMPGPEVDET 
			SELECT ('CLI'||ing.INGCODING) AS IDEV
			    ,INT(ing.INGFECSALI/100) AS PERIODOEV ,ing.INGFECSALI AS FECHAEV ,ing.INGFECINGR AS FECHAING ,ing.INGFECSALI AS FECHASAL
			    ,be.BECODBENE  ,BDSALUD.FNDOCBENE( be.BECODBENE ) AS PACIENTE ,BDSALUD.FNNOMBBENE( be.BECODBENE ) AS NOMBRE ,be.TDTIPDOC ,be.BENUMDOCBE
			    ,ac.ACCODACTIV ,ac.ACDESACTIV ,ac.ACCODAGR AS CODCUP ,TRIM(cu.ACDESACTIV) AS CUP ,ac.CTCCLASF1
			    ,gr.ID AS GRUPOID ,gr.GRUPO ,gr.SUBGRUPO ,gr.PRIORIDAD ,gr.AMBITO
			    ,COALESCE(ej.ENCODENTID, ing.INGCODENTI) AS ENCODENTID ,COALESCE(ej.EPCODPLAN, ing.INGCODPLAN) AS EPCODPLAN 
			    ,(ej.TARIFA * ej.CANT) AS TARIFA ,(ej.COSTO * ej.CANT) AS COSTO ,ej.CANT 
			    ,CASE WHEN onc.ID IS NULL THEN 'NO' ELSE 'SI' END AS ONCOLOGICO ,ej.GRUPO AS GRUPODX
			    ,ing.INGMOTIVO AS ESTADOING 
			    ,ej.DICODDIAG AS DXPPAL ,'' AS DXREL 
			    ,ej.INGCODING ,ej.CICODCITA ,ej.MRCODCONS ,ing.INGCODCONT AS CODCONT
			    ,ej.TSCODSERV ,BDSALUD.FNDESCSERV( ej.TSCODSERV ) AS SERVICIO
			    ,ROW_NUMBER() OVER ( PARTITION BY ing.INGCODING ORDER BY PRIORIDAD ASC, TARIFA DESC ) AS LINEA 
			FROM BDSALUD.TBEJECAPI ej
			    JOIN BDSALUD.TBBDINGRES ing ON ( ej.INGCODING  = ing.INGCODING ) -- Ingresos Clinica 
			    JOIN BDSALUD.TBBDBENEFI be  ON ( ing.BECODBENE = be.BECODBENE ) -- Beneficiarios 
			    JOIN BDSALUD.TBFAACTIVI ac  ON ( ej.ACCODACTIV = ac.ACCODACTIV ) -- Actividades 
			    LEFT JOIN ZZVISTASAL.VTRONCPBL onc ON ( be.BECODBENE = onc.BECODBENE ) -- Oncologicos
			    LEFT JOIN BDSALUD.TBPGPGRUPO gr  ON ( gr.ID = BDSALUD.FNGRUPONT( ac.ACCODAGR ) ) -- Grupo PGP
			    LEFT JOIN BDSALUD.TBFACUPS   cu  ON ( ac.ACCODAGR = cu.ACCODACTIV ) -- Codigos CUPS
			WHERE 1 = 1
			    --AND ing.INGFECSALI = $Dia 'Fecha se cambia por fecha de ejecapi'
			    AND ej.Fecha = $Dia
			    AND ing.INGEXCLU IS NULL 
			    AND (ac.CTCCLASF1 NOT IN ('NPO') OR ac.CTCCLASF1 IS NULL) 
			    AND ac.ACCODAGR NOT IN ('906340') -- Solicitado Alexis 16 Feb 2022
			    --AND ing.INGCODING IN (1640172,1639815) 
			UNION ALL  -- Citas
			SELECT ('CIT'||cit.CICODCITA||'_'||ac.ACCODAGR) AS IDEV,
			    INT(cit.CIFECCITA/100) AS PERIODOEV ,
			     ---INT(ej.fecha/100) AS PERIODOEV,
			     ---ej.fecha AS FECHAEV ,
			    cit.CIFECCITA AS FECHAEV ,
			    cit.CIFECCITA AS FECHAING ,cit.CIFECCITA AS FECHASAL
			    ,be.BECODBENE  ,BDSALUD.FNDOCBENE( be.BECODBENE ) AS PACIENTE ,BDSALUD.FNNOMBBENE( be.BECODBENE ) AS NOMBRE ,be.TDTIPDOC ,be.BENUMDOCBE
			    ,ac.ACCODACTIV ,ac.ACDESACTIV ,ac.ACCODAGR AS CODCUP ,TRIM(cu.ACDESACTIV) AS CUP ,ac.CTCCLASF1
			    ,gr.ID AS GRUPOID ,gr.GRUPO ,gr.SUBGRUPO ,gr.PRIORIDAD ,gr.AMBITO
			    ,COALESCE(ej.ENCODENTID, cit.ENCODENTID) AS ENCODENTID ,COALESCE(ej.EPCODPLAN, cit.EPCODPLAN) AS EPCODPLAN 
			    ,(ej.TARIFA * ej.CANT) AS TARIFA ,(ej.COSTO * ej.CANT) AS COSTO ,ej.CANT 
			    ,CASE WHEN onc.ID IS NULL THEN 'NO' ELSE 'SI' END AS ONCOLOGICO ,ej.GRUPO AS GRUPODX
			    ,'' AS ESTADOING 
			    ,BDSALUD.FNCITADIAG( cit.CICODCITA ) AS DXPPAL ,BDSALUD.FNDXRE1AMB( cit.CICODCITA ) AS DXREL 
			    ,ej.INGCODING ,ej.CICODCITA ,ej.MRCODCONS ,cit.COCODCTO AS CODCONT 
			    ,ej.TSCODSERV ,BDSALUD.FNDESCSERV( ej.TSCODSERV ) AS SERVICIO
			    ,ROW_NUMBER() OVER ( PARTITION BY cit.CICODCITA||ac.ACCODAGR ORDER BY PRIORIDAD ASC, TARIFA DESC ) AS LINEA 
			FROM BDSALUD.TBEJECAPI ej 
			    JOIN BDSALUD.TBAGCITAS cit  ON ( ej.CICODCITA  = cit.CICODCITA ) -- Citas 
			    JOIN BDSALUD.TBBDBENEFI be  ON ( cit.BECODBENE = be.BECODBENE ) -- Beneficiarios 
			    JOIN BDSALUD.TBFAACTIVI ac  ON ( ej.ACCODACTIV = ac.ACCODACTIV ) -- Actividades 
			    LEFT JOIN ZZVISTASAL.VTRONCPBL onc ON ( be.BECODBENE = onc.BECODBENE ) -- Oncologicos
			    LEFT JOIN BDSALUD.TBPGPGRUPO gr  ON ( gr.ID = BDSALUD.FNGRUPONT( ac.ACCODAGR ) ) -- Grupo PGP
			    LEFT JOIN BDSALUD.TBFACUPS   cu  ON ( ac.ACCODAGR = cu.ACCODACTIV ) -- Codigos CUPS
			WHERE 1 = 1 
			    AND ej.INGCODING IS NULL 
			    --AND cit.CIFECCITA = $Dia,se cambia porque el registro no sale
			    AND ej.Fecha = $Dia
			UNION ALL
			SELECT ('ATE'||f.MRCODCONS||'_'||ac.ACCODAGR) AS IDEV
			    ,INT(f.MRFECATE/100) AS PERIODOEV ,f.MRFECATE AS FECHAEV ,f.MRFECATE AS FECHAING ,f.MRFECATE AS FECHASAL
			    ,be.BECODBENE  ,BDSALUD.FNDOCBENE( be.BECODBENE ) AS PACIENTE ,BDSALUD.FNNOMBBENE( be.BECODBENE ) AS NOMBRE ,be.TDTIPDOC ,be.BENUMDOCBE
			    ,ac.ACCODACTIV ,ac.ACDESACTIV ,ac.ACCODAGR AS CODCUP ,TRIM(cu.ACDESACTIV) AS CUP ,ac.CTCCLASF1
			    ,gr.ID AS GRUPOID ,gr.GRUPO ,gr.SUBGRUPO ,gr.PRIORIDAD ,gr.AMBITO
			    ,COALESCE(ej.ENCODENTID, f.ENCODENTID) AS ENCODENTID ,COALESCE(ej.EPCODPLAN, f.EPCODPLAN) AS EPCODPLAN
			    ,(ej.TARIFA * ej.CANT) AS TARIFA ,(ej.COSTO * ej.CANT) AS COSTO ,ej.CANT 
			    ,CASE WHEN onc.ID IS NULL THEN 'NO' ELSE 'SI' END AS ONCOLOGICO ,ej.GRUPO AS GRUPODX
			    ,'' AS ESTADOING 
			    ,ej.DICODDIAG AS DXPPAL ,'' AS DXREL 
			    ,ej.INGCODING ,ej.CICODCITA ,ej.MRCODCONS ,ej.MRCODCONS AS CODCONT 
			    ,ej.TSCODSERV ,BDSALUD.FNDESCSERV( ej.TSCODSERV ) AS SERVICIO
			    ,ROW_NUMBER() OVER ( PARTITION BY f.MRCODCONS||ac.ACCODAGR ORDER BY PRIORIDAD ASC, TARIFA DESC ) AS LINEA 
			FROM BDSALUD.TBEJECAPI ej 
			    JOIN BDSALUD.TBFAMOVREC f   ON (ej.MRCODCONS = f.MRCODCONS) -- Facturas
			    JOIN BDSALUD.TBBDBENEFI be  ON ( f.BECODBENE = be.BECODBENE ) -- Beneficiarios
			    JOIN BDSALUD.TBFAACTIVI ac  ON ( ej.ACCODACTIV = ac.ACCODACTIV ) -- Actividades 
			    LEFT JOIN ZZVISTASAL.VTRONCPBL onc ON ( be.BECODBENE = onc.BECODBENE ) -- Oncologicos
			    LEFT JOIN BDSALUD.TBPGPGRUPO gr  ON ( gr.ID = BDSALUD.FNGRUPONT( ac.ACCODAGR ) ) -- Grupo PGP
			    LEFT JOIN BDSALUD.TBFACUPS   cu  ON ( ac.ACCODAGR = cu.ACCODACTIV ) -- Codigos CUPS
			WHERE 1 = 1 
			    AND ej.INGCODING IS NULL AND ej.CICODCITA IS NULL AND ej.MRCODCONS IS NOT NULL 
			    AND f.MRFECATE = $Dia 
			UNION ALL 
			SELECT DISTINCT TRIM(('EXT'||TRIM(ej.urgencias)||'_'||ac.ACCODAGR)) AS IDEV
			            ,INT(FECHA/100) AS PERIODOEV ,FECHA AS FECHAEV ,FECHA AS FECHAING ,FECHA AS FECHASAL
			            ,be.BECODBENE  ,BDSALUD.FNDOCBENE( be.BECODBENE ) AS PACIENTE ,BDSALUD.FNNOMBBENE( be.BECODBENE ) AS NOMBRE ,be.TDTIPDOC ,be.BENUMDOCBE
			            ,ac.ACCODACTIV ,ac.ACDESACTIV ,ac.ACCODAGR AS CODCUP ,TRIM(cu.ACDESACTIV) AS CUP ,ac.CTCCLASF1
			            ,gr.ID AS GRUPOID ,gr.GRUPO ,gr.SUBGRUPO ,gr.PRIORIDAD ,gr.AMBITO
			            , con.ENCODENTID  AS ENCODENTID ,con.EPCODPLAN AS EPCODPLAN
			            ,(ej.TARIFA * ej.CANT) AS TARIFA ,(ej.COSTO * ej.CANT) AS COSTO ,ej.CANT 
			            ,CASE WHEN onc.ID IS NULL THEN 'NO' ELSE 'SI' END AS ONCOLOGICO ,ej.GRUPO AS GRUPODX
			            ,'' AS ESTADOING 
			            ,ej.DICODDIAG AS DXPPAL ,'' AS DXREL 
			            ,ej.INGCODING ,ej.CICODCITA ,URGENCIAS ,URGENCIAS AS CODCONT 
			            ,ej.TSCODSERV ,BDSALUD.FNDESCSERV( ej.TSCODSERV ) AS SERVICIO
			            ,ROW_NUMBER() OVER ( PARTITION BY URGENCIAS||ac.ACCODAGR ORDER BY PRIORIDAD ASC, TARIFA DESC ) AS LINEA 
			FROM BDSALUD.TBEJECAPI ej 
			            JOIN BDSALUD.tbcoautord f   ON (decimal(ej.urgencias) = f.autconsord) -- Facturas
			            JOIN BDSALUD.tbbdcontra con ON (con.cocodcto=f.cocodcto AND con.conomcapit='S')
			            JOIN BDSALUD.TBBDBENEFI be  ON ( f.BECODBENE = be.BECODBENE ) -- Beneficiarios
			            JOIN BDSALUD.TBFAACTIVI ac  ON ( ej.ACCODACTIV = ac.ACCODACTIV ) -- Actividades 
			            LEFT JOIN ZZVISTASAL.VTRONCPBL onc ON ( be.BECODBENE = onc.BECODBENE ) -- Oncologicos
			            LEFT JOIN BDSALUD.TBPGPGRUPO gr  ON ( gr.ID = BDSALUD.FNGRUPONT( ac.ACCODAGR ) ) -- Grupo PGP
			            LEFT JOIN BDSALUD.TBFACUPS   cu  ON ( ac.ACCODAGR = cu.ACCODACTIV ) -- Codigos CUPS
			WHERE 1 = 1 
			            AND ej.INGCODING IS NULL AND ej.CICODCITA IS NULL AND URGENCIAS IS NOT NULL  AND ASCODAREA='E'
			            AND ej.fecha = $Dia 
		";

		$BD->statement($InsertQuery);

		

		//Eventos
		$BD->statement("DELETE FROM ZZVISTASAL.VMPGPEV WHERE FECHAEV = $Dia");

		$InsertQuery2 = "INSERT INTO ZZVISTASAL.VMPGPEV  
		    	SELECT det.IDEV
					,det.PERIODOEV ,det.FECHAEV ,det.FECHAING ,det.FECHASAL 
					,det.BECODBENE ,det.PACIENTE ,det.NOMBRE ,det.TDTIPDOC ,det.BENUMDOCBE
					,det.ACCODACTIV ,det.ACDESACTIV ,det.CODCUP ,det.CUP
					,det.GRUPOID ,det.GRUPO ,det.SUBGRUPO ,det.PRIORIDAD ,det.AMBITO
					,det.ENCODENTID ,ep.ENTIDAD ,det.EPCODPLAN ,ep.PLAN 
					,det.ONCOLOGICO ,det.GRUPODX
					,det.ESTADOING 
					,det.DXPPAL ,det.DXREL 
					,det.INGCODING ,det.CICODCITA ,det.MRCODCONS ,det.CODCONT 
					,BDSALUD.FNCONTRPGP( det.BECODBENE, det.ENCODENTID, det.EPCODPLAN, det.GRUPOID, det.FECHAEV ) AS CONTRATO 
					,BDSALUD.FNDIAEST( det.INGCODING ) AS DIAS_ESTANCIA 
					,BDSALUD.FNPLANCOMP(det.BECODBENE, det.PERIODOEV) AS PLAN_COMP 
					,CASE WHEN det.ESTADOING NOT LIKE '' THEN 1 ELSE 
					 ( SELECT SUM(d2.CANT) FROM ZZVISTASAL.VMPGPEVDET d2 WHERE d2.IDEV = det.IDEV ) END AS CANT
					,( SELECT SUM(d2.TARIFA) FROM ZZVISTASAL.VMPGPEVDET d2 WHERE d2.IDEV = det.IDEV ) AS TARIFA
					,( SELECT SUM(d2.COSTO)  FROM ZZVISTASAL.VMPGPEVDET d2 WHERE d2.IDEV = det.IDEV ) AS COSTO
				FROM ZZVISTASAL.VMPGPEVDET det 
					JOIN ZZVISTASAL.VTENTPLA ep ON ( ep.ENCODENTID = det.ENCODENTID AND ep.EPCODPLAN = det.EPCODPLAN ) 
				WHERE 1 = 1
					AND det.LINEA = 1 
					AND det.FECHAEV = $Dia ";

		$BD->statement($InsertQuery2);

		$CantPos = $BD->select("SELECT COUNT(*) AS CANT FROM ZZVISTASAL.VMPGPEVDET WHERE FECHAEV = $Dia");
		$EvenPos = $BD->select("SELECT COUNT(*) AS CANT FROM ZZVISTASAL.VMPGPEV    WHERE FECHAEV = $Dia");
		$Arr['detalle_eventos_pos'] = intval($CantPos[0]['CANT']);
		$Arr['eventos_pos'] = intval($EvenPos[0]['CANT']);

		$detalle_eventos_diff = $Arr['detalle_eventos_pos'] - $Arr['detalle_eventos_pre'];
		$eventos_diff = $Arr['detalle_eventos_pos'] - $Arr['detalle_eventos_pre'];

		$Arr['mensaje'] = "{$Arr['fecha']}: detalle de eventos cargados: {$Arr['detalle_eventos_pos']} ({$detalle_eventos_diff}), eventos cargados: {$Arr['eventos_pos']} ($eventos_diff).";

    	return $Arr;
    }
}