<?php

namespace App\Http\Cache;

use App\Models\Task;
use Illuminate\Support\Facades\Cache;

class Repositories
{
    static function getTasks($request, $user)
    {
        $cacheKey = 'tasks_user_' . $user->id;

        $tasksCache = Cache::remember($cacheKey, 30, function () use ($request, $user) {

            $query = Task::query();
            
            #Filter title
            if ($request->has('title')) {
                $query->where('title', 'like', "%$request->title%" );
            }   

            #Filter status
            if ($request->has('status')) {
                $query->where('status',$request->status );
            }  

            #Filter date end
            if ($request->has('date_end')) {
                $query->where('date_end', $request->date_end);
            }  
            
            #Consulta completada
            $task = $query->where("user_id",$user->id)->paginate(10);

            if ($task->isEmpty()) {
                return response()->json(['message' => 'No hay tareas disponibles'], 200);
            }

            return $task;
            
            

        });
        return $tasksCache ;
    }

}