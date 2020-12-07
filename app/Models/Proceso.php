<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Proceso extends MyModel
{
    protected $table = 'sara_procesos';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $with = [];
	protected $primaryKey = 'id';
    protected $casts = [
	];
    protected $appends = [];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',				null, true, false, null, 100 ],
			[ 'Proceso',				'Proceso',			null, true, false, null, 100 ],
			[ 'Tipo',					'Tipo',				null, true, false, null, 100 ],
			[ 'padre_id',				'padre_id',			null, true, false, null, 100 ],
			[ 'responsable_id',			'responsable_id',	null, true, false, null, 100 ],
			[ 'CDC',					'CDC',				null, true, false, null, 100 ],
			[ 'Ruta',					'Ruta',				null, true, false, null, 100 ],
			[ 'Introduccion',			'Introduccion',		null, true, false, null, 100 ],
		];
	}

	public function scopeEmpresa($q)
	{
		return $q->whereNull('padre_id');
	}

	public function subprocesos()
	{
		return $this->hasMany('\App\Models\Proceso', 'padre_id')->orderBy('Proceso', 'ASC');
	}

	public function padre()
	{
		return $this->belongsTo('\App\Models\Proceso', 'padre_id');
	}

	public function indicadores()
	{
		return $this->hasMany('\App\Models\Indicador', 'proceso_id')->orderBy('Indicador');
	}

	public function asignaciones()
	{
		return $this->hasMany('\App\Models\UsuarioAsignacion', 'nodo_id');
	}

	public function getFullrutaAttribute()
	{
		if(is_null($this->padre_id)){
			return $this->Proceso;
		}else{
			return $this->padre->fullruta .'\\'. $this->Proceso;
		}
	}

	public function recolectar(&$Arr)
	{
		$Arr[] = $this;

		foreach ($this->subprocesos as $sP) {
			$sP->recolectar($Arr);
		}
	}

	public function recolectarUp(&$Arr)
	{
		if(!is_null($this->padre_id)){
			$Padre = $this->padre()->first();
			if(!in_array($Padre->id, $Arr)){
				$Arr[] = $Padre->id;
				$Padre->recolectarUp($Arr);
			}
			
		}
	}


	public function getEquipo()
	{
		$this->equipo = $this->asignaciones->groupBy('perfil_id')->values()->sortBy(function ($Perfil) {
		    return $Perfil[0]['perfil']['Orden'];
		});
	}

	public function getBg()
	{
		$bg_url = "img/procesos_bgs/{$this->id}.jpg";

		if(file_exists($bg_url)){
			$this->Bg = $bg_url.'?'.$this->updated_at->timestamp;
		}else{
			$this->Bg = 'img/bg_data1.jpg';
		}

		
	}


	//Eventos
	public static function boot()
    {
		parent::boot();

		self::saved(function($model){
			set_time_limit (5 * 60);
			$Procesos = parent::all();

			foreach ($Procesos as $P) {
				$Ruta = $P->fullruta;
				if($Ruta !== $P->Ruta){
					$P->Ruta = $Ruta;
					$P->save();
				};
			};

        });

    }

}
