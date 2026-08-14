<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <div class="mx-auto w-full max-w-sm">
        <h1 class="font-display text-3xl font-medium tracking-tight text-ink">Set a new password</h1>
        <p class="mt-2 text-xs leading-relaxed text-mist">Your new password must be different from previously used
            passwords.</p>

        <form class="mt-6 space-y-5">
            <div>
                <label for="new-password" class="block text-xs font-medium text-mist mb-1.5">New password</label>
                <input id="new-password" type="password" placeholder="••••••••••"
                    class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
            </div>

            <div>
                <label for="confirm-password" class="block text-xs font-medium text-mist mb-1.5">Confirm new
                    password</label>
                <input id="confirm-password" type="password" placeholder="••••••••••"
                    class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
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

            <button type="submit"
                class="w-full rounded-lg bg-amber py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition">
                Reset password
            </button>
        </form>
    </div>
</div>
