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

//Route::get('/test', 'HomeController@test');

Route::post('/auth/register', 'AuthController@register');
Route::post('/auth/login', 'AuthController@login');


Route::middleware('auth:api')->group(function () {
    Route::get('auth/me', 'AuthController@me');

    Route::apiResource('board', 'BoardController');

    // Column route list
    Route::get('board/{board}/column', 'ColumnController@index');
    Route::post('board/{board}/column', 'ColumnController@store');
    Route::apiResource('column', 'ColumnController')->only('show', 'update', 'destroy');

    // Card route list
    Route::get('column/{column}/card', 'CardController@index');
    Route::post('column/{column}/card', 'CardController@store');
    Route::apiResource('card', 'CardController')->only('show', 'update', 'destroy');

    // team route list
    Route::apiResource('team', 'TeamController');
});
