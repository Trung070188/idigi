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


Route::get('/', function() {
    return redirect('/xadmin/lessons/index');
})->name('home');

Route::get('/xadmin', function() {
    return redirect('/xadmin/lessons/index');
});


Route::post('/auth/google-sign', 'Auth\GoogleSignController@login');
Route::get('/sso', 'SSOController@index');

Route::middleware(['auth', 'CheckIfRole'])->namespace('Admin')->prefix('xadmin')->group(function () {
    Route::get('/elfinder', 'ElfinderController@index');
    Route::get('/data-source/index', 'DataSourceController@index')->name('data-source');
    Route::post('/data-source/get-many', 'DataSourceController@getMany')->name('data-source-get-many');
    Route::any('/files/{action}', 'FilesController')->name('files');
    Route::get('/dashboard/{action}', 'DashboardController')->name('dashboard');
    $registry = require_once base_path('routes/registry.php');

    foreach ($registry as $route) {
        Route::any($route['path'], $route['action'])->name($route['name']);
    }

});

Route::middleware(['auth' ])->namespace('Admin')->prefix('xadmin')->group(function () {

    Route::get('/request_role/index', 'RequestRolesController@index');
    Route::post('/request_role/save', 'RequestRolesController@save');

});

Route::middleware(['auth'])->namespace('Teacher')->prefix('xadmin')->group(function (){
    Route::get('/app_versions/downloadApp','AppVersionsController@downloadApp');
});

Route::group(['prefix' => 'xadmin'], function(){
    Route::get('login','Auth\LoginController@showLoginForm')->name('login');
    Route::post('login','Auth\LoginController@login');
    Route::get('logout','Auth\LoginController@logout');
});


