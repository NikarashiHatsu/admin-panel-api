<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', 'Api\UserController@login')->name('api.login');
Route::post('/register', 'Api\UserController@register')->name('api.register');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/logout', 'Api\UserController@logout')->name('api.logout');
});