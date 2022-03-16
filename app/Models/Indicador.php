<?php

namespace App\Models;

use App\Models\Core\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\IndicadorVariable;
use App\Models\IndicadorMeta;
use App\Models\IndicadorValor;
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
    	'config' => 'array'
	];
	protected $with = ['proceso'];
    protected $appends = ['Ruta'];

    use SoftDeletes;

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',					null, true, false, null, 100 ],
			//[ 'Ruta',					'Ruta',					null, true, false, null, 100 ],
			[ 'proceso_id',				'proceso_id',			null, true, false, null, 100 ],
			[ 'Indicador',				'Indicador',			null, true, false, null, 100 ],
			[ 'Definicion',				'Definición',			null, true, false, null, 100 ],
			[ 'TipoDato',				'TipoDato',				null, true, false, null, 100 ],
			[ 'Decimales',				'Decimales',			null, true, false, null, 100 ],
			[ 'Formula',				'Formula',				null, true, false, null, 100 ],
			[ 'Sentido',				'Sentido',				null, true, false, null, 100 ],
			[ 'FrecuenciaAnalisis',		'FrecuenciaAnalisis',	null, true, false, null, 100 ],
			[ 'config',					'config',				null, true, false, null, 100 ],
		];
	}

	public function proceso()
	{
		return $this->belongsTo('\App\Models\Proceso', 'proceso_id');
	}

	public function metas()
	{
		return $this->hasMany('\App\Models\IndicadorMeta', 'indicador_id');
	}

	public function componentes()
	{
		return $this->hasMany('\App\Models\IndicadorVariable', 'indicador_id');
	}

	public function valorescache()
	{
		return $this->hasMany('\App\Models\IndicadorValor', 'indicador_id');
	}

	//Scope
	public function scopeProceso($q, $proceso_id)
	{
		return $q->where('proceso_id', $proceso_id);
	}


	//Calcular Variables
	public function calcVals($Anio = false, $mesIni = 1, $mesFin = 12)
	{
		
		if(!$Anio) $Anio = intval(date("Y"));
		$PeriodoMin = intval(($Anio*100)+$mesIni);
        $PeriodoMax = intval(($Anio*100)+$mesFin);

        //$IndValores = IndicadorValor::indicador($this->id)->whereBetween('Periodo', [$PeriodoMin, $PeriodoMax])->get()->keyBy('Periodo');
        //if(!empty($IndValores)) return $IndValores->toArray();

        $def = [
			'mes' => 0, 'calculable' => true, 
			'Valor' => null, 'val' => null,
			'meta_Valor' => null, 'meta2_Valor' => null, 'meta_val' => null, 'cump' => null,
			'cump_porc' => null
		];
		$valores = array_fill_keys(Helper::getPeriodos($PeriodoMin,$PeriodoMax), $def);

		$decimales = ($this->TipoDato == 'Porcentaje') ? $this->Decimales + 2 : $this->Decimales;

		//Variables
		if(!$this->variables){
			$this->variables = IndicadorVariable::with([ 'variable.column' ])->indicador($this->id)->get();
			$desagregables = [];
			$desagregados = [];
			
			foreach ($this->variables as $V) {
				if($V->variable){
					foreach ($V->variable->getDesagregables() as $campo) {
						if(in_array($campo['id'], [])){ //TODO poner desagregaciones posibles
							$desagregados[$campo['id']] = $campo;
						}else{
							$desagregables[$campo['id']] = $campo;
						};
					}
				}
				
			}
			$this->desagregables = collect($desagregables)->values();
			$this->desagregados  = collect($desagregados)->values();
		}

		$this->metas     = IndicadorMeta::indicador($this->id)->year($Anio,$mesFin)->get();
		
		//Pepare comments
		$Comments = \App\Models\Comentario::entidad(['Indicador', $this->id])->periododesde($PeriodoMin)->periodohasta($PeriodoMax)->get();


		foreach ($this->variables as $c) {
			if($c->Tipo == 'Variable'){
				$Var = Variable::where('id',$c->variable_id)->first();

				if(!$Var) dd($this);

				$c->valores = collect($Var->getVals(false));
				$c->variable_name = $Var->Variable;

			}else if($c->Tipo == 'Indicador'){
				$Ind = self::where('id',$c->variable_id)->first();
				$c->valores = collect($Ind->calcVals($Anio,$mesIni,$mesFin));
				$c->variable_name = $Ind->Indicador;

			};
		};

		//Prepare meta_variable
		if($this->config['meta_tipo'] == 'variable' AND !is_null($this->config['meta_elemento_id'])){
			$this->meta_variable = Variable::where('id', $this->config['meta_elemento_id'])->first();
			$this->meta_variable->valores = collect($this->meta_variable->getVals($Anio));
		};


		foreach ($valores as $target_per => &$v) {

			$v['mes'] = intval(substr($target_per, 4, 2));
			$v['Periodo'] = $target_per;
			$v['indicador_id'] = $this->id;

			//Obtener Componentes
			$comp = [];
			foreach ($this->variables as $c) {
				if(!$v['calculable']) continue;
				$target_val  = ($c->valores->has($target_per)) ? $c->valores[$target_per]['Valor'] : null;
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
			if(!empty($this->metas) AND $this->config['meta_tipo'] == 'fija'){
				$Meta = $this->metas->filter(function($m) use ($target_per){ return $m['PeriodoDesde'] <= $target_per; })->first();

				if($Meta){
					$v['meta_Valor'] = $Meta['Meta'];
					$v['meta_val'] = Helper::formatVal($Meta['Meta'], $this->TipoDato, $this->Decimales);
					if($this->Sentido == 'RAN'){
						$v['meta2_Valor'] = $Meta['Meta2'];
						$v['meta_val'] .= " - " . Helper::formatVal($Meta['Meta2'], $this->TipoDato, $this->Decimales);
					};
				}else{
					$v['calculable'] = false;
				}
			};

			//Obtener metas de variable
			if($this->config['meta_tipo'] == 'variable' AND !is_null($this->config['meta_elemento_id'])){
				$meta_desfase = ($this->config['meta_desfase'] ?: 0);
				$target_per_desf = Helper::periodoAdd($target_per, $meta_desfase);
				

				$val = $this->meta_variable->valores->get($target_per_desf);
				if(is_null($val)){
					$val = ['Valor' => null, 'val' => null];
				}
				$v['meta_Valor'] = $val['Valor'];
				$v['meta_val']   = $val['val'];
			};

			//Evaluar Cumplimiento
			$v['cump']      = Helper::calcCump($v['Valor'], $v['meta_Valor'], $this->Sentido, 'bool', $v['meta2_Valor']);
			$v['cump_porc'] = Helper::calcCump($v['Valor'], $v['meta_Valor'], $this->Sentido, 'porc', $v['meta2_Valor']);
			$v['color']     = Helper::getIndicatorColor($v['cump_porc']);
			
			//Agregar comentarios
			$ComentariosPer = $Comments->filter(function($c) use ($target_per){ return $c['Op1'] == $target_per; });
			$v['comentarios_comentarios'] = $ComentariosPer->filter(function($c){ return $c['Grupo'] == 'Comentario'; })->count();
			$v['comentarios_acciones']    = $ComentariosPer->filter(function($c){ return $c['Grupo'] == 'Accion'; })->count();
			$v['comentarios_total']       = $ComentariosPer->count();


			/*$IndVal = IndicadorValor::firstOrNew([
				'indicador_id' => $v['indicador_id'],
				'Periodo'      => $v['Periodo']
			]);
			$IndVal->fill($v);
			$IndVal->save();*/
		}
		
		return $valores;
	}

	public function getRutaAttribute()
	{
		if(is_null($this->proceso)) return null;
		return $this->proceso->Ruta;
	}

	public function getConfigAttribute($Config)
	{
		$Default = [
			'meta_tipo' => 'fija',
			'meta_elemento_id' => null,
			'meta_desfase' => 0,
		];
		
		if(gettype($Config) == 'string') $Config = json_decode($Config);
		if(gettype($Config) == 'object') $Config = (array) $Config;
		$Config = is_null($Config) ? $Default : array_merge($Default, $Config);

		return $Config;
	}

	public function scopeBuscar($q, $searchText)
	{
		return $q->where('Indicador', 'LIKE', "%$searchText%")->select([
			'id', 'Indicador AS Titulo', 'Definicion', 'proceso_id'
		]);
	}

	public function getVariables()
	{
		$variables_ids = [];

		foreach ($this->componentes as $c) {
			if($c->Tipo == 'Variable') $variables_ids[] = $c->variable_id;
			if($c->Tipo == 'Indicador'){
				$variables_ids = array_merge($variables_ids, (new self)::find($c->variable_id)->getVariables());
			}
		};

		return $variables_ids;
	}

}
