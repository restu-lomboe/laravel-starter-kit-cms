<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Fortify;
use Laravel\Passkeys\Passkey;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public string $two_factor_code = '';
    public bool $showEnableForm = false;
    public bool $showDisableForm = false;
    public bool $showRecoveryCodes = false;

    public string $newPasskeyName = '';

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (Hash::check($this->current_password, auth()->user()->password)) {
            auth()
                ->user()
                ->update([
                    'password' => Hash::make($this->password),
                ]);

            LivewireAlert::title('Password successfully updated')
                ->success()
                ->toast()
                ->position('center')
                ->timer(2500)
                ->timerProgressBar()
                ->withOptions([
                    'width' => '30%',
                ])
                ->show();

            $this->reset('current_password', 'password', 'password_confirmation');
        } else {
            LivewireAlert::title('Current password is incorrect')
                ->error()
                ->toast()
                ->position('center')
                ->timer(2500)
                ->timerProgressBar()
                ->withOptions([
                    'width' => '30%',
                ])
                ->show();
        }
    }

    #[Computed]
    public function twoFactorConfigured(): bool
    {
        return !is_null(auth()->user()->two_factor_secret);
    }

    #[Computed]
    public function twoFactorConfirmed(): bool
    {
        return !is_null(auth()->user()->two_factor_confirmed_at);
    }

    #[Computed]
    public function qrCodeSvg(): string
    {
        return auth()->user()->twoFactorQrCodeSvg();
    }

    #[Computed]
    public function setupKey(): string
    {
        return Fortify::currentEncrypter()->decrypt(auth()->user()->two_factor_secret);
    }

    #[Computed]
    public function recoveryCodes(): array
    {
        return auth()->user()->recoveryCodes();
    }

    public function enableTwoFactor()
    {
        if (!Hash::check($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', 'The provided password does not match your current password.');

            return;
        }

        app(EnableTwoFactorAuthentication::class)(auth()->user());

        $this->reset('current_password', 'showEnableForm');
        $this->showRecoveryCodes = true;
        unset($this->twoFactorConfigured, $this->qrCodeSvg, $this->setupKey, $this->recoveryCodes);

        LivewireAlert::title('Two-factor authentication enabled')
            ->success()
            ->toast()
            ->position('center')
            ->timer(3000)
            ->timerProgressBar()
            ->withOptions([
                'width' => '30%',
            ])
            ->show();
    }

    public function confirmTwoFactor()
    {
        try {
            app(ConfirmTwoFactorAuthentication::class)(auth()->user(), $this->two_factor_code);
        } catch (ValidationException) {
            $this->addError('two_factor_code', 'The provided two factor authentication code was invalid.');

            return;
        }

        $this->reset('two_factor_code', 'showRecoveryCodes');
        unset($this->twoFactorConfirmed);

        LivewireAlert::title('Two-factor authentication confirmed')
            ->success()
            ->toast()
            ->position('center')
            ->timer(3000)
            ->timerProgressBar()
            ->withOptions([
                'width' => '30%',
            ])
            ->show();
    }

    public function disableTwoFactor()
    {
        if (!Hash::check($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', 'The provided password does not match your current password.');

            return;
        }

        app(DisableTwoFactorAuthentication::class)(auth()->user());

        $this->reset('current_password', 'two_factor_code', 'showDisableForm', 'showRecoveryCodes');
        unset($this->twoFactorConfigured, $this->twoFactorConfirmed, $this->qrCodeSvg, $this->setupKey, $this->recoveryCodes);

        LivewireAlert::title('Two-factor authentication disabled')
            ->success()
            ->toast()
            ->position('center')
            ->timer(2500)
            ->timerProgressBar()
            ->withOptions([
                'width' => '30%',
            ])
            ->show();
    }

    public function regenerateRecoveryCodes()
    {
        app(GenerateNewRecoveryCodes::class)(auth()->user());

        $this->showRecoveryCodes = true;
        unset($this->recoveryCodes);

        LivewireAlert::title('Recovery codes regenerated')
            ->success()
            ->toast()
            ->position('center')
            ->timer(2500)
            ->timerProgressBar()
            ->withOptions([
                'width' => '30%',
            ])
            ->show();
    }

    #[Computed]
    public function passkeys(): \Illuminate\Database\Eloquent\Collection
    {
        return auth()->user()->passkeys()->latest()->get();
    }

    #[On('passkey-registered')]
    public function refreshPasskeys(): void
    {
        unset($this->passkeys);

        LivewireAlert::title('Passkey added successfully')
            ->success()
            ->toast()
            ->position('center')
            ->timer(2500)
            ->timerProgressBar()
            ->withOptions([
                'width' => '30%',
            ])
            ->show();
    }

    public function deletePasskey(int $passkeyId): void
    {
        $passkey = Passkey::where('id', $passkeyId)->where('user_id', auth()->id())->first();

        if (! $passkey) {
            LivewireAlert::title('Passkey not found')
                ->error()
                ->toast()
                ->position('center')
                ->timer(2500)
                ->timerProgressBar()
                ->withOptions([
                    'width' => '30%',
                ])
                ->show();

            return;
        }

        $passkey->delete();
        unset($this->passkeys);

        LivewireAlert::title('Passkey removed')
            ->success()
            ->toast()
            ->position('center')
            ->timer(2500)
            ->timerProgressBar()
            ->withOptions([
                'width' => '30%',
            ])
            ->show();
    }
};
?>

<div>
    <!-- Security panel -->
    <div id="settings-panel-security" class="settings-panel space-y-6">
        <!-- Update password -->
        <div>
            <p class="text-sm font-semibold text-ink">Update password</p>
            <p class="text-xs text-mist mt-0.5">Ensure your account is using a long, random password to stay secure</p>

            <form wire:submit="updatePassword">
                <div class="mt-4 space-y-4 max-w-lg">
                    <div>
                        <label for="current-password" class="block text-xs font-medium text-mist mb-1.5">Current
                            password</label>
                        <div class="relative">
                            <input id="current-password" type="password" wire:model="current_password"
                                class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 pr-10 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition"
                                required />
                            <button type="button"
                                wire:click="$js.togglePasswordField('current-password', $event.currentTarget)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-mist hover:text-ink transition">
                                <i class="fa-solid fa-eye-slash text-xs"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="new-password" class="block text-xs font-medium text-mist mb-1.5">New
                            password</label>
                        <div class="relative">
                            <input id="new-password" type="password" wire:model="password"
                                class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 pr-10 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition"
                                required />
                            <button type="button"
                                wire:click="$js.togglePasswordField('new-password', $event.currentTarget)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-mist hover:text-ink transition">
                                <i class="fa-solid fa-eye-slash text-xs"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="confirm-password" class="block text-xs font-medium text-mist mb-1.5">Confirm
                            password</label>
                        <div class="relative">
                            <input id="confirm-password" type="password" wire:model="password_confirmation"
                                class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 pr-10 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition"
                                required />
                            <button type="button"
                                wire:click="$js.togglePasswordField('confirm-password', $event.currentTarget)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-mist hover:text-ink transition">
                                <i class="fa-solid fa-eye-slash text-xs"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                        class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold hover:brightness-110 transition disabled:opacity-50 disabled:cursor-wait"
                        style="color: var(--color-card);">
                        Update password
                    </button>
                </div>
            </form>
        </div>

        <!-- Two-factor authentication -->
        <div class="border-t border-line pt-6">
            <p class="text-sm font-semibold text-ink">Two-factor authentication</p>
            <p class="text-xs text-mist mt-0.5">Manage your two-factor authentication settings</p>

            @if ($this->twoFactorConfigured && !$this->twoFactorConfirmed)
                <div class="mt-4 max-w-lg rounded-xl border border-line bg-surface p-6">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-surface-2 text-amber">
                            <i class="fa-solid fa-qrcode text-sm"></i>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-ink">Confirm two-factor authentication</p>
                            <p class="text-xs text-mist mt-0.5">Scan the QR code with your authenticator app, then enter
                                the 6-digit code below to finish enabling.</p>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-center rounded-lg border border-line bg-surface-2 p-4">
                        <div class="bg-white rounded-md p-2">
                            {!! $this->qrCodeSvg !!}
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-xs font-medium text-mist mb-1.5">Setup key</p>
                        <input type="text" readonly value="{{ $this->setupKey }}" onfocus="this.select()"
                            class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm font-mono tracking-wider text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                    </div>

                    <form wire:submit="confirmTwoFactor" class="mt-4">
                        <label for="two-factor-code" class="block text-xs font-medium text-mist mb-1.5">Authentication
                            code</label>
                        <div class="flex gap-2">
                            <input id="two-factor-code" type="text" wire:model="two_factor_code" placeholder="000000"
                                inputmode="numeric" maxlength="6" autocomplete="one-time-code"
                                class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                            <button type="submit" wire:loading.attr="disabled"
                                class="shrink-0 rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold hover:brightness-110 transition disabled:opacity-50 disabled:cursor-wait"
                                style="color: var(--color-card);">
                                Confirm
                            </button>
                        </div>
                        @error('two_factor_code')
                            <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </form>

                    <div class="mt-4 rounded-lg border border-amber/30 bg-amber/10 p-4">
                        <p class="text-xs font-medium text-amber">Recovery codes</p>
                        <p class="text-xs text-mist mt-1 leading-relaxed">Store these recovery codes in a safe place.
                            Each code can only be used once if you lose access to your authenticator app.</p>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            @foreach ($this->recoveryCodes as $code)
                                <code
                                    class="rounded bg-surface px-2 py-1 text-xs font-mono text-ink">{{ $code }}</code>
                            @endforeach
                        </div>
                    </div>
                </div>
            @elseif ($this->twoFactorConfigured && $this->twoFactorConfirmed)
                <div class="mt-4 max-w-lg rounded-xl border border-line bg-surface p-6">
                    <div class="flex items-center gap-2">
                        <svg class="shrink-0 size-10 text-emerald-600" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path d="M0 0h24v24H0z" fill="none" />
                            <path fill="currentColor"
                                d="m20.42 6.11l-7.97-4c-.28-.14-.62-.14-.9 0l-7.97 4c-.31.15-.51.45-.55.79c-.01.11-.96 10.76 8.55 15.01a.98.98 0 0 0 .82 0C21.91 17.66 20.97 7 20.95 6.9a.98.98 0 0 0-.55-.79ZM12 19.9C5.26 16.63 4.94 9.64 5 7.64l7-3.51l7 3.51c.04 1.99-.33 9.02-7 12.26" />
                            <path fill="currentColor"
                                d="m11 12.59l-1.29-1.3l-1.42 1.42l2.71 2.7l4.71-4.7l-1.42-1.42z" />
                        </svg>

                        <div>
                            <p class="text-sm font-medium text-ink">Two-factor authentication is enabled</p>
                            <p class="text-xs text-mist mt-0.5">Your account is protected by a TOTP authenticator app.
                            </p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-medium text-mist">Recovery codes</p>
                            <button type="button" wire:click="regenerateRecoveryCodes"
                                class="text-xs font-medium text-amber hover:underline transition">
                                Regenerate
                            </button>
                        </div>
                        @if ($this->showRecoveryCodes)
                            <p class="text-xs text-mist mt-1 leading-relaxed">Store these recovery codes in a safe
                                place. Each code can only be used once.</p>
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                @foreach ($this->recoveryCodes as $code)
                                    <code
                                        class="rounded bg-surface px-2 py-1 text-xs font-mono text-ink">{{ $code }}</code>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-mist mt-1.5">Recovery codes are hidden. Click regenerate to view a
                                new set.</p>
                        @endif
                    </div>

                    <button type="button" wire:click="$set('showDisableForm', true)"
                        class="mt-4 inline-flex items-center gap-1.5 text-xs font-medium hover:underline transition"
                        style="color:#ef4444;">
                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                        Disable two-factor authentication
                    </button>

                    @if ($this->showDisableForm)
                        <form wire:submit="disableTwoFactor" class="mt-3">
                            <label for="confirm-password-2fa" class="block text-xs font-medium text-mist mb-1.5">Enter
                                your current password to confirm</label>
                            <div class="flex gap-2">
                                <input id="confirm-password-2fa" type="password" wire:model="current_password"
                                    class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                                <button type="submit" wire:loading.attr="disabled"
                                    class="shrink-0 rounded-lg px-4 py-2.5 text-sm font-semibold hover:brightness-110 transition disabled:opacity-50 disabled:cursor-wait"
                                    style="color:#ffffff; background-color:#ef4444;">
                                    Disable
                                </button>
                            </div>
                            @error('current_password')
                                <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </form>
                    @endif
                </div>
            @else
                <div class="mt-4 max-w-lg rounded-xl border border-line bg-surface p-6">
                    <div class="flex items-center gap-2">
                        <span class="flex size-10 items-center justify-center text-mist">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-ink">Two-factor authentication is not enabled</p>
                            <p class="text-xs text-mist mt-0.5 text-justify">When you enable two-factor authentication,
                                you will be
                                prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported
                                application on your phone.</p>
                        </div>
                    </div>

                    <button type="button" wire:click="$set('showEnableForm', true)"
                        class="mt-4 rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold hover:brightness-110 transition"
                        style="color: var(--color-card);">
                        Enable 2FA
                    </button>

                    @if ($this->showEnableForm)
                        <form wire:submit="enableTwoFactor" class="mt-3">
                            <label for="confirm-password-2fa-enable"
                                class="block text-xs font-medium text-mist mb-1.5">Enter
                                your current password to confirm</label>
                            <div class="flex gap-2">
                                <input id="confirm-password-2fa-enable" type="password" wire:model="current_password"
                                    class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                                <button type="submit" wire:loading.attr="disabled"
                                    class="shrink-0 rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold hover:brightness-110 transition disabled:opacity-50 disabled:cursor-wait"
                                    style="color: var(--color-card);">
                                    Enable
                                </button>
                            </div>
                            @error('current_password')
                                <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </form>
                    @endif
                </div>
            @endif
        </div>

        <!-- Passkeys -->
        <div class="border-t border-line pt-6">
            <p class="text-sm font-semibold text-ink">Passkeys</p>
            <p class="text-xs text-mist mt-0.5">Manage your passkeys for passwordless sign-in</p>

            @if ($this->passkeys->isEmpty())
                <div
                    class="mt-4 max-w-lg rounded-xl border border-line bg-surface px-6 py-8 flex flex-col items-center text-center">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-surface-2 text-mist mb-3">
                        <i class="fa-solid fa-key"></i>
                    </span>
                    <p class="text-sm font-medium text-ink">No passkeys yet</p>
                    <p class="text-xs text-mist mt-1">Add a passkey to sign in without a password</p>
                </div>
            @else
                <div class="mt-4 max-w-lg space-y-2">
                    @foreach ($this->passkeys as $passkey)
                        <div class="flex items-center justify-between rounded-xl border border-line bg-surface px-4 py-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-mist shrink-0">
                                    <i class="fa-solid fa-key text-xs"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-ink truncate">{{ $passkey->name }}</p>
                                    <p class="text-xs text-mist truncate">
                                        {{ $passkey->authenticator ?? 'Passkey' }}
                                        · {{ $passkey->created_at->format('d M Y') }}
                                        @if ($passkey->last_used_at)
                                            · last used {{ $passkey->last_used_at->diffForHumans() }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <button type="button" wire:click="deletePasskey({{ $passkey->id }})"
                                wire:confirm="Remove this passkey? You can always add it again."
                                class="ml-3 shrink-0 inline-flex items-center gap-1.5 text-xs font-medium hover:underline transition"
                                style="color:#ef4444;">
                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                Remove
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-4 max-w-lg rounded-xl border border-line bg-surface p-4">
                <label for="new-passkey-name" class="block text-xs font-medium text-mist mb-1.5">New passkey name</label>
                <div class="flex gap-2">
                    <input id="new-passkey-name" type="text" wire:model="newPasskeyName" placeholder="My MacBook"
                        class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                    <button type="button" id="btn-add-passkey" onclick="window.registerPasskey()"
                        class="shrink-0 inline-flex items-center gap-2 rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold hover:brightness-110 transition disabled:opacity-50 disabled:cursor-wait"
                        style="color: var(--color-card);">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Add passkey
                    </button>
                </div>
                <p class="text-xs text-mist mt-2">You will be prompted by your browser to create a passkey. Works with Touch ID, Face ID, Windows Hello, etc.</p>
                <p id="passkey-error" class="hidden mt-2 text-xs flex items-center gap-1" style="color:#ef4444;"></p>
            </div>
        </div>
    </div>
</div>

<script>
    this.$js.togglePasswordField = (inputId, btn) => {
        console.log(inputId);
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        var isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('fa-eye-slash', !isPassword);
        icon.classList.toggle('fa-eye', isPassword);
    }
</script>

<script>
    window.registerPasskey = async () => {
        const input = document.getElementById('new-passkey-name');
        const errorEl = document.getElementById('passkey-error');
        const btn = document.getElementById('btn-add-passkey');
        if (!input || !btn) return;
        errorEl.classList.add('hidden');
        errorEl.textContent = '';
        const name = input.value.trim() || input.placeholder || 'Passkey';
        const Passkeys = window.Passkeys;
        if (!Passkeys) {
            errorEl.textContent = 'Passkeys not ready. Please refresh the page.';
            errorEl.classList.remove('hidden');
            return;
        }
        btn.disabled = true;
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Creating…';
        try {
            await Passkeys.register({ name });
            if (window.Livewire) {
                window.Livewire.dispatch('passkey-registered');
            }
            input.value = '';
            input.dispatchEvent(new Event('input'));
        } catch (e) {
            const msg = e?.message || 'Failed to create passkey. Make sure your device supports WebAuthn and try again.';
            errorEl.textContent = msg;
            errorEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = original;
        }
    };
</script>
