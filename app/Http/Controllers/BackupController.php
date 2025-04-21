<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\Backup;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function generate()
    {
        $user = auth()->user();
        $task = Task::where('user_id',$user->id)->get();

        $fechaBackup = date('Y-m-d h:i:s');

        $tareas = $task->toArray();
        $data = ["Tareas"=>[$tareas],
                "FechaBackup" =>$fechaBackup,
        ];

        $xml = ArrayToXml::convert($data, 'usuario');
        $nameFile = uniqid();    
        $path = storage_path('app/backups/'.$nameFile.'.xml');
        file_put_contents($path, $xml);

        $backup = Backup::create([
            "user_id"=>$user->id ,
            "url_backup"=> $_SERVER['HTTP_HOST']."/storage/backups/".$nameFile.'.xml',
        ]);

        $backup->save();
        return response()->download($path);
    }

    public function getAllBackups(){
        $user = auth()->user();
        $backups = Backup::where("user_id",$user->id )->get();
        return $backups;
    }

    public function loadBackup(Request $request){
        $user = auth()->user();
        $validator = Validator::make($request->all(),[
            'document' => 'file|mimes:xml',
        ]);
        if($validator->fails()){
            return response()->json(["message"=>"Error en la solicitud",'errors' => $validator->errors()]);
        }

        if ($request->hasFile('document') && $request->file('document')->isValid()){
                
            $name = $request->file('document');

            $xmlString = file_get_contents($name->getRealPath());
         
            $xml = simplexml_load_string($xmlString);
            
            $TareasArray = json_decode(json_encode($xml), true);
            $a = "fsd";
            $task = DB::delete("DELETE from tasks where user_id = $user->id");
            foreach ($TareasArray['Tareas'] as $tarea) {
                $a = "fddsd";
                $title = $tarea["title"];
                $description = $tarea["description"];
                $status = $tarea["status"];
                $date_end = $tarea["date_end"];
                $hour_end = $tarea["hour_end"];
                // Ejemplo: mostrar
                $task = Task::create([
                    "title"=>$title,
                    "description"=>$description,
                    "status" =>$status,
                    "date_end" =>$date_end ,
                    "hour_end"  =>$hour_end,
                    "user_id" =>$user->id
                ]);

                $task->save();
            }
            return  $TareasArray['Tareas']   ;

        }else {
            return response()->json(['error' => 'El archivo no es válido'], 400);
        }

    }
}
