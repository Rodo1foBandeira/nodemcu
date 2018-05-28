<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware'=>'auth'], function (){
    Route::get('controle', 'ControleController@index');
    Route::get('controle/onOff/{port_id}', 'ControleController@onOff');
    Route::resource('group', 'GroupController');
    Route::resource('node', 'NodeController');
    Route::resource('infrared', 'InfraredController');
    Route::resource('device', 'DeviceController');
    Route::get('device/lerCodigo/{port_id}', 'DeviceController@lerCodigo');
});