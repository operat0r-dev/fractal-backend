<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\IntegrationSettingController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('api.prefix')], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        // Route::post('refresh', 'AuthController@refresh');
        Route::GET('/me', [AuthController::class, 'me']);

        Route::PATCH('/update', [UserController::class, 'update']);
        Route::GET('/users/email/{email}', [UserController::class, 'getUserByEmail']);
        Route::POST('/workspace/{workspaceId}/invite', [UserController::class, 'inviteUserToWorkspace']);

        Route::POST('/workspace/create', [WorkspaceController::class, 'store']);
        Route::PATCH('/workspace/update/{id}', [WorkspaceController::class, 'update']);
        Route::GET('/workspace/{id}', [WorkspaceController::class, 'getOne']);

        Route::GET('/boards/{id}', [BoardController::class, 'index']);
        Route::POST('/board/store', [BoardController::class, 'store']);

        Route::POST('/column/store', [ColumnController::class, 'store']);
        Route::PUT('/column/{id}', [ColumnController::class, 'update']);

        Route::POST('/task/store', [TaskController::class, 'store']);
        Route::PUT('/task/update/{id}', [TaskController::class, 'update']);

        Route::GET('/user/workspaces', [WorkspaceController::class, 'getUserWorkspaces']);
        Route::PATCH('/user/set-user-workspace', [WorkspaceController::class, 'setUserWorkspace']);

        Route::GET('/integration_setting', [IntegrationSettingController::class, 'index']);
        Route::POST('/integration_setting/create', [IntegrationSettingController::class, 'store']);
        Route::PATCH('/integration_setting/update/{id}', [IntegrationSettingController::class, 'update']);
        Route::GET('/integration_setting/delete/{id}', [IntegrationSettingController::class, 'delete']);

        Route::GET('/label/{boardId}', [LabelController::class, 'index']);
        Route::POST('/label/create', [LabelController::class, 'store']);
        Route::PATCH('/label/update/{id}', [LabelController::class, 'update']);
        Route::GET('/label/delete/{id}', [LabelController::class, 'delete']);
        Route::POST('/label/{taskId}/assign', [LabelController::class, 'assign']);
    });

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('/register', [AuthController::class, 'register']);
});
