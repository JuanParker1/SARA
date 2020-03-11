<?php

namespace App\Models;

use App\Models\Core\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\IndicadorVariable;
use App\Models\IndicadorMeta;
use App\Models\Variable;
use App\Functions\FormulaParser;
use App\Functions\Helper;

class Indicador extends MyModel
{
    protected $table = 'sara_indicadores';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
	];
    protected $appends = ['Ruta'];

    use SoftDeletes;

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',				null, true, false, null, 100 ],
			//[ 'Ruta',					'Ruta',				null, true, false, null, 100 ],
			[ 'proceso_id',				'proceso_id',		null, true, false, null, 100 ],
			[ 'Indicador',				'Indicador',		null, true, false, null, 100 ],
			[ 'Definicion',				'Definición',		null, true, false, null, 100 ],
			[ 'Unidad',					'Unidad',			null, true, false, null, 100 ],
			[ 'TipoDato',				'TipoDato',			null, true, false, null, 100 ],
			[ 'Decimales',				'Decimales',		null, true, false, null, 100 ],
			[ 'Formula',				'Formula',			null, true, false, null, 100 ],
			[ 'Sentido',				'Sentido',			null, true, false, null, 100 ],
		];
	}

	public function proceso()
	{
		return $this->belongsTo('\App\Models\Proceso', 'proceso_id');
	}

	//Scope
	public function scopeProceso($q, $proceso_id)
	{
		return $q->where('proceso_id', $proceso_id);
	}


	//Calcular Variables
	public function calcVals($Anio, $mesIni = 1, $mesFin = 12)
	{
		$decimales = ($this->TipoDato == 'Porcentaje') ? $this->Decimales + 2 : $this->Decimales;

		//Variables
		if(!$this->variables){
			$this->variables = IndicadorVariable::indicador($this->id)->get();
			$desagregables = [];
			$desagregados = [];
			
			foreach ($this->variables as $V) {
				foreach ($V->variable->getDesagregables() as $campo) {
					if(in_array($campo['id'], [])){ //TODO poner desagregaciones posibles
						$desagregados[$campo['id']] = $campo;
					}else{
						$desagregables[$campo['id']] = $campo;
					};

					
				}
			}
			$this->desagregables = collect($desagregables)->values();
			$this->desagregados  = collect($desagregados)->values();
		}

		$this->metas     = IndicadorMeta::indicador($this->id)->year($Anio,$mesFin)->get();
		$def = [
			'mes' => 0, 'calculable' => true, 
			'Valor' => null, 'val' => null,
			'meta_Valor' => null, 'meta2_Valor' => null, 'meta_val' => null, 'cump' => null,
			'cump_porc' => null
		];
		$valores = array_fill_keys(Helper::getPeriodos((($Anio*100)+$mesIni),(($Anio*100)+$mesFin)), $def);

		foreach ($this->variables as $c) {
			if($c->Tipo == 'Variable'){
				$Var = Variable::where('id',$c->variable_id)->first();
				$c->valores = $Var->valores()->year($Anio,$mesIni,$mesFin)->get()->keyBy('Periodo')->transform(function($v) use ($Var){
					$v->formatVal($Var->TipoDato, $Var->Decimales);
					return $v;
				});
				$c->variable_name = $Var->Variable;
			}else if($c->Tipo == 'Indicador'){
				$Ind = self::where('id',$c->variable_id)->first();
				$c->valores = collect($Ind->calcVals($Anio,$mesIni,$mesFin));
				$c->variable_name = $Ind->Indicador;

			};
		};

		foreach ($valores as $target_per => &$v) {

			$v['mes'] = intval(substr($target_per, 4, 2));

			//Obtener Componentes
			$comp = [];
			foreach ($this->variables as $c) {
				if(!$v['calculable']) continue;
				$target_val = ($c->valores->has($target_per)) ? $c->valores[$target_per]['Valor'] : null;
				if(is_null($target_val)){ $v['calculable'] = false; continue; }
				$comp[$c->Letra] = $target_val;
			};
			$v['comp'] = $comp;

			//Calcular Formulas
			if($v['calculable']){
				$v['Valor'] = Helper::calcFormula( $this->Formula, $v['comp'], $decimales );
				$v['val']   = Helper::formatVal($v['Valor'], $this->TipoDato, $this->Decimales);
			};

			//Obtener metas
			if(!empty($this->metas)){
				$Meta = $this->metas->filter(function($m) use ($target_per){ return $m['PeriodoDesde'] <= $target_per; })->first();

				if($Meta){
					$v['meta_Valor'] = $Meta['Meta'];
					$v['meta_val'] = Helper::formatVal($Meta['Meta'], $this->TipoDato, $this->Decimales);
					if($this->Sentido == 'RAN'){
						$v['meta2_Valor'] = $Meta['Meta2'];
						$v['meta_val'] .= " - " . Helper::formatVal($Meta['Meta2'], $this->TipoDato, $this->Decimales);
					};
				}
			};

			//Evaluar Cumplimiento
			$v['cump']      = Helper::calcCump($v['Valor'], $v['meta_Valor'], $this->Sentido, 'bool', $v['meta2_Valor']);
			$v['cump_porc'] = Helper::calcCump($v['Valor'], $v['meta_Valor'], $this->Sentido, 'porc', $v['meta2_Valor']);
			$v['color']     = Helper::getIndicatorColor($v['cump_porc']);
		}


		return $valores;
	}

	public function getRutaAttribute()
	{
		return $this->proceso->Ruta;
	}


}
