<?php 

namespace App\Functions;

use App\Models\Log;
use Crypt;

class Logger
{
	function __construct($Entity, $Entity_id = null, $Msj = '')
	{
		if(config('app.env') == 'production'){
			$token = request()->header('token');
			$Email = $token ? Crypt::decrypt($token) : null;
			
			Log::create([
				'Email' => $Email,
				'Entity' => $Entity,
				'Entity_id' => $Entity_id,
				'Msj' => $Msj,
			]);
		}
		
	}
}