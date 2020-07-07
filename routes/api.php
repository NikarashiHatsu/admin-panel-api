<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute autentikasi
Route::post('login', 'Api\UserController@login')->name('api.login');
Route::post('register', 'Api\UserController@register')->name('api.register');

Route::group(['middleware' => 'auth:api'], function() {
    // Rute logout
    Route::get('logout', 'Api\UserController@logout')->name('api.logout');

    // Rute user management
    Route::resource('user', 'Api\UserManagement')->except(['create', 'edit']);
    Route::resource('patient', 'Api\PatientController')->except(['create', 'edit']);
    Route::resource('article', 'Api\ArticleController')->except(['create', 'edit']);

    // App management - base url
    Route::get('get_base_url', 'Api\AppManagementController@get_base_url')->name('api.app.base_url');
    Route::post('store_base_url', 'Api\AppManagementController@store_base_url')->name('api.app.store_base_url');
    Route::put('update_base_url', 'Api\AppManagementController@update_base_url')->name('api.app.update_base_url');
});