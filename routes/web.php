<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Models\MagicLoginToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Fortify\Events\TwoFactorAuthenticationChallenged;

Route::livewire('/', 'pages::auth.login')->name('login');
Route::livewire('/two-factor-challenge', 'pages::auth.two-factor-challenge')
    ->middleware('guest')
    ->name('two-factor.login');

// Magic link verification (guest)
Route::get('/auth/magic-link/verify', function (Request $request) {
    $request->validate([
        'token' => ['required', 'string'],
        'email' => ['required', 'email'],
    ]);

    $email = Str::lower(trim($request->query('email')));
    $plainToken = $request->query('token');
    $hashed = hash('sha256', $plainToken);

    $record = MagicLoginToken::where('email', $email)
        ->where('token', $hashed)
        ->latest()
        ->first();

    if (! $record || $record->isExpired() || $record->isUsed()) {
        return redirect()->route('login')->with('error', 'Magic link is invalid or has expired.');
    }

    $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

    if (! $user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }

    $record->update(['used_at' => now()]);

    if ($user->hasEnabledTwoFactorAuthentication()) {
        session()->put([
            'login.id' => $user->getKey(),
            'login.remember' => false,
        ]);
        event(new TwoFactorAuthenticationChallenged($user));

        return redirect()->route('two-factor.login');
    }

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->intended(route('admin.dashboard'));
})->middleware('guest')->name('auth.magic.verify');

// Google SSO (only if enabled in settings, controller will check)
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->middleware('guest')->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->middleware('guest')->name('auth.google.callback');

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
