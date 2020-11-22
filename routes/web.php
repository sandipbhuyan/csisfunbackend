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

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function(){

    // Branchs
    Route::resource('branch', 'BranchController');
    Route::get('branch/publish/{id}', 'BranchController@publish')->name('branch.publish');
    Route::get('branch/unpublish/{id}', 'BranchController@unpublish')->name('branch.unpublish');

    // Type
    Route::resource('type', 'TypeController');
    Route::get('type/publish/{id}', 'TypeController@publish')->name('type.publish');
    Route::get('type/unpublish/{id}', 'TypeController@unpublish')->name('type.unpublish');

    Route::resource('post', 'PostController');
    Route::get('post/publish/{id}', 'PostController@publish')->name('post.publish');
    Route::get('post/unpublish/{id}', 'PostController@unpublish')->name('post.unpublish');

    // PHOTOS
    Route::resource('category', 'CategoryController');
    Route::get('category/create/{branch_id}', 'CategoryController@createByBranch')->name('category.createByBranch');
    Route::get('category/publish/{id}', 'CategoryController@publish')->name('category.publish');
    Route::get('category/unpublish/{id}', 'CategoryController@unpublish')->name('category.unpublish');

});

Route::middleware(['maintenance'])->prefix(env('MAINTENANCE_URL').'/{password}')->group(function() {
    Route::get('/', 'SetupController@getMaintenance');
    Route::post('/', 'SetupController@postMaintenance')->name('postmn');
});
