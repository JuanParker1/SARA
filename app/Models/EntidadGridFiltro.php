<?php

namespace App\Models;

use App\Models\Core\MyModel;
use Carbon\Carbon;
use App\Functions\Helper;

class EntidadGridFiltro extends MyModel
{
    protected $table = 'sara_entidades_grids_filtros';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Locked' => 'boolean'
    ];
    protected $with = [];
    protected $appends = ['Valor', 'campo','default','val', 'default_comparador'];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',			'id',			null, true,  	false, null, 100 ],
			[ 'grid_id',	'grid_id',		null, true,  	false, null, 100 ],
			[ 'columna_id',	'columna_id',	null, true,  	false, null, 100 ],
			[ 'Indice',		'Indice',		null, true,  	false, null, 100 ],
			[ 'Comparador',	'Comparador',	null, true,  	false, null, 100 ],
			[ 'Valor',		'Valor',		null, false,  	false, null, 100 ],
			[ 'Op1',		'Op1',			null, false,  	false, null, 100 ],
			[ 'Op2',		'Op2',			null, false,  	false, null, 100 ],
			[ 'Op3',		'Op3',			null, false,  	false, null, 100 ],
		];
	}


	//Scopes
	public function scopeGrid($query,$id)
	{
		return $query->where('grid_id', $id);
	}


	//Relations
	public function columna()
	{
		return $this->belongsTo('\App\Models\EntidadGridColumna', 'columna_id');
	}

	public function getCampoAttribute()
	{
		return \App\Models\EntidadCampo::where('id', $this->columna->campo_id)->first();
	}


	//Accesor
	public function getValorAttribute()
	{
		$Valor = array_key_exists('Valor', $this->attributes) ? $this->attributes['Valor'] : null;

		if($this->campo->Tipo == 'Lista'){
			if($Valor){
				$Valor = json_decode($Valor);
			}
		};

		if(in_array($this->campo->Tipo, ['Entero', 'Decimal', 'Dinero'])){
			$Valor = floatval($Valor);
		};

		return $Valor;
	}

	public function getDefaultAttribute()
	{
		$Valor = $this->Valor;

        if($this->campo->Tipo == 'Fecha'){
            $Date  = Carbon::parse($Valor)->startOfDay();
            $Valor = $Date->format('c');
        };

        if($this->Comparador == 'lista'){
        	if(is_string($Valor)) $Valor = is_null($Valor) ? null : json_decode($Valor, true);
        };


        $Valor = Helper::getSystemVariable($Valor);

        return $Valor;
	}

	public function getValAttribute(){ return $this->default; }

	public function getDefaultComparadorAttribute()
	{
		return $this->Comparador;
	}

	//Eventos
	public function prepSave($F)
	{
		if($this->Comparador == 'lista') {
    		if(is_array($F['Valor']) AND !empty($F['Valor'])){
    			$this->Valor = json_encode($F['Valor']);
    		}else{
    			$this->Valor = null;
    		};
    	};
	}



	
	public static function boot()
    {
        parent::boot();

        self::creating(function($model){
        	
        	//dd($model);

        });

        self::created(function($model){
        	$model->aaa = 1;
        });

    }


}
