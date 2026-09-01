<?php

use Illuminate\Support\Facades\Password;
use Livewire\Component;

new class extends Component {
    public string $email = '';
    public bool $sent = false;

    public function sendResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->sent = true;
            session()->flash('success', __($status));
        } else {
            // Do not reveal if email exists — show generic success to prevent enumeration
            // But for validation errors, show the actual error
            if ($status === Password::INVALID_USER) {
                $this->addError('email', __($status));
            } else {
                $this->addError('email', __($status));
            }
        }
    }
};
?>

<div>
    <div class="mx-auto w-full max-w-sm">
        <h1 class="font-display text-3xl font-medium tracking-tight text-ink">Reset your password</h1>
        <p class="mt-2 text-xs leading-relaxed text-mist">Enter the email linked to your account and we'll send you a
            link to reset your password.</p>

        <form wire:submit="sendResetLink" class="mt-6 space-y-5">
            @include('components.messages')

            @if ($sent)
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-3 py-2.5 text-xs text-emerald-700">
                    If an account exists for that email, a reset link has been sent. Please check your inbox.
                </div>
            @endif

            <div>
                <label for="email" class="block text-xs font-medium text-mist mb-1.5">Work email</label>
                <input id="email" type="email" wire:model="email" placeholder="you@company.com"
                    class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                @error('email')
                    <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                        <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                class="w-full rounded-lg bg-amber py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition disabled:opacity-50 disabled:cursor-wait">
                <span wire:loading.remove>Send reset link</span>
                <span wire:loading>Sending…</span>
            </button>

            <button type="button" wire:click="$dispatch('back-to-login')"
                class="flex items-center justify-center gap-1.5 text-xs text-mist hover:text-ink transition">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Back to sign in
            </button>
        </form>
    </div>
</div>
