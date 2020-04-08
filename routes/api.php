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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/test', function (){
    return 13;
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'

], function ($router) {
    Route::namespace('Admin')->group(function (){
        Route::post('login', 'LoginController@login');
        Route::get('user', 'UserController@getUser');
    });


//    Route::post('refresh', 'AuthController@refresh');
//    Route::post('me', 'AuthController@me');

});
