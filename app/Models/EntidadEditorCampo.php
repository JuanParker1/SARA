<?php

namespace App\Models;

use App\Models\Core\MyModel;

class EntidadEditorCampo extends MyModel
{
    protected $table = 'sara_entidades_editores_campos';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    	'Visible' => 'bool'
    ];
    protected $appends = [
    ];


    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			['id',			'id',	null, true, false, null, 100 ],
			['editor_id',	'editor_id',	null, true, false, null, 100 ],
			['seccion_id',	'seccion_id',	null, true, false, null, 100 ],
			['Indice',		'Indice',	null, true, false, null, 100 ],
			['Etiqueta',	'Etiqueta',	null, true, false, null, 100 ],
			['campo_id',	'campo_id',	null, true, false, null, 100 ],
			['Tipo',		'Tipo',	null, true, false, null, 100 ],
			['Ancho',		'Ancho',	null, true, false, null, 100 ],
			['Visible',		'Visible',	null, true, false, null, 100 ],
			['Op1',			'Op1',	null, true, false, null, 100 ],
			['Op2',			'Op2',	null, true, false, null, 100 ],
			['Op3',			'Op3',	null, true, false, null, 100 ],
			['Op4',			'Op4',	null, true, false, null, 100 ],
			['Op5',			'Op5',	null, true, false, null, 100 ],
		];
	}


	public function scopeEditor($query,$id)
	{
		return $query->where('editor_id', $id);
	}
}