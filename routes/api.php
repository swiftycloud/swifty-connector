<?php

use Illuminate\Http\Request;

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

Route::group(['namespace' => 'API', 'middleware' => 'auth:api'], function () {
  Route::apiResource('customers', 'CustomerController')->except(['store']);
});

Route::group(['namespace' => 'API'], function () {
  Route::post('customers/login', 'CustomerController@login');
  Route::post('customers/password/link', 'PasswordResetController@link');
  Route::put('customers/password/reset', 'PasswordResetController@reset');
  Route::apiResource('customers', 'CustomerController')->only(['store']);
});

