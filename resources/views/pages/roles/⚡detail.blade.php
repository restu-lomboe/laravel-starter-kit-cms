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
        return Role::findOrFail($this->roleId)->permissions()
            ->orderBy('page')
            ->orderBy('feature')
            ->orderBy('level')
            ->get();
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
                    @can('roles.update')
                        <a href="{{ route('admin.roles.update', $this->roleId) }}" wire:navigate
                            class="inline-flex items-center gap-2 rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                            Edit
                        </a>
                    @endcan
                    <a href="{{ route('admin.roles.index') }}" wire:navigate
                        class="inline-flex items-center gap-2 rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Back
                    </a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <div class="flex items-center gap-2">
                        <span
                            class="flex size-7 items-center justify-center rounded-lg bg-surface-2 text-amber-deep shrink-0"><i
                                class="fa-solid fa-user-shield text-xs"></i></span>
                        <p class="text-xs font-medium text-mist">Name</p>
                    </div>
                    <p class="mt-3 font-mono text-sm text-ink break-words">{{ $this->name }}</p>
                </div>
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <div class="flex items-center gap-2">
                        <span
                            class="flex size-7 items-center justify-center rounded-lg bg-surface-2 text-amber-deep shrink-0"><i
                                class="fa-solid fa-shield-halved text-xs"></i></span>
                        <p class="text-xs font-medium text-mist">Guard</p>
                    </div>
                    <p class="mt-3"><span
                            class="rounded-full bg-surface-2 px-2.5 py-1 text-xs font-mono text-mist">{{ $this->guard_name }}</span>
                    </p>
                </div>
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <div class="flex items-center gap-2">
                        <span
                            class="flex size-7 items-center justify-center rounded-lg bg-surface-2 text-amber-deep shrink-0"><i
                                class="fa-solid fa-users text-xs"></i></span>
                        <p class="text-xs font-medium text-mist">Users</p>
                    </div>
                    <p class="mt-3 font-display text-2xl text-ink">{{ $this->usersCount }}</p>
                </div>
                <div class="rounded-xl border border-line bg-card p-5"
                    style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                    <div class="flex items-center gap-2">
                        <span
                            class="flex size-7 items-center justify-center rounded-lg bg-surface-2 text-amber-deep shrink-0"><i
                                class="fa-solid fa-calendar text-xs"></i></span>
                        <p class="text-xs font-medium text-mist">Created at</p>
                    </div>
                    <p class="mt-3 font-mono text-xs text-ink">{{ $this->created_at }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-line bg-card overflow-hidden"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <div class="px-5 py-4 border-b border-line flex items-center justify-between">
                    <p class="text-sm font-medium text-ink">Permissions</p>
                    <span
                        class="text-xs rounded-full bg-surface-2 px-2.5 py-1 text-mist">{{ $this->permissions->count() }}</span>
                </div>

                @forelse ($this->permissions->groupBy('page') as $page => $pagePermissions)
                    <div class="px-5 py-4 border-b border-line last:border-b-0">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-mist mb-3">{{ $page ?: 'General' }}
                        </h3>
                        <div class="space-y-3">
                            @foreach ($pagePermissions->groupBy('feature') as $feature => $featurePermissions)
                                <div>
                                    <p class="text-xs font-medium text-mist mb-2">{{ $feature ?: 'No Feature' }}</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($featurePermissions as $permission)
                                            <a href="{{ route('admin.permission.detail', $permission->id) }}"
                                                wire:navigate
                                                class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-3 py-1.5 text-xs text-ink hover:border-amber-deep hover:bg-surface-2 transition">
                                                <i class="fa-solid fa-key text-[10px] text-amber-deep"></i>
                                                {{ $permission->name }}
                                                @if ($permission->level)
                                                    <span
                                                        class="rounded-full bg-surface-2 px-2 py-0.5 font-mono text-[10px] text-mist">{{ $permission->level }}</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-mist text-center py-10">This role has no permissions assigned</p>
                @endforelse
            </div>
        </div>
    </main>
</div>
