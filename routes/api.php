<?php

use Illuminate\Http\Request;
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

Route::middleware('localization')->group(function () {
    Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {
        // Auth routes
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/user', 'AuthController@user');
        });
    });
});



