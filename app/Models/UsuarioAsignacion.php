<?php

namespace App\Models;

use App\Models\Core\MyModel;

class UsuarioAsignacion extends MyModel
{
    protected $table = 'sara_usuarios_asignacion';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $with = ['usuario'];
	protected $primaryKey = 'id';
    protected $casts = [
	];
    protected $appends = [];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',				null, true, false, null, 100 ],
			[ 'usuario_id',				'usuario_id',		null, true, false, null, 100 ],
			[ 'nodo_id',				'nodo_id',			null, true, false, null, 100 ],
			[ 'perfil_id',				'perfil_id',		null, true, false, null, 100 ],
		];
	}

	public function usuario()
	{
		return $this->belongsTo('\App\Models\Usuario', 'usuario_id');
	}

	public function perfil()
	{
		return $this->belongsTo('\App\Models\Perfil', 'perfil_id');
	}

	public function proceso()
    {
        return $this->belongsTo('\App\Models\Proceso', 'nodo_id');
    }

	//Scope
	public function scopeNodo($q, $id)
	{
		return $q->where('nodo_id', $id);
	}
}
