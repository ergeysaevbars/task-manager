<?php

use App\Http\Controllers\Api\v1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::domain(env('API_DOMAIN'))->name('api.')->group(function () {
    Route::prefix('v1')->namespace('Api\\v1')->name('v1.')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/tasks', [TaskController::class, 'list'])->name('tasks.list');
            Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
            Route::post('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
            Route::post('/tasks/{task}/update', [TaskController::class, 'update'])->name('tasks.update');
            Route::post('/tasks/{task}/delete', [TaskController::class, 'delete'])->name('tasks.delete');
        });
    });
});

