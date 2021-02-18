<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index', ['page' => 'Home']);
});

Route::get('/log', 'HomeController@log');

Route::get('/log/{year}/{month}/{day}', 'HomeController@log');


Route::get('/test', 'HomeController@test');
