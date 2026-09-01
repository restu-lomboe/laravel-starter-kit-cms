<?php

use App\Models\AuthenticationSetting;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

new class extends Component {
    public string $btnPreview = 'Sign In';
    public bool $formPassword = true;

    // typed single-row settings
    public string $defaultMethod = 'email';
    public bool $passkeyEnabled = false;
    public bool $googleSsoEnabled = true;
    public string $googleClientId = '';
    public string $googleClientSecret = '';

    // keep legacy property for backward compat in tests if needed
    public $login_defaults = [];
    public $login_sso = true;

    public function mount(): void
    {
        $settings = AuthenticationSetting::current();

        $this->defaultMethod = $settings->default_method;
        $this->passkeyEnabled = (bool) $settings->passkey_enabled;
        $this->googleSsoEnabled = (bool) $settings->google_sso_enabled;
        $this->googleClientId = $settings->google_client_id ?? '';
        // do not prefill secret for security; keep empty unless user wants to change
        $this->googleClientSecret = '';

        // legacy sync
        $this->login_defaults = [$this->defaultMethod];
        $this->login_sso = $this->googleSsoEnabled;

        $this->syncPreview();
    }

    private function syncPreview(): void
    {
        if ($this->defaultMethod === 'magic_link') {
            $this->btnPreview = 'Sign in via a one-time link sent to your email';
            $this->formPassword = false;
        } elseif ($this->defaultMethod === 'otp') {
            $this->btnPreview = 'Sign in with a 6-digit code sent to your email';
            $this->formPassword = false;
        } else {
            $this->btnPreview = 'Sign In';
            $this->formPassword = true;
        }
    }

    private function persist(array $attributes, string $message): void
    {
        $settings = AuthenticationSetting::current();
        $settings->update($attributes);
        $this->syncPreview();

        LivewireAlert::title($message)
            ->success()
            ->toast()
            ->position('center')
            ->timer(2500)
            ->timerProgressBar()
            ->withOptions(['width' => '30%'])
            ->show();
    }

    public function updatedDefaultMethod($value): void
    {
        $value = in_array($value, ['email', 'magic_link', 'otp'], true) ? $value : 'email';
        $this->defaultMethod = $value;
        $this->login_defaults = [$value];

        $map = [
            'email' => 'Login method successfully changed to Email & Password.',
            'magic_link' => 'Login method successfully changed to Magic Link.',
            'otp' => 'Login method successfully changed to One-time password (email).',
        ];

        $this->persist(['default_method' => $value], $map[$value] ?? $map['email']);
    }

    // legacy: when old checkbox array is updated (e.g. in tests), sync to single
    public function updatedLoginDefaults($value): void
    {
        $arr = is_array($value) ? $value : [$value];
        $candidate = end($arr) ?: 'email';
        // normalize magiclink -> magic_link
        if ($candidate === 'magiclink') {
            $candidate = 'magic_link';
        }
        $this->updatedDefaultMethod($candidate);
    }

    public function updatedPasskeyEnabled($value): void
    {
        $this->passkeyEnabled = (bool) $value;
        $this->persist(['passkey_enabled' => $this->passkeyEnabled], 'Passkey successfully ' . ($this->passkeyEnabled ? 'enabled' : 'disabled'));
    }

    public function updatedGoogleSsoEnabled($value): void
    {
        $this->googleSsoEnabled = (bool) $value;
        $this->login_sso = $this->googleSsoEnabled;
        $this->persist(['google_sso_enabled' => $this->googleSsoEnabled], 'SSO successfully ' . ($this->googleSsoEnabled ? 'enabled' : 'disabled'));
    }

    public function updatedLoginSso($value): void
    {
        $this->updatedGoogleSsoEnabled($value);
    }

    public function save(): void
    {
        $this->validate([
            'defaultMethod' => ['required', 'in:email,magic_link,otp'],
            'googleClientId' => ['nullable', 'string', 'max:255'],
            'googleClientSecret' => ['nullable', 'string', 'max:1000'],
        ]);

        $attributes = [
            'default_method' => $this->defaultMethod,
            'passkey_enabled' => $this->passkeyEnabled,
            'google_sso_enabled' => $this->googleSsoEnabled,
            'google_client_id' => $this->googleClientId ?: null,
        ];

        // only overwrite secret if user typed a new one
        if ($this->googleClientSecret !== '') {
            $attributes['google_client_secret'] = $this->googleClientSecret;
        }

        $this->persist($attributes, 'Authentication settings saved.');
        $this->googleClientSecret = '';
    }
};
?>

