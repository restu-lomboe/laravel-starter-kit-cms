<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuthenticationSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Fortify\Events\TwoFactorAuthenticationChallenged;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    private function configure(): void
    {
        try {
            $settings = AuthenticationSetting::current();

            if ($settings->google_client_id) {
                config(['services.google.client_id' => $settings->google_client_id]);
            }

            if ($settings->google_client_secret) {
                config(['services.google.client_secret' => $settings->google_client_secret]);
            }

            // Ensure redirect matches the defined callback route
            if (! config('services.google.redirect')) {
                config(['services.google.redirect' => route('auth.google.callback')]);
            }
        } catch (\Throwable $e) {
            // table may not exist yet
        }
    }

    public function redirect(Request $request)
    {
        $this->configure();

        try {
            $settings = AuthenticationSetting::current();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Authentication settings not configured.');
        }

        if (! $settings->google_sso_enabled) {
            return redirect()->route('login')->with('error', 'Google sign-in is disabled.');
        }

        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()->route('login')->with('error', 'Google SSO is not configured. Please set Client ID and Secret in Settings → Authentication.');
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        $this->configure();

        try {
            $settings = AuthenticationSetting::current();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Authentication settings not configured.');
        }

        if (! $settings->google_sso_enabled) {
            return redirect()->route('login')->with('error', 'Google sign-in is disabled.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('login')->with('error', 'Failed to authenticate with Google. Please try again.');
        }

        $email = Str::lower(trim($googleUser->getEmail()));
        $name = $googleUser->getName() ?: $googleUser->getNickname() ?: Str::before($email, '@');

        if (! $email) {
            return redirect()->route('login')->with('error', 'Unable to retrieve email from Google.');
        }

        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (! $user) {
            // Create new user with a random password; they can still use Google SSO next time
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(32)),
            ]);
        }

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
    }
}
