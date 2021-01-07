<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/register', 'AuthController@register');
Route::post('/auth/login', 'AuthController@login');

Route::middleware('auth:api')->group(function () {
    Route::get('auth/me', 'AuthController@me');
    Route::put('auth/change-password', 'AuthController@changePassword');

    Route::post('upload', 'UploadController');

    // Board routes
    Route::apiResource('board', 'BoardController');

    // Column routes
    Route::prefix('board/{board}/column')->group(function () {
        Route::get('', 'ColumnController@index');
        Route::post('', 'ColumnController@store');
    });

    Route::prefix('column/{column}')->group(function () {
        Route::get('', 'ColumnController@show');
        Route::put('', 'ColumnController@update');
        Route::delete('', 'ColumnController@update');
    });

    // Card routes
    Route::prefix('column/{column}/card')->group(function () {
        Route::get('', 'CardController@index');
        Route::post('', 'CardController@store');
    });

    Route::prefix('card/{card}')->group(function () {
        Route::get('', 'CardController@show');
        Route::put('', 'CardController@update');
        Route::delete('', 'CardController@update');

        Route::prefix('/assignees')->group(function () {
            Route::get('', 'CardAssignController@index');
            Route::put('', 'CardAssignController@update');
            Route::delete('', 'CardAssignController@destroy');
        });

        Route::prefix('/checklist')->group(function () {
            Route::get('', 'CardCheckListController@index');
            Route::post('', 'CardCheckListController@store');

            Route::put('/{cardCheckList}', 'CardCheckListController@update');
            Route::delete('/{cardCheckList}', 'CardCheckListController@destroy');
        });
    });

    // team route list
    Route::apiResource('team', 'TeamController');

    Route::prefix('team/{team}/board')->group(function () {
        Route::get('', 'CardController@index');
        Route::post('', 'CardController@store');
    });

    // Team Board route list
    Route::apiResource('team.board', 'TeamBoardController');

    Route::prefix('team/{team}/user')->group(function () {
        Route::get('', 'TeamUserController@index');
        Route::post('', 'TeamUserController@store');
        Route::delete('/{user}', 'TeamUserController@destroy');
    });

    Route::get('board/{board}/activity', 'BoardActivityController');

    Route::get('activity', 'ActivityController');

    Route::prefix('board/{board}/user')->group(function () {
        Route::get('', 'BoardUserController@index');
        Route::post('', 'BoardUserController@store');
        Route::delete('/{user}', 'BoardUserController@destroy');
    });

    Route::apiResource('team-user-invite', 'TeamUserInviteController', ['except' => ['store', 'show']]);
    Route::apiResource('board-user-invite', 'BoardUserInviteController', ['except' => ['store', 'show']]);
});
