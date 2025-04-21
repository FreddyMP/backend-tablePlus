<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskNotification extends Notification
{
    use Queueable;
    
    /**
     * Create a new notification instance.
     */
    public function __construct($data, $type)
    {
        try {
            $recibo = $data[0];
            $this->taskId = $recibo["task_id"];
        } catch (\Throwable $th) {

            $this->taskId = $data["task_id"];
        }

        $this->type = $type;
            
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $taskId = $this->taskId;
        $type = $this->type;
       $task = Task::find($taskId);
       
        $title = $task->title;
        $description = $task->description;
        $hour = $task->hour_end;
        $date = $task ->date_end;
        $timeExec = $date." ".$hour;

        if($type == "Create"){
           $message = " has creado una nueva alerta para esta tarea";
        }else{
            $message = " se acerca el tiempo límite de esta tarea";
        }

        return (new MailMessage)
        ->greeting('Table Plus')
        ->line("Hola $notifiable->name ".$message)
        ->line('Titulo: '.$title)
        ->line('Description: '.$description)
        ->line('Time: '.$timeExec );
       
           
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
