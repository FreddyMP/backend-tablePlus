<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogController;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\DocumentsController;
use App\Http\Cache\Repositories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
  
    public function index(Request $request)
    {
        $user = auth()->user();

        //Consulta Cacheada
        $tasks = Repositories::getTasks($request,$user);

      
        return $tasks;
    }

    public function store(Request $request)
    {
        //Validaciones
        $validator = Validator::make($request->all(),[
            'title'=>'required|string|max:255',
            'description'=>'required|string|max:255',
            'date_end'=>'required|date',
            'hour_end'=>'required|date_format:H:i',
            //'time_alert'=>'required|numeric',
            'document' => 'file|mimes:jpg,png,pdf|max:5000',
        ]);
        if($validator->fails()){
            return response()->json(["message"=>"Error en la solicitud",'errors' => $validator->errors()]);
        }

        //Usuario Logueado
        $user = auth()->user();

        //Insertar tarea
        try {
            $task = Task::create([
                "title"=> $request->title,
                "description"=>$request->description,
                "date_end" => $request->date_end,
                "hour_end" => $request->hour_end,
                "time_alert" => $request->time_alert,
                "status" => "No completada",
                "user_id" =>  $user->id,
            ]);

            $task->save();

            $cacheKey = 'tasks_user_' . $user->id;
            Cache::forget($cacheKey);

            //Cargar el archivo
            if($request->document){
                if ($request->hasFile('document') && $request->file('document')->isValid()){
                
                    $url = $request->file("document")->store('documents', 'public');
                    $loadDocuments =  DocumentsController::loadDocuments($url, $task->id);
    
                    $urlDocumentComplete = env("APP_URL").":".$request->getPort()."/storage/".$url;
                    
                    LogController::NewlineLog("Task","post",$task );
                    

                    return response()->json(["task"=>$task,"document"=>$urlDocumentComplete]);
                    
    
                }else {
                    LogController::NewlineLog("Task","post",$task,"Error al intentar crear archivo" );
                    return response()->json(['error' => 'El archivo no es válido'], 400);
                }
            }
            
            return response()->json(["task"=>$task]);

        } catch (\Throwable $th) {
            return  $th;
        }
    }

    public function show($id)
    {
        $user = auth()->user();

        $task = DB::select("select t.* , d.url as documentUrl  
                            from tasks  t 
                            left join documents d 
                            on d.task_id = t.id 
                            where t.id= $id 
                            and user_id = $user->id
                            ");

        if(empty($task)){
            return response()->json(["message"=>"No tienes esta tarea"]);
        }
        $baseUrl = env("APP_URL").":8000/storage/";

        return $task;
    }

    public function update(Request $request, Task $task)
    {
        //Validaciones
        $validator = Validator::make($request->all(),[
            'title'=>'string|min:2|max:255',
            'description'=>'string|min:5|max:255',
            'date_end'=>'filled|date',
            'hour_end'=>'filled|date_format:H:i',
            'status'=>'string|filled|in:completada,no completada',
            'time_alert'=>'filled|numeric',
            'document' => 'filled|file|mimes:jpg,png,pdf|max:5000',
        ]);
        if($validator->fails()){
            return response()->json(["message"=>"Error en la solicitud",'errors' => $validator->errors()]);
        }


        $data = $request->only(array_keys($request->all()));

        $task->update($data);

        $user = auth()->user();

        $cacheKey = 'tasks_user_' . $user->id;
        Cache::forget($cacheKey);


        return $task;
    }


    public function destroy($id)
    {
        $user = auth()->user();

        $verifiedUserLog = Task::where('id',$id)->where('user_id',$user->id)->first();

        if(!$verifiedUserLog){
            return response()->json(["message"=>"Tarea no encontrada"]);
        }

        $verifiedUserLog->delete();

        $cacheKey = 'tasks_user_' . $user->id;
        Cache::forget($cacheKey);

        return response()->json(["message"=>"Tarea eliminada"]);
       
        
    }
}
