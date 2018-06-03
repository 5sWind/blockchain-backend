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

Route::get('/', "HomeController@welcome");
Route::get('/bucket/{username}', 'HomeController@getUserPage');
Route::get('/upload', function() {
    return view("upload");
});

Route::prefix('api')->group(function () {
    Route::get('/logon', function () {
        return \Illuminate\Support\Facades\Response::json([
            "is_login" => \Illuminate\Support\Facades\Auth::check(),
        ]);
    });

    Route::post('/login', "AuthController@login");
    Route::post('/signup', "AuthController@signup");
    Route::post('/upload', "HomeController@upload");

});