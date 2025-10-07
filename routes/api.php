<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ReportController;





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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);

    // Admin API's
    Route::middleware('can:manage-users')->group(function(){
        Route::get('/users', [UserController::class,'index']);
        Route::put('/users/{id}', [UserController::class,'updateRole']);
        Route::delete('/users/{id}', [UserController::class,'destroy']);
    });

    // Tasks API's
    Route::post('/tasks', [TaskController::class,'store']);
    Route::get('/tasks', [TaskController::class,'index']);
    Route::get('/tasks/{id}', [TaskController::class,'show']);
    Route::put('/tasks/{id}', [TaskController::class,'update']);
    Route::delete('/tasks/{id}', [TaskController::class,'destroy']);

    //Comments
    Route::post('/tasks/{id}/comments', [CommentController::class,'store']);
    Route::get('/tasks/{id}/comments', [CommentController::class,'index']);

    // Reports & activity
    Route::get('/reports/tasks-summary', [ReportController::class,'tasksSummary']);
    Route::get('/activity-logs', [ReportController::class,'activityLogs']);
});
