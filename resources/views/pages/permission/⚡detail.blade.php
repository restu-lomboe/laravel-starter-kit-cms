<?php

use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

new class extends Component {
    public int $permissionId;
    public string $name = '';
    public string $description = '';
    public string $page = '';
    public string $feature = '';
    public string $level = '';
    public string $guard_name = 'web';
    public string $created_at = '';

    public function mount($id): void
    {
        $permission = Permission::findOrFail($id);

        $this->permissionId = $permission->id;
        $this->name = $permission->name;
        $this->description = $permission->description ?? '';
        $this->page = $permission->page ?? '';
        $this->feature = $permission->feature ?? '';
        $this->level = $permission->level ?? '';
        $this->guard_name = $permission->guard_name;
        $this->created_at = $permission->created_at->format('d M Y, H:i');
    }

    #[Computed]
    public function roles()
    {
        return Permission::findOrFail($this->permissionId)->roles()->orderBy('name')->get();
    }
};
?>

<div>
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-6">
        <div class="w-full">

            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Permission detail</h1>
                    <p class="mt-1 text-sm text-mist">Details of the selected permission</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('admin.permission.update', $this->permissionId) }}" wire:navigate
                        class="inline-flex items-center gap-2 rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                        <i class="fa-solid fa-pen text-xs"></i>
                        Edit
                    </a>
                    <a href="{{ route('admin.permission.index') }}" wire:navigate
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
                    <p class="text-xs font-medium text-mist">Created at</p>
                    <p class="mt-2 font-mono text-xs text-ink">{{ $this->created_at }}</p>
                </div>
            </div>

            @if ($this->description !== '')
                <div class="mt-4 rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <p class="text-xs font-medium text-mist">Description</p>
                    <p class="mt-2 text-sm text-ink">{{ $this->description }}</p>
                </div>
            @endif

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <p class="text-xs font-medium text-mist">Page</p>
                    <p class="mt-2 font-mono text-sm text-ink break-words">{{ $this->page ?: '—' }}</p>
                </div>
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <p class="text-xs font-medium text-mist">Feature</p>
                    <p class="mt-2 font-mono text-sm text-ink break-words">{{ $this->feature ?: '—' }}</p>
                </div>
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <p class="text-xs font-medium text-mist">Level</p>
                    <p class="mt-2">
                        @if ($this->level !== '')
                            <span
                                class="rounded-full bg-surface-2 px-2.5 py-1 text-xs font-mono text-mist">{{ $this->level }}</span>
                        @else
                            <span class="text-sm text-mist">—</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-line bg-card overflow-hidden"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <div class="px-5 py-4 border-b border-line flex items-center justify-between">
                    <p class="text-sm font-medium text-ink">Assigned to roles</p>
                    <span class="text-xs rounded-full bg-surface-2 px-2.5 py-1 text-mist">{{ $this->roles->count() }}</span>
                </div>

                @forelse ($this->roles as $role)
                    <a href="{{ route('admin.roles.detail', $role->id) }}" wire:navigate
                        class="flex items-center justify-between px-5 py-3.5 hover:bg-surface transition border-b border-line last:border-b-0">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-surface-2 text-amber-deep shrink-0"><i
                                    class="fa-solid fa-user-shield text-xs"></i></span>
                            <p class="text-sm font-medium text-ink">{{ $role->name }}</p>
                        </div>
                        <span class="text-mist"><i class="fa-solid fa-angle-right text-xs"></i></span>
                    </a>
                @empty
                    <div class="px-5 py-10 text-center">
                        <p class="text-sm text-mist">This permission is not assigned to any role yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>
