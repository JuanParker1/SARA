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
    protected $appends = [ 'avatar' ];
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
            $query = "SELECT se.*, 5 AS Level
                        FROM sara_secciones se 
                        WHERE 1 = 1 
                            AND se.Estado = 'A'  
                        ORDER BY se.Orden";
        }else{
            $query = "SELECT se.id, se.Seccion, se.Orden, se.Icono, MAX(ps.Level) as Level
                        FROM sara_usuarios_asignacion ua  
                            JOIN sara_perfiles_secciones ps ON ( ua.perfil_id = ps.perfil_id ) 
                            JOIN sara_secciones se ON ( se.id = ps.seccion_id ) 
                        WHERE 1 = 1 
                            AND ua.usuario_id = {$this->id}
                            AND se.Estado = 'A'  
                        GROUP BY se.id, se.Seccion, se.Orden, se.Icono 
                        ORDER BY se.Orden";
        }

        $Secciones = DB::select($query);
        $this->Secciones = $Secciones;
    }

    /*public function apps()
    {
        return $this->belongsToMany('App\Models\Apps', 'sara_usuario_apps', 'usuario_id', 'app_id')->withPivot('favorito');
    }*/

    public function getApps($withPages = false)
    {
        
        //Obtener los procesos
        $ProcesosAsignados = \App\Models\UsuarioAsignacion::where('usuario_id', $this->id)->get(['nodo_id'])->pluck('nodo_id');
        $Procesos = \App\Models\Proceso::whereIn('id', $ProcesosAsignados)->get();
        $MyProcesos = [];
        $ParentProcesosIds = [];
        
        foreach ($Procesos as $P){
            $P->recolectar($MyProcesos);
            $P->recolectarUp($ParentProcesosIds);
        }

        $MyProcesosIds = collect($MyProcesos)->pluck('id')->toArray();
        $MyProcesosIds = array_merge($MyProcesosIds, $ParentProcesosIds);

        $this->Procesos = $MyProcesos;

        $Apps  = \App\Models\Apps::all();

        $Apps = $Apps->filter(function($A) use ($MyProcesosIds){
            return count(array_intersect($A['Procesos'], $MyProcesosIds)) > 0;
        })->values();

        if($withPages){
            foreach ($Apps as $App) {
                $App->pages = $App->pages()->get();
            }
        }

        $this->Apps = $Apps;

    }



    public function fromToken($token)
    {
        $Email = Crypt::decrypt($token);

        $Usuario = Usuario::where('Email', $Email)->first();
        return $Usuario;
    }



    //Attributos
    public function getAvatarAttribute()
    {
        if (file_exists( public_path() . '/img/avatars/' . $this->id . '.jpg')) {
            return "img/avatars/$this->id.jpg?".$this->updated_at->timestamp;
        } else {
            return 'img/avatars/default.png';
        }   
    }


}
