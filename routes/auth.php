<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Auth routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "auth" middleware group. Enjoy building your Auth!
|
*/

Route::group(['namespace' => 'Auth', 'as' => 'auth.', 'prefix' => 'auth'], function () {
    Route::any('totp/{code}', 'TOTPController@main'); 
});
