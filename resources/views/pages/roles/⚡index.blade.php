<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new class extends Component {
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Computed]
    public function roles()
    {
        return Role::query()
            ->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))
            ->withCount('permissions')
            ->withCount('users')
            ->orderBy('name')
            ->paginate(10);
    }

    public function delete(int $id): void
    {
        Role::findOrFail($id)->delete();

        session()->flash('success', 'Role deleted successfully.');

        $this->redirectRoute('admin.roles.index');
    }
};
?>

<div>
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-6">

        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Roles</h1>
                <p class="mt-1 text-sm text-mist">Group permissions into roles and assign them to users</p>
            </div>
            <a href="{{ route('admin.roles.create') }}" wire:navigate
                class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-amber px-4 py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition">
                <i class="fa-solid fa-plus text-xs"></i>
                New role
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-line bg-card p-4 flex items-center gap-3"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-amber-deep shrink-0"><i
                        class="fa-solid fa-circle-check"></i></span>
                <p class="text-sm font-medium text-ink">{{ session('success') }}</p>
            </div>
        @endif

        <div class="rounded-xl border border-line bg-card overflow-hidden"
            style="box-shadow: 0 8px 24px -12px var(--card-shadow);">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-line">
                <p class="text-sm font-medium text-ink">{{ $this->roles->total() }} total</p>
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-mist text-xs"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search roles…"
                        class="field-input pl-9" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-mist border-b border-line">
                            <th class="font-normal px-5 py-2.5">Name</th>
                            <th class="font-normal px-5 py-2.5">Guard</th>
                            <th class="font-normal px-5 py-2.5">Permissions</th>
                            <th class="font-normal px-5 py-2.5">Users</th>
                            <th class="font-normal px-5 py-2.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($this->roles as $role)
                            <tr class="hover:bg-surface transition">
                                <td class="px-5 py-3 font-medium text-ink">{{ $role->name }}</td>
                                <td class="px-5 py-3">
                                    <span class="rounded-full bg-surface-2 px-2.5 py-1 text-xs font-mono text-mist">
                                        {{ $role->guard_name }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="rounded-full bg-surface-2 px-2.5 py-1 text-xs text-mist">
                                        {{ $role->permissions_count }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="rounded-full bg-surface-2 px-2.5 py-1 text-xs text-mist">
                                        {{ $role->users_count }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.roles.detail', $role->id) }}" wire:navigate
                                            title="Detail"
                                            class="inline-flex size-8 items-center justify-center rounded-lg text-mist hover:text-ink hover:bg-surface-2 transition">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.update', $role->id) }}" wire:navigate
                                            title="Edit"
                                            class="inline-flex size-8 items-center justify-center rounded-lg text-mist hover:text-ink hover:bg-surface-2 transition">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <button type="button" wire:click="delete({{ $role->id }})"
                                            wire:confirm="Delete role \"{{ $role->name }}\"? This cannot be undone."
                                            title="Delete"
                                            class="inline-flex size-8 items-center justify-center rounded-lg text-mist hover:text-red-600 hover:bg-surface-2 transition">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center">
                                    <p class="text-sm text-mist">No roles found</p>
                                    <a href="{{ route('admin.roles.create') }}" wire:navigate
                                        class="mt-2 inline-flex items-center gap-2 text-xs text-amber-deep hover:text-ink transition">
                                        <i class="fa-solid fa-plus text-[10px]"></i>
                                        Create the first role
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($this->roles->hasPages())
                <div class="px-5 py-4 border-t border-line">
                    {{ $this->roles->links() }}
                </div>
            @endif
        </div>
    </main>
</div>