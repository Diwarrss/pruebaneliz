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
    return view('welcome');
});

//Rutas EmployeController
Route::get('employe/get', 'EmployeController@get');
/* Route::get('employe/getImmediateBoss', 'EmployeController@getImmediateBoss'); */
Route::post('employe/create', 'EmployeController@create');
Route::post('employe/update', 'EmployeController@update');
Route::put('employe/delete', 'EmployeController@delete');

//Rutas CivilStateController
Route::get('civilstate/get', 'CivilStateController@get');

//Rutas PositionController
Route::get('position/get', 'PositionController@get');
