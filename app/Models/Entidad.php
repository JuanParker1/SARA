<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    protected $table = 'sara_entidades';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    ];
    protected $appends = [];


    public function columns()
	{

		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',					null, true, false, null, 100 ],
			[ 'bdd_id',					'bdd_id',				null, true, false, null, 100 ],
			[ 'Nombre',					'Nombre',				null, true, true, null, 100 ],
			[ 'Tipo',					'Tipo',					null, true, false, null, 100 ],
			[ 'Tabla',					'Tabla',				null, true, false, null, 100 ],
			[ 'campo_llaveprim',		'campo_llaveprim',		null, true, false, null, 100 ],
			[ 'campo_desc1',			'campo_desc1',			null, true, false, null, 100 ],
			[ 'campo_desc2',			'campo_desc2',			null, true, false, null, 100 ],
			[ 'campo_desc3',			'campo_desc3',			null, true, false, null, 100 ],
			[ 'campo_orderby',			'campo_orderby',		null, true, false, null, 100 ],
			[ 'campo_orderbydesc',		'campo_orderbydesc',	null, true, false, null, 100 ],
			[ 'max_rows',				'max_rows',	null, true, false, null, 100 ],
		];
	}

	//RElaciones
	public function campos()
	{
		return $this->hasMany('\App\Models\EntidadCampo', 'entidad_id')->orderBy('Indice', 'ASC');
	}

	public function bdd()
	{
		return $this->belongsTo('\App\Models\BDD', 'bdd_id');
	}

	//funciones
	public function getTableName()
	{
		$Bdd = $this->bdd()->first();
		return \App\Functions\CamposHelper::getTableSchema($this->Tabla, $Bdd->Op3);
	}
}
