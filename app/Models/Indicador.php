<?php

namespace App\Models;

use App\Models\Core\MyModel;
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

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',				null, true, false, null, 100 ],
			//[ 'Ruta',					'Ruta',				null, true, false, null, 100 ],
			[ 'proceso_id',				'proceso_id',	null, true, false, null, 100 ],
			[ 'Indicador',				'Indicador',		null, true, false, null, 100 ],
			[ 'Definicion',				'Definición',		null, true, false, null, 100 ],
			[ 'Unidad',					'Unidad',			null, true, false, null, 100 ],
			[ 'TipoDato',				'TipoDato',		null, true, false, null, 100 ],
			[ 'Decimales',				'Decimales',		null, true, false, null, 100 ],
			[ 'Formula',				'Formula',			null, true, false, null, 100 ],
			[ 'Sentido',				'Sentido',			null, true, false, null, 100 ],
		];
	}

	public function proceso()
	{
		return $this->belongsTo('\App\Models\Proceso', 'proceso_id');
	}


	//Calcular Variables
	public function calcVals($Anio, $mesIni = 1, $mesFin = 12)
	{
		$decimales = ($this->TipoDato == 'Porcentaje') ? $this->Decimales + 2 : $this->Decimales;

		$this->variables = IndicadorVariable::indicador($this->id)->get();
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
				$parser = new FormulaParser($this->Formula, $decimales);
				$parser->setVariables($v['comp']);
				$res = $parser->getResult();
				if($res && $res[0] == 'done' && is_numeric($res[1])){
					$v['Valor'] = $res[1];
					$v['val'] = Helper::formatVal($res[1], $this->TipoDato, $this->Decimales);
				}
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
			if(!is_null($v['Valor']) AND !is_null($v['meta_Valor'])){
				if($this->Sentido == 'ASC'){
					$v['cump'] = ($v['Valor'] >= $v['meta_Valor']) ? 1:0;
					$cump_porc = ($v['cump'] == 1) ? 1 : $v['Valor']/($v['meta_Valor'] ?: 1);
				}else if($this->Sentido == 'DES'){
					$v['cump'] = ($v['Valor'] <= $v['meta_Valor']) ? 1:0;
					$cump_porc = ($v['cump'] == 1) ? 1 : ((1 - ( ( $v['Valor'] - $v['meta_Valor'] ) / $v['meta_Valor'] )) ?: 1);
				}else if($this->Sentido == 'RAN' AND !is_null($v['meta2_Valor'])){
					$v['cump'] = ($v['Valor'] >= $v['meta_Valor'] AND $v['Valor'] <= $v['meta2_Valor']) ? 1:0;
					if($v['cump'] == 1){
						$cump_porc = 1;
					}else if($v['Valor'] < $v['meta_Valor']){
						$cump_porc = $v['Valor']/($v['meta_Valor'] ?: 1);
					}else if($v['Valor'] > $v['meta2_Valor']){
						$cump_porc = ((1 - ( ( $v['Valor'] - $v['meta_Valor'] ) / $v['meta_Valor'] )) ?: 1);
					}
				};

				$v['cump_porc'] = round(max($cump_porc,0),3);
			};

			$v['color'] = Helper::getIndicatorColor($v['cump_porc']);
		}


		return $valores;
	}

	public function getRutaAttribute()
	{
		return $this->proceso->Ruta;
	}


}
