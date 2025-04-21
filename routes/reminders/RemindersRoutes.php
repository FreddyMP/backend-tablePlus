<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RemindersController;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskNotification;
use App\Models\User;

Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('reminders', [RemindersController::class,'store']);
    Route::get('reminders/task/{id}', [RemindersController::class,'index']);
    Route::get('reminders/{id}', [RemindersController::class,'show']);
    Route::put('reminders/{reminder}', [RemindersController::class,'update']);
    Route::delete('reminders/{id}', [RemindersController::class,'destroy']);
});


