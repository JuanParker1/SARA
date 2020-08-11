<?php

namespace App\Models;

use App\Models\Core\MyModel;

class BDDListas extends MyModel
{
    protected $table = 'sara_bdds_listas';
	protected $guarded = ['id'];

	public function columns()
	{

		//$SiNo = [ 'N' => 'No', 'S' => 'Si' ];

		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'bdd_id',			'bdd_id',				null, true,  false,  null, 100 ],
			[ 'Nombre',			'Nombre',				null, true,  false,  null, 100 ],
			[ 'Indice',			'Tabla Indice',			null, true,  false,  null, 100 ],
			[ 'IndiceCod',		'Códigos',				null, false, false,  null, 50 ],
			[ 'IndiceDes',		'Descripciónes',		null, false, false,  null, 50 ],
			[ 'Detalle',		'Tabla Detalles',		null, false, false,  null, 50 ],
			[ 'Llave',			'Llave',				null, false, false,  null, 50 ],
			[ 'DetalleCod',		'Det. Códigos',			null, false, false,  null, 50 ],
			[ 'DetalleDes',		'Det. Descripciónes',	null, false, false,  null, 50 ],
		];
	}

	public function bdd()
	{
		return $this->belongsTo('App\Models\BDD');
	}

	//Scopes
	public function scopeBddid($query,$id)
	{
		return $query->where('bdd_id', $id);
	}

}
