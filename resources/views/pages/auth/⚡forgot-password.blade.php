<?php

use Livewire\Component;

new class extends Component {

};
?>

<div>
    <div class="mx-auto w-full max-w-sm">
        <h1 class="font-display text-3xl font-medium tracking-tight text-ink">Reset your password</h1>
        <p class="mt-2 text-xs leading-relaxed text-mist">Enter the email linked to your account and we'll send you a
            link to reset your password.</p>

        <form class="mt-6 space-y-5">
            <div>
                <label for="email" class="block text-xs font-medium text-mist mb-1.5">Work email</label>
                <input id="email" type="email" placeholder="you@company.com"
                    class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-amber py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition">
                Send reset link
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
