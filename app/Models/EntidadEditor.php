<?php

namespace App\Models;

use App\Models\Core\MyModel;
use App\Functions\Helper;
use App\Functions\ConnHelper;
use App\Functions\EntidadHelper;
use App\Functions\CamposHelper;

use Carbon\Carbon;

class EntidadEditor extends MyModel
{
    protected $table = 'sara_entidades_editores';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Secciones' => 'array'
    ];
    protected $appends = [];


    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',			'id',			null, true,  false, null, 100 ],
			[ 'entidad_id',	'entidad_id',	null, true,  false, null, 100 ],
			[ 'Titulo',		'Titulo',		null, true,  false, null, 100 ],
			[ 'Ancho',		'Ancho',		null, true,  false, null, 100 ],
			[ 'Secciones',	'Secciones',	null, true,  false, null, 100 ],
		];
	}


	public function scopeEntidad($query,$id)
	{
		return $query->where('entidad_id', $id);
	}

	//Relaciones
	public function entidad()
	{
		return $this->belongsTo('\App\Models\Entidad', 'entidad_id');
	}
	
	public function campos()
	{
		return $this->hasMany('\App\Models\EntidadEditorCampo', 'editor_id')->orderBy('Indice', 'ASC');
	}



	//Funciones
	public function prepFields($Config, $Obj = null)
	{
		$Config['campos'] = collect($Config['campos']);

		$this->primary_key_val = is_null($Obj) ? null : $Obj['id'];

		foreach ($this->campos as $F) {
			
			$ConfigField = $Config['campos']->get($F->id);
			if($ConfigField){
				$TipoValor   = $ConfigField['tipo_valor'];
			}else{
				$TipoValor = "";
			}
			

			$TipoCampo   = $F->campo->Tipo;
			
			$Valor       = null;
			$primary_key = ($F->campo->id == $this->entidad->campo_llaveprim);

			//Definir el valor
			if($F->campo->Defecto !== ""){
				$Defecto = Helper::getSystemVariable($F->campo->Defecto);
				$Valor = $Defecto;
			};

			if($TipoValor == 'Columna'){
				if(array_key_exists($ConfigField['columna_id'], $Obj)) $Valor = $Obj[$ConfigField['columna_id']]['val'];
			};

			$F->val = $Valor;
			
			if($TipoCampo == 'Entidad'){
				if($F->val) $F->selectedItem = EntidadHelper::searchElms($F->campo->Op1, $F->val);
			};

			if($TipoCampo == 'Periodo'){
				$F->val = CamposHelper::prepDato($F->campo, $F->val);
				if($F->val){
					$TC = CamposHelper::getTipos();
					$relatives = array_map(function($rel){ return $rel[0]; }, $TC['Periodo']['Relatives']);
		            if(in_array($F->val, $relatives)){
		                $Date = Carbon::parse($F->val)->day(1);
		            }else{
		                $Date = Carbon::createFromFormat($F->campo['Op4'], $F->val)->day(1);
		            }
		         
		            $F->val = $Date->toDateString();
				}   
			};

			if($TipoCampo == 'Booleano'){
				$F->val = ( $F->val == $F->campo['Op4'] ) ? $F->val : $F->campo['Op5'];
			};

			if($TipoCampo == 'Entero'){  $F->val = intval($F->val); };
			if($TipoCampo == 'Decimal'){ $F->val = floatval($F->val); };

			if($TipoCampo == 'ListaAvanzada'){
				$F->campo->opciones = ConnHelper::getListaValores($F->campo['Config']['lista_id'], $F->campo['Config']['indice_cod']);
				
				//Adicional
				if($F->campo['Op4'] == 'AddDate'){

					if(!in_array($F->val, $F->campo->opciones->pluck('value')->toArray() )){
						$F->val_aux = $F->val;
						$F->val = '_SELECT_DATE_';
					};
				}

			}

			//Definir Editable
			//$F->Editable = $F->Editable;
			if($primary_key) $F->Editable = false;

			//Definir Requerido
			$F->Requerido = $F->campo->Requerido;
		};
	}


}
