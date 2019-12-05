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

    public function getValores($Variable)
    {
        return $Variable->valores()->get()->keyBy('Periodo')->transform(function($v) use ($Variable){
            $v->formatVal($Variable->TipoDato, $Variable->Decimales);
            return [ 'val' => $v->val, 'Valor' => $v->Valor ];
        });
    }

    public function postGetVariable()
    {
        $Variable = Variable::where('id', request('id'))->with(['grid','grid.columnas'])->first();
        $Variable->valores = $this->getValores($Variable);
        return $Variable;
    }

    public function postGet()
    {
    	$Variable = Variable::where('id', request('id'))->with([])->first();
        $Variable->valores = $this->getValores($Variable);

        $VariablesRelacionadas = [];
        if($Variable->Tipo == 'Calculado de Entidad'){
            $VariablesRelacionadas = Variable::where('id', '<>', $Variable->id)->where('grid_id', $Variable->grid_id)->get();
            foreach ($VariablesRelacionadas as $V) {
                $V->valores = $this->getValores($V);
            }
        };

        $Variable->related_variables = $VariablesRelacionadas;

        return $Variable;
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
            $V->valores = $this->getValores($V);
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
            $V->valores = $this->getValores($V);
        }
        return $Variables;
    }
}
