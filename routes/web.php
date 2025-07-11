<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::resource('tasks', TaskController::class)->except(['index', 'create', 'edit']);
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');

Route::resource('projects', ProjectController::class)->only(['index', 'store']);
