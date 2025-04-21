<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Reminders;
use App\Notifications\TaskNotification;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class ProbandoController extends Controller
{
   
    static function probar()
    {   
        $date = date("Y-m-d");
        $hora = date("H:i");
        $horaExc = 5;
        while($horaExc <= 30){
            $hour = Carbon::createFromFormat('H:i', $hora)->addMinutes($horaExc);
            

            $hour_end = $hour->format('H:i').":00";
            $tasks = Task::where("date_end",$date)->where("hour_end",$hour_end)->get();

            foreach($tasks as $task ){
           
                $reminders = Reminders::where("task_id", $task->id)->get();
                foreach($reminders as $reminder){
                    if($horaExc == $reminder->value_time_reminder){
                        $user = User::find($task->user_id);
                        $user->notify(new TaskNotification($reminders));
                    }
                }
                
            }
            $horaExc = $horaExc + 5;
            return    $hora ;

        }
        
    }

}
