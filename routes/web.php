<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::auth.login')->name('login');

// route groups prefixe admin
Route::middleware('auth.login')->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/dashboard', 'pages::dashboard')
        ->name('dashboard');

    // group prefix permission
    Route::prefix('permission')->name('permission.')->group(function () {
        Route::livewire('/', 'pages::permission.index')
            ->middleware('permission:permission.index')
            ->name('index');
        Route::livewire('/create', 'pages::permission.create')
            ->middleware('permission:permission.create')
            ->name('create');
        Route::livewire('/{id}/update', 'pages::permission.update')
            ->middleware('permission:permission.update')
            ->name('update');
        Route::livewire('/{id}/detail', 'pages::permission.detail')
            ->middleware('permission:permission.detail')
            ->name('detail');
    });

    // group prefix roles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::livewire('/', 'pages::roles.index')
            ->middleware('permission:roles.index')
            ->name('index');
        Route::livewire('/create', 'pages::roles.create')
            ->middleware('permission:roles.create')
            ->name('create');
        Route::livewire('/{id}/update', 'pages::roles.update')
            ->middleware('permission:roles.update')
            ->name('update');
        Route::livewire('/{id}/detail', 'pages::roles.detail')
            ->middleware('permission:roles.detail')
            ->name('detail');
    });

    // group prefix user
    Route::prefix('user')->name('user.')->group(function () {
        Route::livewire('/', 'pages::users.index')
            ->middleware('permission:users.index')
            ->name('index');
        Route::livewire('/create', 'pages::users.create')
            ->middleware('permission:users.create')
            ->name('create');
        Route::livewire('/{id}/update', 'pages::users.update')
            ->middleware('permission:users.update')
            ->name('update');
        Route::livewire('/{id}/detail', 'pages::users.detail')
            ->middleware('permission:users.detail')
            ->name('detail');
    });

    // group prefix setting
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::livewire('/profile', 'pages::setting.profile')->name('profile');
    });

    Route::livewire('/templates', 'templates')->name('templates');

    Route::post('/logout', function () {
        auth()->logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout successfully');
    })->name('logout');
});
