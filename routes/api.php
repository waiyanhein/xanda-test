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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('filter/spacecrafts/{name}/{class}/{status}', 'Api\SpacecraftController@index')->name('api.spacecrafts.filter');
Route::get('spacecrafts', 'Api\SpacecraftController@index')->name('api.spacecrafts');
Route::get('spacecrafts/{spacecraft}', 'Api\SpacecraftController@show')->name('api.spacecrafts.show');

Route::group([ 'middleware' => [ 'auth:api' ] ], function () {
    Route::post('spacecrafts', 'Api\SpacecraftController@store')->name('api.spacecrafts.store');
    Route::put('spacecrafts/{spacecraft}', 'Api\SpacecraftController@update')->name('api.spacecrafts.update');
    Route::delete('spacecrafts/{id}', 'Api\SpacecraftController@destroy')->name('api.spacecrafts.destroy');
});
