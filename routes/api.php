<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProbandoController;

require_once("tasks/TasksRoutes.php");
require_once("reminders/RemindersRoutes.php");
require_once("backups/BackupsRoutes.php");
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/
Route::post('register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::get('/logout', [AuthController::class,'logout']);
Route::get('/probando', [ProbandoController::class,'probar']);




