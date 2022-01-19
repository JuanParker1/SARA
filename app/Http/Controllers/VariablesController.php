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
use Carbon\Carbon;

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
        extract(request()->all()); //id, Tipo
        $Variable = Variable::where('id', $id)->with(['grid','grid.columnas'])->first();
        $Variable->Filtros = $Variable->prepFiltros();
        $Variable->valores = $Variable->getVals(false, false);
        return $Variable;
    }

    public function postGet()
    {
    	extract(request()->all()); //Anio
        $Variable = Variable::where('id', request('id'))->first();
        $Variable->valores = $Variable->getVals(false);
 
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
            $V['valores'] = $V->getVals($Anio, false);
        }

        return $Variables;
    }



    public function postUpdateValor()
    {
        extract(request()->all());
        $V = VariableValor::firstOrCreate(compact('variable_id','Periodo'));
        $V->Valor = $Valor;
        $V->save();

        H::touchIndicadores();

    }

    public function postGetVariables()
    {
        $Variables = Variable::whereIn('id', request('ids'))->tipo(request('Tipo'))->get();
        foreach ($Variables as $V) {
            $V->valores = $V->getVals();
        }
        return $Variables;
    }

    public function postCalcValores()
    {
        extract(request()->all()); //Var, Periodos
        $Valores = [];

        if(in_array($Var['Tipo'], ['Valor Fijo', 'Calculado de Entidad'])){
            foreach ($Periodos as $P) {
                $Valores[$P] = [ 'val' => 0, 'Valor' => 0 ];
            }
        }

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
                $Valores[$d[0]] = [ 'val' => $VarVal->val, 'Valor' => $VarVal->Valor, 'sql' => $Grid->sql ];
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
                if(!$overwriteValues AND isset($V['valores'][$Periodo]['val'])){
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

        H::touchIndicadores(); 

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


    public function postConvertirEnIndicador()
    {
        extract(request()->all()); //Variable

        $Ind = new \App\Models\Indicador([
            'proceso_id' => $Variable['proceso_id'],
            'Indicador' => $Variable['Variable'], 
            'Definicion' => '', 
            'TipoDato' => $Variable['TipoDato'],  
            'Decimales' => $Variable['Decimales'], 
            'Formula' => '',   
            'Sentido' => 'ASC',   
        ]);
        $Ind->save();

        
        //Cambiar las asignaciones actuales
        \App\Models\IndicadorVariable::where([
            'Tipo' => 'Variable',  'variable_id' => $Variable['id']
        ])->update([
            'Tipo' => 'Indicador', 'variable_id' => $Ind->id
        ]);

        //Añadir la variable como componente
        /*$IndComp = new \App\Models\IndicadorVariable([
            'indicador_id' => $Ind->id,
            'Letra' => 'a',
            'Tipo' => 'Variable',
            'variable_id' => $Variable['id'],
        ]);
        $IndComp->save();*/

        $DaVariable = Variable::where('id', $Variable['id'])->first();
        $DaVariable->delete();

        return compact('Ind', 'IndComp');

    }



    public function postDeleteVariable()
    {
        extract(request()->all()); //Variable

        $DaVariable = Variable::where('id', $Variable['id'])->first();
        $DaVariable->delete();
    }



    public function postCanEdit()
    {
        extract(request()->all()); //Variable, Periodo
        $Usuario = H::getUsuario();
        $editable = false;

        if($Usuario->isGod){
            $editable = true;
        }else if($Variable['Tipo'] == 'Manual'){

            $Conf    = H::getConfiguracion();

            $FRECUENCIAS_HAB = $Conf['VARIABLES_FRECUENCIAS_HAB']['Valor'];

            if(!in_array($Variable['Frecuencia'], $FRECUENCIAS_HAB)){
                $editable = false;
            }else{
                $DIAS_DESDE = $Variable['DiasDesde'] ?? $Conf['VARIABLES_DIAS_DESDE']['Valor'];
                $DIAS_HASTA = $Variable['DiasHasta'] ?? $Conf['VARIABLES_DIAS_HASTA']['Valor'];

                $DiaCierre = Carbon::parse($Periodo.'01')->addMonth();

                $DiaDesde  = $DiaCierre->copy()->addDays($DIAS_DESDE);
                $DiaHasta  = $DiaCierre->copy()->addDays($DIAS_HASTA);

                $Today = Carbon::today();

                if($Today->greaterThanOrEqualTo($DiaDesde) AND $Today->lessThan($DiaHasta)){
                    $editable = true;
                }
            }

            //dd(compact('DIAS_HASTA', 'DiaDesde', 'DiaHasta', 'Today', 'editable'));

        }

        return [ 'editable' => $editable ];
    }


}
