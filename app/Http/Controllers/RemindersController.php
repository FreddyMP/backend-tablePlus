<?php

namespace App\Http\Controllers;

use App\Models\Reminders;
use App\Models\User;
use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskNotification;
use Illuminate\Support\Facades\Schedule;


class RemindersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($task)
    {
        $reminders = Reminders::where("task_id",$task)->get();
        return $reminders;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'task_id'=>'required|numeric',
            'message'=>'required|string|max:255',
            'value_time_reminder'=>'required|numeric|in:1,5,10,15,20,30',
        ]);

        if($validator->fails()){
            return response()->json(["message"=>"Error en la solicitud",'errors' => $validator->errors()]);
        }

        $Reminders = Reminders::create([
            "task_id" => $request->task_id,
            "message" => $request->message,
            "value_time_reminder" => $request->value_time_reminder,
        ]);

        $Reminders->save();

        $user = auth()->user();

        $user = User::find($user->id);
        $user->notify(new TaskNotification($Reminders,"Create"));

        return $user;  
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reminder = Reminders::find($id);
        
    }


    public function destroy(Reminders $reminders)
    {

        $reminder = Reminders::find($reminders->id);

        $reminder->delete();

        return response()->json(["message"=>"Recordatorio eliminada"]);
    }
}
