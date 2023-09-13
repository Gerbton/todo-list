<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {
    Route::post('token', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(TaskController::class)->group(function () {
            Route::get('tasks', 'index');
            Route::post('tasks', 'store');
        });
    });

    Route::middleware(['auth:sanctum', 'task.owner'])->group(function () {
        Route::controller(TaskController::class)->group(function () {
            Route::get('tasks/{task}', 'show');
            Route::put('tasks/{task}/edit', 'update');
            Route::delete('tasks/{task}', 'destroy');
        });
    });
});

