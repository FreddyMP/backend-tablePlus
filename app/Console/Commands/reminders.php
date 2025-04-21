<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\User;
use App\Models\Reminders as Remi;
use App\Mail\notifications;
use Illuminate\Support\Facades\Mail;
use App\Notifications\TaskNotification;
use Carbon\Carbon;

class reminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'send:reminders {email}';
    protected $signature = 'send:reminders';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de correos con un recordatorio ';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $date = date("Y-m-d");
        $hora = date("H:i");
        $horaExc = 5;
        while($horaExc <= 30){
            $hour = Carbon::createFromFormat('H:i', $hora)->addMinutes($horaExc);
            

            $hour_end = $hour->format('H:i').":00";
            $tasks = Task::where("date_end",$date)->where("hour_end",$hour_end)->get();

            foreach($tasks as $task ){
           
                $reminders = Remi::where("task_id", $task->id)->get();
                foreach($reminders as $reminder){
                    if($horaExc == $reminder->value_time_reminder){
                        $user = User::find($task->user_id);
                        $user->notify(new TaskNotification($reminders,"Recordatorio"));
                    }
                }
                
            }
            $horaExc = $horaExc + 5;

        }
        
    }
}