<div>
    <div id="settings-panel-authentication" class="settings-panel">
        <div>
            <p class="text-sm font-semibold text-ink">Authentication</p>
            <p class="text-xs text-mist mt-0.5">Choose how employees can sign in to Anchor HR</p>
        </div>

        <!-- session error and success -->
        @if (session()->has('error'))
            <div class="mt-6 rounded-xl border border-line bg-surface p-4 flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-mist shrink-0"><i
                        class="fa-solid fa-triangle-exclamation"></i></span>
                <div>
                    <p class="text-sm font-medium text-ink">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div wire:loading.class="blur-xs">
            <div class="mt-6 grid grid-cols-1 xl:grid-cols-2 gap-8 items-start">

                <!-- ===== LEFT — SETUP ===== -->
                <div class="space-y-3">

                    <!-- Password (default, always on) -->
                    <div class="rounded-xl border border-line bg-card p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-mist shrink-0"><i
                                        class="fa-solid fa-lock"></i></span>
                                <div>
                                    <p class="text-sm font-medium text-ink">Username / email &amp; password</p>
                                    <p class="text-xs text-mist mt-0.5">The default sign-in method for all accounts</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="radio" name="defaultMethod" id="toggle-default" class="peer sr-only"
                                    wire:model.live="defaultMethod" value="email">
                                <div
                                    class="w-9 h-5 rounded-full bg-surface-2 border border-line peer-checked:bg-amber transition-colors">
                                </div>
                                <span
                                    class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-card border border-line transition-transform peer-checked:translate-x-4 peer-checked:border-transparent"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Magic link -->
                    <div class="rounded-xl border border-line bg-card p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-mist shrink-0"><i
                                        class="fa-solid fa-wand-magic-sparkles"></i></span>
                                <div>
                                    <p class="text-sm font-medium text-ink">Magic link</p>
                                    <p class="text-xs text-mist mt-0.5">Sign in via a one-time link sent to their email
                                    </p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="radio" name="defaultMethod" id="toggle-magiclink" class="peer sr-only"
                                    value="magic_link" wire:model.live="defaultMethod">
                                <div
                                    class="w-9 h-5 rounded-full bg-surface-2 border border-line peer-checked:bg-amber transition-colors">
                                </div>
                                <span
                                    class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-card border border-line transition-transform peer-checked:translate-x-4 peer-checked:border-transparent"></span>
                            </label>
                        </div>
                    </div>

                    <!-- OTP via email -->
                    <div class="rounded-xl border border-line bg-card p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-mist shrink-0"><i
                                        class="fa-solid fa-shield-halved"></i></span>
                                <div>
                                    <p class="text-sm font-medium text-ink">One-time password (email)</p>
                                    <p class="text-xs text-mist mt-0.5">Sign in with a 6-digit code sent to their email
                                    </p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="radio" name="defaultMethod" id="toggle-otp" class="peer sr-only"
                                    value="otp" wire:model.live="defaultMethod">
                                <div
                                    class="w-9 h-5 rounded-full bg-surface-2 border border-line peer-checked:bg-amber transition-colors">
                                </div>
                                <span
                                    class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-card border border-line transition-transform peer-checked:translate-x-4 peer-checked:border-transparent"></span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-semibold text-ink">Optional</p>
                        <p class="text-xs text-mist mt-0.5">
                            You can add more sign-in methods later
                        </p>
                    </div>

                    <!-- Passkeys -->
                    <div class="rounded-xl border border-line bg-card p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-mist shrink-0"><i
                                        class="fa-solid fa-key"></i></span>
                                <div>
                                    <p class="text-sm font-medium text-ink">Passkeys</p>
                                    <p class="text-xs text-mist mt-0.5">
                                        passwordless sign-in with passkeys
                                    </p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" id="passkey" class="peer sr-only" value="1"
                                    wire:model.live="passkeyEnabled">
                                <div
                                    class="w-9 h-5 rounded-full bg-surface-2 border border-line peer-checked:bg-amber transition-colors">
                                </div>
                                <span
                                    class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-card border border-line transition-transform peer-checked:translate-x-4 peer-checked:border-transparent"></span>
                            </label>
                        </div>
                    </div>

                    <!-- SSO (Google) -->
                    <div class="rounded-xl border border-line bg-card p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 shrink-0">
                                    <svg width="16" height="16" viewBox="0 0 48 48">
                                        <path fill="#EA4335"
                                            d="M24 9.5c3.5 0 6.6 1.2 9.1 3.6l6.8-6.8C35.9 2.4 30.4 0 24 0 14.6 0 6.4 5.4 2.5 13.2l7.9 6.1C12.3 13.1 17.6 9.5 24 9.5z" />
                                        <path fill="#4285F4"
                                            d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.5 3-2.2 5.5-4.7 7.2l7.4 5.7c4.3-4 6.8-9.9 6.8-17.4z" />
                                        <path fill="#FBBC05"
                                            d="M10.4 19.3A14.5 14.5 0 0 0 9.5 24c0 1.6.3 3.2.9 4.7l-7.9 6.1A24 24 0 0 1 0 24c0-3.9.9-7.5 2.5-10.7l7.9 6z" />
                                        <path fill="#34A853"
                                            d="M24 48c6.4 0 11.9-2.1 15.9-5.8l-7.4-5.7c-2.1 1.4-4.8 2.3-8.5 2.3-6.4 0-11.7-3.6-13.6-8.8l-7.9 6.1C6.4 42.6 14.6 48 24 48z" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-ink">Single sign-on (Google)</p>
                                    <p class="text-xs text-mist mt-0.5">Let employees sign in with their Google
                                        Workspace
                                        account</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" id="toggle-sso" class="peer sr-only" value="1"
                                    wire:model.live="googleSsoEnabled">
                                <div
                                    class="w-9 h-5 rounded-full bg-surface-2 border border-line peer-checked:bg-amber transition-colors">
                                </div>
                                <span
                                    class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-card border border-line transition-transform peer-checked:translate-x-4 peer-checked:border-transparent"></span>
                            </label>
                        </div>

                        <!-- SSO config fields -->
                        <div id="sso-config" class="mt-4 pt-4 border-t border-line space-y-3">
                            <div>
                                <label for="google-client-id" class="block text-xs font-medium text-mist mb-1.5">Google
                                    Client ID</label>
                                <input id="google-client-id" type="text" wire:model="googleClientId"
                                    placeholder="xxxxxx.apps.googleusercontent.com"
                                    class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition font-mono" />
                                @error('googleClientId')
                                    <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                        <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>
                            <div>
                                <label for="google-client-secret"
                                    class="block text-xs font-medium text-mist mb-1.5">Google
                                    Client Secret</label>
                                <input id="google-client-secret" type="password" wire:model="googleClientSecret"
                                    placeholder="••••••••••••••••"
                                    class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition font-mono" />
                                @error('googleClientSecret')
                                    <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                        <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="button" wire:click="save" wire:loading.attr="disabled"
                        class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold hover:brightness-110 transition disabled:opacity-50"
                        style="color: var(--color-card);">
                        Save
                    </button>
                </div>

                <!-- ===== RIGHT — LIVE PREVIEW ===== -->
                <div class="xl:sticky xl:top-6">
                    <p class="text-xs font-medium text-mist mb-3">Preview</p>
                    <div class="rounded-xl border border-line bg-surface p-6">
                        <div class="mx-auto max-w-sm rounded-xl border border-line bg-card p-6"
                            style="box-shadow: 0 12px 32px -16px var(--card-shadow, rgba(0,0,0,.2));">
                            <p class="font-display text-lg text-ink">Sign in to your workspace</p>
                            <p class="text-xs text-mist mt-1">People, time, and pay — all in one place.</p>

                            <div class="mt-5 space-y-3">
                                <input id="preview-email" type="email" placeholder="you@company.com" disabled
                                    class="w-full rounded-lg border border-line bg-surface px-3 py-2 text-xs text-ink placeholder-mist/60" />
                                <div wire:show="formPassword" id="preview-password-field">
                                    <input id="preview-password" type="password" placeholder="••••••••••" disabled
                                        class="w-full rounded-lg border border-line bg-surface px-3 py-2 text-xs text-ink placeholder-mist/60" />
                                </div>
                                <div id="button-signin"
                                    class="w-full rounded-lg bg-amber py-2 font-semibold text-center text-on-amber text-xs">
                                    {{ $btnPreview }}</div>
                            </div>

                            <div class="space-y-3">
                                @if ($googleSsoEnabled || $passkeyEnabled)
                                    <div class="flex items-center gap-3 my-4">
                                        <div class="h-px flex-1 bg-line"></div>
                                        <span class="text-[10px] text-mist font-mono">or</span>
                                        <div class="h-px flex-1 bg-line"></div>
                                    </div>
                                @endif
                                <div wire:show="googleSsoEnabled">
                                    <div
                                        class="w-full flex items-center justify-center gap-2 rounded-lg border border-line py-2 text-xs font-medium text-ink">
                                        <svg width="12" height="12" viewBox="0 0 48 48">
                                            <path fill="#EA4335"
                                                d="M24 9.5c3.5 0 6.6 1.2 9.1 3.6l6.8-6.8C35.9 2.4 30.4 0 24 0 14.6 0 6.4 5.4 2.5 13.2l7.9 6.1C12.3 13.1 17.6 9.5 24 9.5z" />
                                            <path fill="#4285F4"
                                                d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.5 3-2.2 5.5-4.7 7.2l7.4 5.7c4.3-4 6.8-9.9 6.8-17.4z" />
                                            <path fill="#FBBC05"
                                                d="M10.4 19.3A14.5 14.5 0 0 0 9.5 24c0 1.6.3 3.2.9 4.7l-7.9 6.1A24 24 0 0 1 0 24c0-3.9.9-7.5 2.5-10.7l7.9 6z" />
                                            <path fill="#34A853"
                                                d="M24 48c6.4 0 11.9-2.1 15.9-5.8l-7.4-5.7c-2.1 1.4-4.8 2.3-8.5 2.3-6.4 0-11.7-3.6-13.6-8.8l-7.9 6.1C6.4 42.6 14.6 48 24 48z" />
                                        </svg>
                                        Continue with Google
                                    </div>
                                </div>

                                <button type="button" id="btn-passkey-login" wire:show="passkeyEnabled"
                                    class="w-full flex items-center justify-center gap-2 rounded-lg border border-line py-2 text-xs font-medium text-ink">
                                    <i class="fa-solid fa-key text-xs"></i>
                                    Sign in with passkey
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
