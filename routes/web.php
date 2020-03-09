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

Route::get('/home', 'SpacecraftController@index')->name('home');
Route::get('spacecraft/create', 'SpacecraftController@create')->name('spacecrafts.create');
Route::post('spacecraft/store', 'SpacecraftController@store')->name('spacecrafts.store');
Route::get('spacecraft/{spacecraft}', 'SpacecraftController@show')->name('spacecrafts.show');
Route::post('spacecraft/delete/{id}}', 'SpacecraftController@destroy')->name('spacecrafts.destroy');
Route::get('spacecraft/edit/{spacecraft}', 'SpacecraftController@edit')->name('spacecrafts.edit');
Route::post('spacecraft/update/{spacecraft}', 'SpacecraftController@update')->name('spacecrafts.update');
