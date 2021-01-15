<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Functions\CRUD;
use Carbon\Carbon;
use App\Models\Bot;
use App\Models\BotLog;


class BotsController extends Controller
{
    public function postIndex()
    {
    	$CRUD = new CRUD('App\Models\Bot');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postPasos()
    {
    	$CRUD = new CRUD('App\Models\BotPaso');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function postVariables()
    {
        $CRUD = new CRUD('App\Models\BotVariable');
        return $CRUD->call(request()->fn, request()->ops);
    }

    public function getRun($id)
    {
        $Bot = Bot::where('id', $id)->first();
        $Bot->run();
        return $Bot;
    }

    public function postLogs()
    {
        extract(request()->all()); //Inicio, Fin, bot_id
        $BotLogs = BotLog::where('bot_id', $bot_id)->with(['paso'])->orderBy('created_at')->get();
        return $BotLogs;
    }

    public function getCheck()
    {
        //Obtener los bots activos
        $Bots = Bot::whereIn('Estado', ['Espera'])->get();
        $Ahora = Carbon::now();
        $DiasSem = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
        $DiaSem = $DiasSem[$Ahora->dayOfWeek];

        $Bots = $Bots->filter(function($Bot) use ($DiaSem, $Ahora){
            
            if(!$Bot->config[$DiaSem])       return false;
            if(empty($Bot->config['Horas'])) return false;

            $Horas = $Bot->config['Horas'];
            sort($Horas);

            $Run = false;
            foreach ($Horas as $H) {
                $Hora = Carbon::createFromFormat('H:i', $H);
                if( $Hora->isAfter($Ahora) ) continue; //Hora en el futuro
                if( $Hora->isBefore($Bot->lastrun_at) ) continue; //Hora cubierta
                $Run = true; break;
            }

            return $Run;
        });

        foreach ($Bots as $Bot) {
            echo "Corriendo: ".$Bot->Nombre;
            $Bot->run();
        }
    }

    public function getTest()
    {
        sleep(3);
        //return 'Ok esta url';
        abort('No se pudo ejecutar esta url');
    }
}
