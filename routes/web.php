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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

Route::group(['prefix' => '/api/v1'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('account/{id}', 'UsersController@index');
        Route::post('register', 'UsersController@create');
        Route::post('login', 'UsersController@login');
    });
    Route::group(['prefix' => 'transactions'], function () {
        Route::post('add', 'TransactionController@create');
        Route::get('history/{id}', 'TransactionController@history');
    });
});
