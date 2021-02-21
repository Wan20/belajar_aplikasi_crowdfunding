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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
    'namespace' => 'Auth'
], function($router) {
    Route::post('register', 'RegisterController');
    Route::post('verification', 'VerificationController');
    Route::post('regenerate-otp', 'RegenerateOTPController');
    Route::post('update-password', 'UpdatePasswordController');
    Route::post('login', 'LoginController');
});


Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'profile',
    'namespace' => 'Profile'
], function($router) {
    Route::get('get-profile', 'ProfileController@show');
    Route::post('update-profile', 'ProfileController@update');
});
