<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public int $userId;
    public string $name = '';
    public string $email = '';
    public string $created_at = '';

    public function mount($id): void
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->created_at = $user->created_at->format('d M Y, H:i');
    }

    #[Computed]
    public function roles()
    {
        return User::findOrFail($this->userId)->roles()->orderBy('name')->get();
    }
};
?>

<div>
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-6">
        <div class="w-full">

            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span
                        class="flex size-12 items-center justify-center rounded-full bg-surface-2 text-sm font-medium text-ink shrink-0">
                        {{ str($this->name)->substr(0, 2)->upper() }}
                    </span>
                    <div>
                        <h1 class="font-display text-2xl font-medium tracking-tight text-ink">{{ $this->name }}</h1>
                        <p class="mt-1 text-sm text-mist font-mono">{{ $this->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    @can('users.update')
                        <a href="{{ route('admin.user.update', $this->userId) }}" wire:navigate
                            class="inline-flex items-center gap-2 rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                            Edit
                        </a>
                    @endcan
                    <a href="{{ route('admin.user.index') }}" wire:navigate
                        class="inline-flex items-center gap-2 rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Back
                    </a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <p class="text-xs font-medium text-mist">Name</p>
                    <p class="mt-2 text-sm text-ink">{{ $this->name }}</p>
                </div>
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <p class="text-xs font-medium text-mist">Email</p>
                    <p class="mt-2 font-mono text-xs text-ink break-words">{{ $this->email }}</p>
                </div>
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <p class="text-xs font-medium text-mist">Member since</p>
                    <p class="mt-2 font-mono text-xs text-ink">{{ $this->created_at }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-line bg-card overflow-hidden"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <div class="px-5 py-4 border-b border-line flex items-center justify-between">
                    <p class="text-sm font-medium text-ink">Assigned roles</p>
                    <span class="text-xs rounded-full bg-surface-2 px-2.5 py-1 text-mist">{{ $this->roles->count() }}</span>
                </div>

                <div class="p-5">
                    @forelse ($this->roles as $role)
                        <a href="{{ route('admin.roles.detail', $role->id) }}" wire:navigate
                            class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-3 py-1.5 text-xs text-ink hover:border-mist hover:bg-surface-2 transition mr-2 mb-2">
                            <i class="fa-solid fa-user-shield text-[10px] text-amber-deep"></i>
                            {{ $role->name }}
                        </a>
                    @empty
                        <p class="text-sm text-mist text-center py-4">This user has no roles assigned</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>
