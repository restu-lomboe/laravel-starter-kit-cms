<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <!-- Security panel -->
    <div id="settings-panel-security" class="settings-panel space-y-6">
        <!-- Update password -->
        <div>
            <p class="text-sm font-semibold text-ink">Update password</p>
            <p class="text-xs text-mist mt-0.5">Ensure your account is using a long, random password to stay secure</p>

            <div class="mt-4 space-y-4 max-w-md">
                <div>
                    <label for="current-password" class="block text-xs font-medium text-mist mb-1.5">Current
                        password</label>
                    <div class="relative">
                        <input id="current-password" type="password"
                            class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 pr-10 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                        <button type="button" wire:click="$js.togglePasswordField('current-password', $event.currentTarget)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-mist hover:text-ink transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="new-password" class="block text-xs font-medium text-mist mb-1.5">New password</label>
                    <div class="relative">
                        <input id="new-password" type="password"
                            class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 pr-10 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                        <button type="button" wire:click="$js.togglePasswordField('new-password', $event.currentTarget)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-mist hover:text-ink transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="confirm-password" class="block text-xs font-medium text-mist mb-1.5">Confirm
                        password</label>
                    <div class="relative">
                        <input id="confirm-password" type="password"
                            class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 pr-10 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                        <button type="button" wire:click="$js.togglePasswordField('confirm-password', $event.currentTarget)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-mist hover:text-ink transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <button type="button"
                    class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold hover:brightness-110 transition"
                    style="color: var(--color-card);">
                    Save
                </button>
            </div>
        </div>

        <!-- Two-factor authentication -->
        <div class="border-t border-line pt-6">
            <p class="text-sm font-semibold text-ink">Two-factor authentication</p>
            <p class="text-xs text-mist mt-0.5">Manage your two-factor authentication settings</p>
            <p class="text-xs text-mist mt-2 max-w-md leading-relaxed">
                When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin
                can be retrieved from a TOTP-supported application on your phone.
            </p>
            <button type="button"
                class="mt-3 rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold hover:brightness-110 transition"
                style="color: var(--color-card);">
                Enable 2FA
            </button>
        </div>

        <!-- Passkeys -->
        <div class="border-t border-line pt-6">
            <p class="text-sm font-semibold text-ink">Passkeys</p>
            <p class="text-xs text-mist mt-0.5">Manage your passkeys for passwordless sign-in</p>

            <div
                class="mt-4 max-w-md rounded-xl border border-line bg-surface px-6 py-8 flex flex-col items-center text-center">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-surface-2 text-mist mb-3">
                    <i class="fa-solid fa-key"></i>
                </span>
                <p class="text-sm font-medium text-ink">No passkeys yet</p>
                <p class="text-xs text-mist mt-1">Add a passkey to sign in without a password</p>
            </div>

            <button type="button"
                class="mt-4 flex items-center gap-2 rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold hover:brightness-110 transition"
                style="color: var(--color-card);">
                <i class="fa-solid fa-plus text-xs"></i>
                Add passkey
            </button>
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
        icon.classList.toggle('fa-eye', !isPassword);
        icon.classList.toggle('fa-eye-slash', isPassword);
    }
</script>
