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
Route::group(['middleware' => ['auth_api'], 'namespace' => 'Api'], function () {
    Route::get('/get-all-lesson', 'LessonController@getAllLesson');
});

Route::group([ 'namespace' => 'Api'], function () {
    Route::post('/login', 'LoginController@login');
    Route::post('/auth/google-sign', 'GoogleSignController@login');
    Route::get('/check-device', 'CheckDeviceController@checkDevice');
});
