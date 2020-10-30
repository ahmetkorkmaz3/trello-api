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

    // Board route list
    Route::apiResource('board', 'BoardController');
    Route::post('board/{board}/invite', 'BoardController@invite');

    // Column route list
    Route::apiResource('board.column', 'ColumnController');

    // Card route list
    Route::apiResource('board.column.card', 'CardController');

    // team route list
    Route::apiResource('team', 'TeamController');
    Route::post('team/{team}/invite', 'TeamController@invite');

    // Team Board route list
    Route::apiResource('team.board', 'TeamBoardController');

    Route::get('board-requests', 'BoardRequestController@index');
    Route::put('board-requests/{boardRequest}', 'BoardRequestController@update');

    Route::get('team-requests', 'TeamRequestController@index');
    Route::put('team-requests/{teamRequest}', 'TeamRequestController@update');
});
