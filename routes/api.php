<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/categories/all', "PublicEndpoint@getAllCategory");
Route::get('/types/all', "PublicEndpoint@getAllType");
Route::get('/posts/all', "PublicEndpoint@getPosts");
Route::get('/filter/post', "PublicEndpoint@filterPost");
Route::get('/post/{id}', "PublicEndpoint@getPost");
