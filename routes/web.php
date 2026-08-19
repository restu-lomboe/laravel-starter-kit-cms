<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::auth.login')->name('login');

// route groups prefixe admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');

    // group prefix permission
    Route::prefix('permission')->name('permission.')->group(function () {
        Route::livewire('/', 'pages::permission.index')->name('index');
        Route::livewire('/create', 'pages::permission.create')->name('create');
        Route::livewire('/{id}/update', 'pages::permission.update')->name('update');
        Route::livewire('/{id}/detail', 'pages::permission.detail')->name('detail');
    });

    // group prefix roles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::livewire('/', 'pages::roles.index')->name('index');
        Route::livewire('/create', 'pages::roles.create')->name('create');
        Route::livewire('/{id}/update', 'pages::roles.update')->name('update');
        Route::livewire('/{id}/detail', 'pages::roles.detail')->name('detail');
    });

    // group prefix setting
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::livewire('/profile', 'pages::setting.profile')->name('profile');
    });

    Route::livewire('/templates', 'templates')->name('templates');
});
