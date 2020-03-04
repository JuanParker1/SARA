<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Comentario extends MyModel
{
    protected $table = 'sara_comentarios';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [];
    protected $appends = ['hace'];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',					'id',				null, true, false, null, 100 ],
			[ 'Entidad',			'Entidad',			null, true, false, null, 100 ],
			[ 'Entidad_id',			'Entidad_id',		null, true, false, null, 100 ],
			[ 'Grupo',				'Grupo',			null, true, false, null, 100 ],
			[ 'usuario_id',			'usuario_id',		null, true, false, null, 100 ],
			[ 'Comentario',			'Comentario',		null, true, false, null, 100 ],
			[ 'Op1',				'Op1',				null, true, false, null, 100 ],
			[ 'Op2',				'Op2',				null, true, false, null, 100 ],
			[ 'Op3',				'Op3',				null, true, false, null, 100 ],
			[ 'Op4',				'Op4',				null, true, false, null, 100 ],
			[ 'Op5',				'Op5',				null, true, false, null, 100 ],
			[ 'Estado',				'Estado',			null, true, false, null, 100 ],
		];
	}

	public function autor()
	{
		return $this->belongsTo('\App\Models\Usuario', 'usuario_id');
	}

	public function getHaceAttribute()
	{
		return $this->created_at->diffForHumans();
	}


	public function scopeEntidad($q, $Entidad)
	{
		return $q->where('Entidad', $Entidad[0])->where('Entidad_id', $Entidad[1]);
	}
}
