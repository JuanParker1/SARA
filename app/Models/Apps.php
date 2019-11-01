<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Apps extends MyModel
{
    protected $table = 'sara_apps';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [
    ];
    protected $appends = ['textcolor'];

    public function columns()
    {
        //Name, Desc, Type, Required, Unique, Default, Width, Options
        return [
            [ 'id',                     'id',                   null, true, false, null, 100 ],
            [ 'Titulo',                 'Titulo',               null, true, false, null, 100 ],
            [ 'Desc',                   'Desc',                 null, true, true, null, 100 ],
            [ 'Slug',                   'Slug',                 null, true, true, null, 100 ],
            [ 'Icono',                 'Icono',               null, true, true, null, 100 ],
            [ 'Color',                 'Color',               null, true, true, null, 100 ],
            [ 'Navegacion',            'Navegacion',               null, true, true, null, 100 ],
            [ 'ToolbarSize',           'ToolbarSize',               null, true, true, null, 100 ],
        ];
    }

    public function pages()
    {
        return $this->hasMany('\App\Models\AppPages', 'app_id')->orderBy('Indice', 'ASC');
    }

    public function getTextcolorAttribute()
    {
    	$hexcolor = $this->Color;
    	$r = hexdec(substr($hexcolor, 1, 2));
	    $g = hexdec(substr($hexcolor, 3, 2));
	    $b = hexdec(substr($hexcolor, 5, 2));
	    $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
	    return ($yiq > 127.5) ? 'black' : 'white';
    }

}
