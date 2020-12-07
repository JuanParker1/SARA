<?php

namespace App\Models;

use App\Models\Core\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\EntidadCampo;

use App\Functions\Helper AS H;
use App\Functions\GridHelper;
use App\Functions\CamposHelper;
use App\Functions\Helper;

class Variable extends MyModel
{
    protected $table = 'sara_variables';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Filtros' => 'array'
    ];
    protected $with = ['proceso'];
    protected $appends = [ 'Filtros', 'Ruta'];

    use SoftDeletes;

    public function columns()
	{

		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',				null, true, false, null, 100 ],
			[ 'Ruta',					'Ruta',				null, true, false, null, 100 ],
			[ 'proceso_id',				'proceso_id',		null, true, false, null, 100 ],
			[ 'Variable',				'Variable',			null, true, false, null, 100 ],
			[ 'Descripcion',			'Descripcion',		null, true, false, null, 100 ],
			[ 'TipoDato',				'TipoDato',			null, true, false, null, 100 ],
			[ 'Decimales',				'Decimales',		null, true, false, null, 100 ],
			[ 'Tipo',					'Tipo',				null, true, false, null, 100 ],
			[ 'Frecuencia',				'Frecuencia',		null, true, false, null, 100 ],
			[ 'Acumulada',				'Acumulada',		null, true, false, null, 100 ],
			[ 'grid_id',				'grid_id',			null, true, false, null, 100 ],
			[ 'ColPeriodo',				'ColPeriodo',		null, true, false, null, 100 ],
			[ 'Agrupador',				'Agrupador',		null, true, false, null, 100 ],
			[ 'Col',					'Col',				null, true, false, null, 100 ],
			[ 'Filtros',				'Filtros',			null, true, false, null, 100 ],
		];
	}

	//Scopes
	public function scopeTipo($q, $Tipo)
	{
		if($Tipo) return $q->where('Tipo', $Tipo);
		return $q;
	}

	public function grid()
	{
		return $this->belongsTo('\App\Models\EntidadGrid', 'grid_id');
	}

	public function valores($Anio = false)
	{
		$q = $this->hasMany('\App\Models\VariableValor', 'variable_id')->orderBy('Periodo');

		if(!$Anio) return $q;
		return $q->where('Periodo', '>=', ($Anio-1).'01')->where('Periodo', '<=', $Anio.'12');
	}

	public function proceso()
	{
		return $this->belongsTo('\App\Models\Proceso', 'proceso_id');
	}

	public function getDesagregables()
	{
		if($this->Tipo !== 'Calculado de Entidad') return [];
		return EntidadCampo::where('entidad_id', $this->grid->entidad_id)->where('Desagregable', 1)->orderBy('Alias')->get([ 'id', 'Columna', 'Alias', 'Tipo' ]);
	}

	public function getFiltrosAttribute($a)
	{
		
		$Filtros = json_decode($this->attributes['Filtros'], true);

		if(!is_array($Filtros)) $Filtros = [];

		return collect($Filtros)->transform(function($F){
			$F['val']   = $F['Valor'];
			return $F;
		});
	}

	
	public function setFiltrosAttribute($Filtros)
	{
		if(!is_null($Filtros)){
			foreach ($Filtros as &$F) { unset($F['campo']); };
			$this->attributes['Filtros'] = json_encode($Filtros);
		}
		
	}

	public function prepFiltros()
	{
		return $this->Filtros->map(function($F){
			$F['campo'] = EntidadCampo::find($F['campo_id']);
			return $F;
		});
	}

	public function getRutaAttribute()
	{
		return $this->proceso->Ruta;
	}


	//Desagregacion
	public function getDesagregated($PeriodoIni, $PeriodoFin, $desag_campos, $make_array = true)
	{
		$Grid = GridHelper::getGrid($this->grid_id);
        $q    = GridHelper::getQ($Grid->entidad);

        $this->Filtros = $this->prepFiltros();

        GridHelper::calcJoins($Grid);
        GridHelper::addJoins($Grid, $q);
        GridHelper::addFilters($this->Filtros, $Grid, $q);

        $ColPeriodo = H::getElm($Grid->columnas, $this->ColPeriodo);
		$ColPeriodoName = \DB::raw($ColPeriodo->campo->getColName($ColPeriodo['tabla_consec']));
		$ColCalculo = H::getElm($Grid->columnas, $this->Col);
		$ColCalculoName = $ColCalculo->campo->getColName($ColCalculo['tabla_consec']);

		$q->whereBetween($ColPeriodoName, [ $PeriodoIni, $PeriodoFin ]);

		GridHelper::getGroupedData($Grid, $q, [$ColPeriodoName], [ [ $ColCalculoName, $this->Agrupador ] ]);

		foreach ($desag_campos as $C) {
			//$Campo = EntidadCampo::where('id', $C['id'])->first();
			$CampoName = \DB::raw(CamposHelper::getColName('t0', $C['Columna']));

			$q->addSelect($CampoName);
			$q->groupBy($CampoName);
			$q->orderBy($CampoName);
		}


		$Data = collect(GridHelper::getData($Grid, $q, false, false, false));

		//dd($Data);

		$Data = $Data->transform(function($D){

			$ValFormat = H::formatVal($D[1],$this->TipoDato,$this->Decimales);

			$Arr = [ 'Periodo' => $D[0], 'Valor'   => intval($D[1]), 'val'     => $ValFormat ];

			$Groupers = [];
			for ($i=2; $i < count($D); $i++) { $Groupers[] = utf8_encode(trim($D[$i])); }
			$Arr['Groupers'] = join('|||', $Groupers);  

			return $Arr;
		})->groupBy('Groupers')->transform(function($G, $kG){

			$Arr = explode('|||', $kG);
			$Arr['Llave'] = implode(' - ', $Arr);
			$Arr['valores'] = [];
			$Arr['valor_total'] = 0;

			foreach ($G as $Row) {
				$Arr['valores'][$Row['Periodo']] = [ 'Valor' => $Row['Valor'], 'val' => $Row['val'] ];
				$Arr['valor_total'] += $Row['Valor'];
			}

			return $Arr;
		})->sortByDesc('valor_total');

		if($make_array) $Data = $Data->values();

		return $Data;
        //$q->toSql();
	}


	public function getVals($Anio = false, $CalcFrecs = true)
    {
        $Valores = $this->valores($Anio)->get();
        $Anio = $Anio ? $Anio : intval(date('Y'));
        $PeriodoMinValores = $Valores->min('Periodo');
        $PeriodoMin = is_null($PeriodoMinValores) ? (($Anio*100)+1) : $PeriodoMinValores;
        $PeriodoMax = ($Anio*100)+12;

        $PeriodosList = H::getPeriodos($PeriodoMin, $PeriodoMax);
       	$Periodos = array_fill_keys($PeriodosList, [ 'val' => null, 'Valor' => null ]);
       	$PeriodoAct = intval(date('Ym'));

       	foreach ($Valores as $v) {
    		$v->formatVal($this->TipoDato, $this->Decimales);
            $Periodos[$v->Periodo] = [ 'val' => $v->val, 'Valor' => $v->Valor ];
    	}

    	if($this->Acumulada == 'Si' AND $CalcFrecs){
    		$val_acum = 0;

    		foreach ($Periodos as $P => &$Vals) {

    			if(substr($P, -2) == '01') $val_acum = 0;

				if(is_null($Vals['Valor']) AND $P >= $PeriodoAct) continue;

				$val_acum += $Vals['Valor'];
				$val_acum_f = Helper::formatVal($val_acum,$this->TipoDato, $this->Decimales);
				$Vals['Valor'] = $val_acum;
				$Vals['val'] = $val_acum_f;
    		}

    	}

    	//if($this->id == 98){ dd($PeriodosList); }
    	//dd($this->Acumulada);
    	if($this->Acumulada == 'No' AND $this->Frecuencia > 1 AND $CalcFrecs){

    		$FixVals = null;
    		$Frecs = 0;

    		foreach ($Periodos as $P => &$Vals) {
    			
    			if($P < $PeriodoAct){

    				if(!is_null($Vals['Valor'])){

	    				$FixVals = $Vals;
	    				$Frecs = 0;

	    			}else if(!is_null($FixVals)){

	    				$Vals = $FixVals;
	    				$Frecs++;

	    				//if($Frecs >= ($this->Frecuencia-1)){ $FixVals = null; $Frecs = 0; } ASK
	    			}

    			}

	    	}
    	}
    	
    	return $Periodos;
        
    }

    public function scopeBuscar($q, $searchText)
	{
		return $q->where('Variable', 'LIKE', "%$searchText%")->select([
			'id', 'Variable AS Titulo', 'Descripcion', 'proceso_id', 'Filtros'
		]);
	}


}
