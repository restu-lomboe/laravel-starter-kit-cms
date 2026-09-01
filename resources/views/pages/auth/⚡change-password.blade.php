<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component {
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $this->token = (string) request()->query('token', '');
        $this->email = (string) request()->query('email', '');

        if (auth()->check()) {
            $this->redirectRoute('admin.dashboard', navigate: true);
        }
    }

    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/[0-9\W]/'],
        ], [
            'password.regex' => 'Password must contain at least a number or symbol.',
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('success', __($status));

            $this->redirectRoute('login', navigate: true);
        } else {
            $this->addError('email', __($status));
        }
    }
};
?>

<div>
    <div class="mx-auto w-full max-w-sm">
        <h1 class="font-display text-3xl font-medium tracking-tight text-ink">Set a new password</h1>
        <p class="mt-2 text-xs leading-relaxed text-mist">Your new password must be different from previously used
            passwords.</p>

        <form wire:submit="resetPassword" class="mt-6 space-y-5">
            @include('components.messages')

            <input type="hidden" wire:model="token" />
            <input type="hidden" wire:model="email" />

            <div>
                <label for="reset-email" class="block text-xs font-medium text-mist mb-1.5">Work email</label>
                <input id="reset-email" type="email" wire:model="email" placeholder="you@company.com"
                    class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                @error('email')
                    <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                        <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="new-password" class="block text-xs font-medium text-mist mb-1.5">New password</label>
                <div class="relative">
                    <input id="new-password" type="password" wire:model="password" placeholder="••••••••••"
                        class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 pr-10 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                    <button type="button" wire:click="$js.togglePasswordField('new-password', $event.currentTarget)"
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
                <label for="confirm-password" class="block text-xs font-medium text-mist mb-1.5">Confirm new password</label>
                <div class="relative">
                    <input id="confirm-password" type="password" wire:model="password_confirmation" placeholder="••••••••••"
                        class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 pr-10 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                    <button type="button" wire:click="$js.togglePasswordField('confirm-password', $event.currentTarget)"
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

            <ul class="space-y-1 text-xs text-mist">
                <li class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-amber-deep" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                    At least 8 characters
                </li>
                <li class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-amber-deep" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                    Contains a number or symbol
                </li>
            </ul>

            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                class="w-full rounded-lg bg-amber py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition disabled:opacity-50 disabled:cursor-wait">
                <span wire:loading.remove>Reset password</span>
                <span wire:loading>Resetting…</span>
            </button>

            <button type="button" wire:click="$dispatch('back-to-login')"
                class="flex w-full items-center justify-center gap-1.5 text-xs text-mist hover:text-ink transition">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Back to sign in
            </button>
        </form>
    </div>
</div>

<script>
    this.$js.togglePasswordField = (inputId, btn) => {
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        var isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('fa-eye-slash', !isPassword);
        icon.classList.toggle('fa-eye', isPassword);
    }
</script>
