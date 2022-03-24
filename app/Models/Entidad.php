<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Entidad extends MyModel
{
    protected $table = 'sara_entidades';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'config' => 'array'
    ];
    protected $appends = ['Ruta'];


    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',					null, true, false, null, 100 ],
			[ 'bdd_id',					'bdd_id',				null, true, false, null, 100 ],
			[ 'proceso_id',				'proceso_id',			null, true, false, null, 100 ],
			[ 'Ruta',					'Ruta',					null, true, true, null, 100 ],
			[ 'Nombre',					'Nombre',				null, true, true, null, 100 ],
			[ 'Tipo',					'Tipo',					null, true, false, null, 100 ],
			[ 'Tabla',					'Tabla',				null, true, false, null, 100 ],
			[ 'campo_llaveprim',		'campo_llaveprim',		null, true, false, null, 100 ],
			[ 'campo_orderby',			'campo_orderby',		null, true, false, null, 100 ],
			[ 'campo_orderbydir',		'campo_orderbydir',		null, true, false, null, 100 ],
			[ 'max_rows',				'max_rows',				null, true, false, null, 100 ],
			[ 'config',					'config',				null, true, false, null, 100 ],
		];
	}

	//Scopes
	public function scopeBdd($q, $bdd_id)
	{
		return $q->where('bdd_id', $bdd_id);
	}

	public function scopeTipo($q, $tipo)
	{
		return $q->where('Tipo', $tipo);
	}

	//Relaciones
	public function campos()
	{
		return $this->hasMany('\App\Models\EntidadCampo', 'entidad_id')->orderBy('Indice', 'ASC');
	}

	public function restricciones()
	{
		return $this->hasMany('\App\Models\EntidadRestriccion', 'entidad_id');
	}

	public function bdd()
	{
		return $this->belongsTo('\App\Models\BDD', 'bdd_id');
	}

	public function proceso()
	{
		return $this->belongsTo('\App\Models\Proceso', 'proceso_id');
	}

	//funciones
	public function getTableName()
	{
		$Bdd = $this->bdd()->first();
		return \App\Functions\GridHelper::getTableName($this->Tabla, $Bdd->Op3);
	}

	public function getConfigAttribute($Config)
	{
		$Default = [
			'campo_desc1'   => null,
			'campo_desc2'   => null,
			'campo_desc3'   => null,
			'campo_desc4'   => null,
			'campo_desc5'   => null,
			'search_minlen' => 2,
			'search_elms'   => 5,
		];
		
		if(gettype($Config) == 'string') $Config = json_decode($Config);
		if(gettype($Config) == 'object') $Config = (array) $Config;
		$Config = is_null($Config) ? $Default : array_merge($Default, $Config);

		return $Config;
	}

	public function getRutaAttribute()
	{
		return $this->proceso->Ruta;
	}
}
