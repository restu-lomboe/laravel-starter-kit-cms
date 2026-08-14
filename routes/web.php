<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::auth.login')->name('login');

// route groups prefixe admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');

    // group prefix setting
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::livewire('/profile', 'pages::setting.profile')->name('profile');
    });
});
