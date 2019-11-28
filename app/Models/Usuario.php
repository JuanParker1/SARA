<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Hash;
use DB;
use Crypt;

class Usuario extends Model
{
    protected $table = 'sara_usuarios';
	protected $guarded = ['id'];
	protected $hidden = ['Password'];
	protected $primaryKey = 'id';
    protected $casts = [
        'isGod' => 'boolean',
    ];

	public function authenticate($Email, $Password)
    {
        $User = Usuario::where('Email', $Email)->first();
        if (!$User OR !Hash::check($Password, $User->Password)) {
            return false;
        }
        return $User;
    }



    public function getSecciones()
    {
    	if($this->isGod){
            $query = "SELECT se.*, 100 AS Level
                        FROM sara_secciones se 
                        WHERE se.Estado = 'A' 
                        ORDER BY se.Orden";
        }else{
            $query = "SELECT se.*, s.Level
                        FROM `compras_security` s
                            JOIN sara_secciones se ON (se.id = s.Seccion_id) 
                        WHERE s.Perfil_id = $this->Perfil_id 
                        AND se.Estado = 'A' 
                        ORDER BY se.Orden";
        }

        $Secciones = DB::select($query);
        $this->Secciones = $Secciones;
    }

    /*public function apps()
    {
        return $this->belongsToMany('App\Models\Apps', 'sara_usuario_apps', 'usuario_id', 'app_id')->withPivot('favorito');
    }*/

    public function getApps()
    {
        /*$this->Apps = $this->apps()->get()->transform(function($A){
            $A['favorito'] = ($A['pivot']['favorito'] == 1);
            return $A;
        });*/
        $this->Apps = \App\Models\Apps::all();
    }



    public function fromToken($token)
    {
        $Email = Crypt::decrypt($token);
        $Usuario = Usuario::where('Email', $Email)->first();
        return $Usuario;
    }


}
