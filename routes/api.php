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

Route::get('/user', function (Request $request) {
    return ["name"=>"Kevin"];
});

Route::post('/authenticate', [

	'uses' => 'ApiAuthController@authenticate'

]);

Route::get('/authenticate', function (Request $request) {
    return ["name"=>"Kevin"];
})->middleware('jwt.auth');