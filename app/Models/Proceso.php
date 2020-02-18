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
			[ 'Tipo',					'Tipo',			null, true, false, null, 100 ],
			[ 'padre_id',				'padre_id',			null, true, false, null, 100 ],
			[ 'responsable_id',			'responsable_id',	null, true, false, null, 100 ],
			[ 'CDC',					'CDC',				null, true, false, null, 100 ],
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

	public function getFullrutaAttribute()
	{
		if(is_null($this->padre_id)){
			return $this->Proceso;
		}else{
			return $this->padre->fullruta .'\\'. $this->Proceso;
		}
	}

}
