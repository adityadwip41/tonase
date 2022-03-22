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

Route::get('/test', 'TestProgramerController@index')->name('index');
Route::get('/test/{id}', 'TestProgramerController@delete')->name('delete');
Route::post('/test/create','TestProgramerController@create')->name('create');
