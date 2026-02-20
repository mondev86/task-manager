<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::apiResource('tasks', TaskController::class);
Route::get('tasks/reminders/pending', [TaskController::class, 'getPendingReminders']);
Route::post('/tasks/{task}/email-reminder', [TaskController::class, 'sendEmailReminder']);
Route::post('tasks/{task}/send-reminder', [TaskController::class, 'sendReminder']);
