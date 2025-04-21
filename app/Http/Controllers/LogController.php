<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
   
    static function NewlineLog(string $modelName, $method, $data, string $customMessage = "Sin mensajes",  )
    {   
        $user = auth()->user();
        $fecha = date("Y-m-d H:i:s");

        $stringLog = $fecha." Id usuario:".$user->id.", Modelo:".$modelName.", Metodo http:".$method.", Mensaje:".$customMessage.", Data: ".$data;
      
        Log::channel('personalizado')->info($stringLog);
    }

}
