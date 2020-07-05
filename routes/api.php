<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute autentikasi
Route::post('/login', 'Api\UserController@login')->name('api.login');
Route::post('/register', 'Api\UserController@register')->name('api.register');

Route::group(['middleware' => 'auth:api'], function() {
    // Rute logout
    Route::get('/logout', 'Api\UserController@logout')->name('api.logout');

    // Rute user management
    Route::resource('user', 'Api\UserManagement')->except(['create', 'edit']);
});