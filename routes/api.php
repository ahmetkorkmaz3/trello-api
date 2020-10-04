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

    // Board route list
    Route::apiResource('board', 'BoardController');

    // Column route list
    Route::apiResource('board.column', 'ColumnController');

    // Card route list
    Route::apiResource('board.column.card', 'CardController');

    // team route list
    Route::apiResource('team', 'TeamController');

    // Team Board route list
    Route::apiResource('team.board', 'TeamBoardController');
});
