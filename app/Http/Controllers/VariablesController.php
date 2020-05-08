<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;
use App\Models\Variable;
use App\Models\VariableValor;

use App\Functions\Helper AS H;
use App\Functions\GridHelper;

class VariablesController extends Controller
{
    //Variables
    public function postIndex()
    {
        $CRUD = new CRUD('App\Models\Variable');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postGetVariable()
    {
        $Variable = Variable::where('id', request('id'))->with(['grid','grid.columnas'])->first();
        $Variable->Filtros = $Variable->prepFiltros();
        $Variable->valores = $Variable->getVals();
        return $Variable;
    }

    public function postGet()
    {
    	$Variable = Variable::where('id', request('id'))->first();
        $Variable->valores = $Variable->getVals();
 
        $Variable->desagregados = [];
        $Variable->desagregables = $Variable->getDesagregables();

        return $Variable;
    }

    public function postGetDesagregacion($value='')
    {
        extract(request()->all()); //variable_id, Anio, desag_campos

        $Variable = Variable::where('id', $variable_id)->first();
        return $Variable->getDesagregated( ($Anio*100)+1, ($Anio*100)+12, $desag_campos);
    }

    public function postGetUsuario()
    {
        extract(request()->all()); //Usuario

        $ProcesosIds = collect($Usuario['Procesos'])->pluck('id')->toArray();

        $Variables = Variable::whereIn('proceso_id', $ProcesosIds)->get();

        foreach ($Variables as $V) {
            $V['valores'] = $V->getVals($Anio);
        }

        return $Variables;
    }



    public function postUpdateValor()
    {
        extract(request()->all());
        $V = VariableValor::firstOrCreate(compact('variable_id','Periodo'));
        $V->Valor = $Valor;
        $V->save();
    }

    public function postGetVariables()
    {
        $Variables = Variable::whereIn('id', request('ids'))->get();
        foreach ($Variables as $V) {
            $V->valores = $V->getVals();
        }
        return $Variables;
    }

    public function postCalcValores()
    {
        extract(request()->all()); //Var, Periodos
        $Valores = [];

        if($Var['Tipo'] == 'Valor Fijo'){
            $ult_valor = VariableValor::where('variable_id', $Var['id'])->whereNotNull('Valor')->orderBy('Periodo', 'DESC')->first();
            if($ult_valor){
                $ult_valor->formatVal($Var['TipoDato'], $Var['Decimales']);
                foreach ($Periodos as $P) {
                    $Valores[$P] = [ 'val' => $ult_valor->val, 'Valor' => $ult_valor->Valor ];
                }
            }
        }else if($Var['Tipo'] == 'Calculado de Entidad'){
            
            $Var = Variable::find($Var['id']);
            $Var->Filtros = $Var->prepFiltros();

            $Grid = GridHelper::getGrid($Var['grid_id']);
            $q    = GridHelper::getQ($Grid->entidad);
            
            GridHelper::calcJoins($Grid);
            GridHelper::addJoins($Grid, $q);
            GridHelper::addFilters($Var['Filtros'], $Grid, $q);

            $ColPeriodo = H::getElm($Grid->columnas, $Var['ColPeriodo']);
            $ColPeriodoName = \DB::raw($ColPeriodo->campo->getColName($ColPeriodo['tabla_consec']));
            $ColCalculo = H::getElm($Grid->columnas, $Var['Col']);
            $ColCalculoName = $ColCalculo->campo->getColName($ColCalculo['tabla_consec']);

            $q->whereIn($ColPeriodoName, $Periodos);
            GridHelper::getGroupedData($Grid, $q, [$ColPeriodoName], [ [ $ColCalculoName, $Var['Agrupador'] ] ]);

            $Data = GridHelper::getData($Grid, $q, false, false, false);

            //return $Grid->sql;

            foreach ($Data as $d) {
                $VarVal = new VariableValor([ 'Valor' => $d[1] ]);
                $VarVal->formatVal($Var['TipoDato'], $Var['Decimales']);
                $Valores[$d[0]] = [ 'val' => $VarVal->val, 'Valor' => $VarVal->Valor ];
            };
        }

        return $Valores;
    }

    public function postStoreValores()
    {
        extract(request()->all()); //Variables, Periodos, overwriteValues

        $ids = [];
        foreach ($Variables as $V) {
            foreach ($V['newValores'] as $Periodo => $nv) {
                if(!$overwriteValues AND isset($V['valores'][$Periodo])){
                    if($V['valores'][$Periodo]['Valor'] == $nv['Valor']) continue;
                };
                VariableValor::updateOrCreate([ 'variable_id' => $V['id'], 'Periodo' => $Periodo ],[ 'Valor' => $nv['Valor'] ]);
            };
            $ids[] = $V['id'];
        };

        $Variables = Variable::whereIn('id', $ids)->get();
        foreach ($Variables as $V) {
            $V->valores = $V->getVals();
        }
        return $Variables;
    }

    public function postStoreAll()
    {
        extract(request()->all()); //VariablesValores

        foreach ($VariablesValores as $VP) {
            $DaVP = VariableValor::where('variable_id', $VP['variable_id'])->where('Periodo', $VP['Periodo'])->first();

            if(!$DaVP AND !is_null($VP['Valor'])){
                $DaVP = new VariableValor($VP);
                $DaVP->save();
            }else{
                $DaVP->fillit($VP);
                $DaVP->save();
            }

        }
    }



}
