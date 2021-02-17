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


Route::middleware(['auth', 'verifiedEmail'])->group(function(){
    Route::get('/route-1', 'MainController@route1');

    Route::middleware(['verifiedAdmin'])->group(function(){
        Route::get('/route-2', 'MainController@route2');
    });
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
