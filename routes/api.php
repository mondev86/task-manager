<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class)->except(['show']);
    
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::get('/reminders/pending', [TaskController::class, 'getPendingReminders']);
    Route::post('/tasks/{task}/whatsapp', [TaskController::class, 'sendReminder']);
    
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'throttle:5,1'])->group(function () {
    Route::post('/tasks/{task}/email', [TaskController::class, 'sendEmailReminder']);
});
