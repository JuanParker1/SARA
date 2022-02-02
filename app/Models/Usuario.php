<?php

namespace App\Models;

use App\Models\Core\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Functions\Helper;
use Carbon\Carbon;
use Hash;
use DB;
use Crypt;

class Usuario extends MyModel
{
    use SoftDeletes;

    protected $table = 'sara_usuarios';
	protected $guarded = ['id'];
	protected $hidden = ['Password'];
	protected $primaryKey = 'id';
    protected $appends = [ 'avatar' ];
    protected $casts = [
        'isGod' => 'boolean',
    ];

    public function columns()
    {
        //Name, Desc, Type, Required, Unique, Default, Width, Options
        return [
            [ 'Email',                     null,                   null, true, false, null, 100 ],
            [ 'Documento',                 null,                   null, true, false, null, 100 ],
            [ 'Celular',                   null,                   null, true, false, null, 100 ],
            [ 'Nombres',                   null,                   null, true, false, null, 100 ],
            [ 'Op1',                       null,                   null, true, false, null, 100 ],
            [ 'Op2',                       null,                   null, true, false, null, 100 ],
            [ 'Op3',                       null,                   null, true, false, null, 100 ],
            [ 'Op4',                       null,                   null, true, false, null, 100 ],
            [ 'Op5',                       null,                   null, true, false, null, 100 ],
            [ 'updated_at',                null,                   null, true, false, null, 100 ],
            [ 'deleted_at',                null,                   null, true, false, null, 100 ],
            [ 'last_login',                null,                   null, true, false, null, 100 ],
        ];
    }

    //scopes
    public function scopeEstado($q, $estado)
    {
        if($estado == 'I') return $q->onlyTrashed();
        return $q;
    }


    //relations
    public function asignacion()
    {
        return $this->hasMany('\App\Models\UsuarioAsignacion', 'usuario_id');
    }


	public function authenticate($Email, $Password)
    {
        $User = Usuario::where('Email', $Email)->first();
        if (!$User OR !Hash::check($Password, $User->Password)) {
            return false;
        }

        return $User;
    }

    public function record_login()
    {
        $this->last_login = Carbon::now();
        $this->save();
    }



    public function getSecciones()
    {
    	$Conn = Helper::getInstanceConn();

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
                            AND ps.Level > 0  
                        GROUP BY se.id, se.Seccion, se.Orden, se.Icono 
                        ORDER BY se.Orden";
        }

        $Secciones = $Conn->select($query);
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

        $Apps  = \App\Models\Apps::with([ 'user_apps' ])->get();

        $Apps = $Apps->filter(function($A) use ($MyProcesosIds){
            return count(array_intersect($A['Procesos'], $MyProcesosIds)) > 0;
        })->map(function($A){
            $user_app = $A->user_apps->get(0);
            if($user_app){ 
                $A->favorito = $user_app->favorito;
            }else{
                $A->favorito = false;
            };
            return $A;
        })->values();

        if($withPages){
            foreach ($Apps as $App) {
                $App->pages = $App->pages()->get();
            }
        }

        $this->Apps = $Apps;

    }


    public function filterAsignacionArr(&$Usuarios, $asignacion)
    {
        if($asignacion[0] == 'Unnasigned'){
            $Usuarios = $Usuarios->filter(function($U){
                return count($U->asignacion) == 0;
            })->values();
        }

        if($asignacion[0] == 'Asigned'){
            $Usuarios = $Usuarios->filter(function($U){
                return count($U->asignacion) > 0;
            })->values();
        }

        if($asignacion[0] == 'Perfil'){
            $Usuarios = $Usuarios->filter(function($U) use ($asignacion){
                return $U->asignacion->filter(function($A) use ($asignacion){
                    return $A->perfil_id == $asignacion[1];
                })->count() > 0;
            })->values();
        }

        if($asignacion[0] == 'Proceso'){
            $Usuarios = $Usuarios->filter(function($U) use ($asignacion){
                return $U->asignacion->filter(function($A) use ($asignacion){
                    return $A->nodo_id == $asignacion[1];
                })->count() > 0;
            })->values();
        }
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
        $config = \App\Functions\Helper::getInstanceConfig();
        $avatar_path = "/fs/{$config['key']}/avatars/{$this->id}.jpg";
        if (file_exists( public_path().$avatar_path )) {
            $timestamp = is_null($this->updated_at) ? '' : $this->updated_at->timestamp;
            return $avatar_path."?".$timestamp;
        } else {
            return 'img/avatars/default.png';
        }
    }


}
