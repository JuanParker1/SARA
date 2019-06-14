<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BDDFavoritos extends Model
{
    protected $table = 'sara_bdds_favoritos';
	protected $guarded = ['id'];
	protected $hidden = [];
    protected $casts = [];
    protected $appends = [];


    public function columns()
	{

		$SiNo = [ 'N' => 'No', 'S' => 'Si' ];

		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',				'id',			null, true, false, null, 100 ],
			[ 'bdd_id',			'bdd_id',		null, true, false, null, 100 ],
			[ 'usuario_id',		'usuario_id',	null, true, false, null, 100 ],
			[ 'Carpeta',		'Carpeta',		null, true, false, null, 100 ],
			[ 'Nombre',			'Nombre',		null, true, true,  null, 100 ],
			[ 'Consulta',		'Consulta',		null, true, false, null, 100 ],
			[ 'EjecutarAutom',	'Ejecutar Automáticamente',	'select', true, false, false, 100, [ 'options' => $SiNo ] ],
		];
	}


	//Scopes
	public function scopeMine($query)
	{
		$token = request()->header('token');
		$Usuario = new Usuario();
		$Usuario = $Usuario->fromToken($token);
		return $query->where('usuario_id', $Usuario->id);
	}

	public function scopeBddid($query,$id)
	{
		return $query->where('bdd_id', $id);
	}


}
