<?php

use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\Permission\Models\Role;

new class extends Component {
    public int $roleId;
    public string $name = '';
    public string $guard_name = 'web';
    public string $created_at = '';

    public function mount($id): void
    {
        $role = Role::findOrFail($id);

        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->created_at = $role->created_at->format('d M Y, H:i');
    }

    #[Computed]
    public function permissions()
    {
        return Role::findOrFail($this->roleId)->permissions()->orderBy('name')->get();
    }

    #[Computed]
    public function usersCount()
    {
        return Role::findOrFail($this->roleId)->users()->count();
    }
};
?>

<div>
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-6">
        <div class="w-full">

            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Role detail</h1>
                    <p class="mt-1 text-sm text-mist">Details of the selected role</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('admin.roles.update', $this->roleId) }}" wire:navigate
                        class="inline-flex items-center gap-2 rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                        <i class="fa-solid fa-pen text-xs"></i>
                        Edit
                    </a>
                    <a href="{{ route('admin.roles.index') }}" wire:navigate
                        class="inline-flex items-center gap-2 rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Back
                    </a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <p class="text-xs font-medium text-mist">Name</p>
                    <p class="mt-2 font-mono text-sm text-ink break-words">{{ $this->name }}</p>
                </div>
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <p class="text-xs font-medium text-mist">Guard</p>
                    <p class="mt-2"><span
                            class="rounded-full bg-surface-2 px-2.5 py-1 text-xs font-mono text-mist">{{ $this->guard_name }}</span>
                    </p>
                </div>
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <p class="text-xs font-medium text-mist">Users</p>
                    <p class="mt-2 font-display text-xl text-ink">{{ $this->usersCount }}</p>
                </div>
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <p class="text-xs font-medium text-mist">Created at</p>
                    <p class="mt-2 font-mono text-xs text-ink">{{ $this->created_at }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-line bg-card overflow-hidden"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <div class="px-5 py-4 border-b border-line flex items-center justify-between">
                    <p class="text-sm font-medium text-ink">Permissions</p>
                    <span class="text-xs rounded-full bg-surface-2 px-2.5 py-1 text-mist">{{ $this->permissions->count() }}</span>
                </div>

                <div class="p-5">
                    @forelse ($this->permissions as $permission)
                        <a href="{{ route('admin.permission.detail', $permission->id) }}" wire:navigate
                            class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-3 py-1.5 text-xs text-ink hover:border-mist hover:bg-surface-2 transition mr-2 mb-2">
                            <i class="fa-solid fa-key text-[10px] text-amber-deep"></i>
                            {{ $permission->name }}
                        </a>
                    @empty
                        <p class="text-sm text-mist text-center py-4">This role has no permissions assigned</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>
