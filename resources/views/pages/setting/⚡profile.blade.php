<?php

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

new class extends Component {
    public $tab = 'profile';

    public $name;
    public $email;

    public function mount()
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function getTab($tab)
    {
        $this->tab = $tab;
    }

    public function update()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        auth()
            ->user()
            ->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

        LivewireAlert::title('Profile successfully updated')
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

    public function delete()
    {
        // logout and delete user login
        $user = \Auth::user();

        \Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $user->delete();

        // redirect to login page
        return redirect()->route('login')->with('success', 'Account deleted successfully');

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
                        <form wire:submit="update">
                            <div id="settings-panel-profile" class="settings-panel space-y-6">
                                <div>
                                    <p class="text-sm font-semibold text-ink">Profile</p>
                                    <p class="text-xs text-mist mt-0.5">Update your name and email address</p>
                                </div>

                                <div>
                                    <label for="name"
                                        class="block text-xs font-medium text-mist mb-1.5">Name</label>
                                    <input id="name" type="text" wire:model="name" value="Hiroko Pearson"
                                        class="w-full max-w-lg rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition"
                                         required />
                                    @error('name')
                                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email"
                                        class="block text-xs font-medium text-mist mb-1.5">Email</label>
                                    <input id="email" type="email" wire:model="email" value="hapib@mailinator.com"
                                        class="w-full max-w-lg rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink outline-none focus:border-amber focus:ring-1 focus:ring-amber transition"
                                        required />
                                    @error('email')
                                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>

                                <button type="submit" wire:loading.attr="disabled"
                                    class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold cursor-pointer hover:brightness-110 transition disabled:opacity-50 disabled:cursor-wait"
                                    style="color: var(--color-card);">
                                    Save
                                </button>

                                <div class="border-t border-line pt-6">
                                    <p class="text-sm font-semibold text-ink">Delete account</p>
                                    <p class="text-xs text-mist mt-0.5">Delete your account and all of its resources</p>
                                    <button type="button" wire:click="delete" wire:loading.attr="disabled"
                                        wire:confirm.prompt="Are you sure?\n\nType DELETE to confirm|DELETE"
                                        class="mt-3 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-wait">
                                        Delete account
                                    </button>
                                </div>
                            </div>
                        </form>
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
