<?php

namespace App\Models\Core;

use Illuminate\Database\Connection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Cache;
use Log;
class DB2Connection extends Connection{

    public function select($query, $bindings = [], $useReadPdo = true)
    {
        return $this->decideRoute('query', $query, $bindings);
    }

    public function statement($query, $bindings = [])
    {
        return $this->decideRoute('statement', $query, $bindings);
    }

    public function affectingStatement($query, $bindings = [])
    {
        return $this->decideRoute('statement', $query, $bindings);
    }

    public function decideRoute($mode, $query, $bindings)
    {
        $bindings = $this->prepareBindings($bindings);
        $db = new \App\Functions\DB2;

        /*$first_3 = substr($query, 0, 3);
        if($first_3 !== 'sel'){
            dd(compact('mode','query', 'bindings'));
        };*/
        if(config('app.debugbar')){
            \Debugbar::info(compact('query', 'bindings'));
        };
        
        Log::info('SQL query', compact('query','bindings','mode'));

        if(config('app.remote')){
            $Response = $db->remote($mode, $query, $bindings);
            $Type = gettype($Response);

            if($Type == 'string'){
                Cache::put('last_db2_error', $Response, 60);
                return [];
            };

            return $Response;
        }else{
            return $db->$mode($query, $bindings);
        }
    }
	
}