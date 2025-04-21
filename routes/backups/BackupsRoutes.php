<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackupController;


Route::middleware(['auth:sanctum'])->group(function (){
    Route::get('/backup', [BackupController::class,'generate']);
    Route::get('/backup/list', [BackupController::class,'getAllBackups']);
    Route::post('/backup/restore', [BackupController::class,'loadBackup']);
});