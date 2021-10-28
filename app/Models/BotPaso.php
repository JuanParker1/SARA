<?php

namespace App\Models;

use App\Models\Core\MyModel;
use App\Functions\Helper;
use App\Functions\ConnHelper;
use GuzzleHttp\Client;

class BotPaso extends MyModel
{
    protected $table = 'sara_bots_pasos';
	protected $guarded = ['id'];
	protected $hidden = [];
	protected $primaryKey = 'id';
    protected $casts = [];
    protected $appends = [];

    public function columns()
	{
		$Tipos = [ 'Url' => 'Llamar Url', 'Sql' => 'Consulta Sql', 'Email' => 'Enviar Correo' ];

		//Name, Desc, Type, Required, Unique, Default, Width, Options
		return [
			[ 'id',						'id',			null, true, false, null, 100 ],
			[ 'bot_id',				 	'bot_id',		null, true, false, null, 100 ],
			[ 'Indice',				 	'Indice',		null, true, false, null, 100 ],
			[ 'Tipo',				 	'Tipo',			'select', true, false, false, 40, [ 'options' => $Tipos ] ],
			[ 'Nombre',				 	'Nombre',		null, true, false, null, 60 ],
			[ 'config',				 	'config',		null, true, false, null, 100 ]
		];
	}

	public function scopeBot($q, $id)
	{
		return $q->where('bot_id', $id);
	}


	//Funciones
	public function run($Bot, $Variables)
	{
		if($this->Tipo == 'Sql') return $this->runSql($Bot, $Variables);
		if($this->Tipo == 'Url') return $this->runUrl($Bot, $Variables);
	}

	public function runUrl($Bot, $Variables)
	{
		$Res  = [ 'Estado' => 'Ok', 'Mensaje' => '' ];
		$client = new Client();
		$req_type = $this->config['req_type'];
		$json = ($req_type == 'POST') ? $this->config['req_params'] : [];
		$res = $client->request($req_type, $this->config['req_url'], [
		    'json' => $json,
		    'http_errors' => false
		]);

		if($res->getStatusCode() <> 200) $Res['Estado'] = 'Error';
		$Res['Mensaje'] = $res->getBody();

		return $Res;
	}

	public function runSql($Bot, $Variables)
	{
		$Conn = ConnHelper::getBDDConn($this->config['bdd_id']);
		$Res  = [ 'Estado' => 'Ok', 'Mensaje' => '' ];
		try{
			$Res['Mensaje'] = $Conn->statement($this->config['sql'], $Variables);
		} catch(\Illuminate\Database\QueryException $ex){ 
			$Res  = [ 'Estado' => 'Error', 'Mensaje' => $ex->getMessage() ];
		}

		return $Res;
	}





	public function getConfigAttribute($Config)
	{
		if(is_string($Config)) $Config = json_decode($Config);
		$Config = (array) $Config;
		$DefConfig = [
			'open' => true,
			'req_url' => '',
			'req_type' => 'GET',
			'req_params' => '{}',
			'bdd_id' => 1,
			'sql' => "SELECT * FROM TB",
			'mail_from' => 'noreply@sara.com',
			'mail_to' => [],
			'mail_subject' => '',
			'mail_body' => ''
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
