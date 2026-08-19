<?php

use Livewire\Component;

new class extends Component {
    public $tab = 'profile';

    public function getTab($tab)
    {
        $this->tab = $tab;
    }
};
?>

<div>
    <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-2">
        <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Settings</h1>
        <p class="mt-1 text-sm text-mist">Manage your profile and account settings</p>

        <div class="mt-6 border-t border-line pt-6 flex flex-col sm:flex-row gap-8">

            <!-- tabs -->
            <nav class="w-full sm:w-56 shrink-0 space-y-0.5">
                <button type="button" wire:click="getTab('profile')"
                    class="settings-tab w-full text-left rounded-md px-3 py-2 text-sm {{ $tab == 'profile' ? 'bg-surface-2 text-ink' : 'text-mist hover:text-ink hover:bg-surface-2' }} font-medium transition">
                    Profile
                </button>
                <button type="button" wire:click="getTab('security')"
                    class="settings-tab w-full text-left rounded-md px-3 py-2 text-sm {{ $tab == 'security' ? 'bg-surface-2 text-ink' : 'text-mist hover:text-ink hover:bg-surface-2' }} transition">
                    Security
                </button>
                <button type="button" wire:click="getTab('authentication')"
                    class="settings-tab w-full text-left rounded-md px-3 py-2 text-sm {{ $tab == 'authentication' ? 'bg-surface-2 text-ink' : 'text-mist hover:text-ink hover:bg-surface-2' }} transition">
                    Authentication UI
                </button>
            </nav>

            <!-- panels -->
            <div class="flex-1 min-w-0">

                @if ($tab == 'profile')
                    <div wire:transition>
                        <!-- Profile panel -->
                        <div id="settings-panel-profile" class="settings-panel space-y-6">
                            <div>
                                <p class="text-sm font-semibold text-ink">Profile</p>
                                <p class="text-xs text-mist mt-0.5">Update your name and email address</p>
                            </div>

                            <div>
                                <label for="name" class="block text-xs font-medium text-mist mb-1.5">Name</label>
                                <input id="name" type="text" value="Hiroko Pearson"
                                    class="w-full max-w-md rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-medium text-mist mb-1.5">Email</label>
                                <input id="email" type="email" value="hapib@mailinator.com"
                                    class="w-full max-w-md rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                            </div>

                            <button type="button"
                                class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold hover:brightness-110 transition"
                                style="color: var(--color-card);">
                                Save
                            </button>

                            <div class="border-t border-line pt-6">
                                <p class="text-sm font-semibold text-ink">Delete account</p>
                                <p class="text-xs text-mist mt-0.5">Delete your account and all of its resources</p>
                                <button type="button" wire:click="delete"
                                    wire:confirm.prompt="Are you sure?\n\nType DELETE to confirm|DELETE"
                                    class="mt-3 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition">
                                    Delete account
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($tab == 'security')
                    <div wire:transition>
                        <livewire:pages::setting.security />
                    </div>
                @endif

                @if ($tab == 'authentication')
                    <div wire:transition>
                        <livewire:pages::setting.authentication-page />
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
