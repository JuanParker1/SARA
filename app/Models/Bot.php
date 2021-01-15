<?php

namespace App\Models;

use App\Models\Core\MyModel;
use App\Functions\Helper;
use App\Functions\ConnHelper;
use Carbon\Carbon;
use App\Models\BotLog;

class Bot extends MyModel
{
    protected $table = 'sara_bots';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [];
    protected $appends = [];

    public function columns()
	{
		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',			null, true, false, null, 100 ],
			[ 'Nombre',				 	'Nombre',		null, true, false, null, 100 ],
			[ 'Estado',				 	'Estado',		null, true, false, null, 100 ],
			[ 'config',				 	'config',		null, true, false, null, 100 ],
			[ 'lastrun_at',				'lastrun_at',		null, true, false, null, 100 ]
		];
	}


	//Relaciones
	public function pasos()
	{
		return $this->hasMany('\App\Models\BotPaso', 'bot_id')->orderBy('Indice');
	}

	public function variables()
	{
		return $this->hasMany('\App\Models\BotVariable', 'bot_id');
	}


	//Funciones
	public function run()
	{
		//Preparar variables
		$Variables = $this->variables->keyBy('Nombre')->transform(function($Var){
			return Helper::getSystemVariable($Var['Valor']);
		})->toArray();

		//Marcar inicio
		$this->Estado = 'Corriendo'; $this->lastrun_at = Carbon::now(); $this->save();
		BotLog::create([ 'bot_id' => $this->id, 'Estado' => 'Inicio', 'Mensaje' => 'Inicio de ejecución' ]);
		$ConError = false;

		//dd($this->pasos->toArray());

		foreach ($this->pasos as $Paso) {
			BotLog::create([ 'bot_id' => $this->id, 'bot_paso_id' => $Paso->id, 'Mensaje' => 'Inicio paso' ]);
			$Res = $Paso->run($this, $Variables);
			BotLog::create([ 'bot_id' => $this->id, 'bot_paso_id' => $Paso->id, 'Estado' => $Res['Estado'], 'Mensaje' => $Res['Mensaje'] ]);
			
			if($Res['Estado'] == 'Error'){
				$this->Estado = 'Error'; $this->save();
				$ConError = true;
				break;
			};
		}

		if(!$ConError){ $this->Estado = 'Espera'; $this->save(); }
	}


	//Campos
	public function getConfigAttribute($Config)
	{
		if(is_string($Config)) $Config = json_decode($Config);
		$Config = (array) $Config;
		$DefConfig = [
			'Lun' => true,
			'Mar' => true,
			'Mie' => true,
			'Jue' => true,
			'Vie' => true,
			'Sab' => false,
			'Dom' => false,
			'Horas' => [ '06:00' ],
		];
		return array_merge($DefConfig, $Config);
	}

	//Eventos
	public static function boot()
    {
		parent::boot();

		self::saving(function($model){
			if(!is_string($model->config)) $model->config = json_encode($model->config);
        });

    }

}
