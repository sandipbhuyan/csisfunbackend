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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['maintenance'])->prefix(env('MAINTENANCE_URL').'/{password}')->group(function() {
    Route::get('/', 'SetupController@getMaintenance');
    Route::post('/', 'SetupController@postMaintenance')->name('postmn');
});
