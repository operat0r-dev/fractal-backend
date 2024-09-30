<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    // Route::post('refresh', 'AuthController@refresh');
    Route::get('/me', [AuthController::class, 'me']);

    Route::patch('/update', [UserController::class, 'update']);

    Route::post('/workspace/create', [WorkspaceController::class, 'store']);
    Route::patch('/workspace/update/{id}', [WorkspaceController::class, 'update']);
    Route::get('/workspace/{id}', [WorkspaceController::class, 'getOne']);

    Route::get('/boards/{id}', [BoardController::class, 'index']);
    Route::post('/board/store', [BoardController::class, 'store']);

    Route::post('/column/store', [ColumnController::class, 'store']);
    Route::put('/column/{id}', [ColumnController::class, 'update']);

    Route::post('/task/store', [TaskController::class, 'store']);
    Route::put('/task/move/{id}', [TaskController::class, 'move']);
    Route::put('/task/reorder/{id}', [TaskController::class, 'reorder']);

    Route::get('/user/workspaces', [WorkspaceController::class, 'getUserWorkspaces']);
    Route::patch('/user/set-user-workspace', [WorkspaceController::class, 'setUserWorkspace']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
Route::post('/register', [AuthController::class, 'register']);
