<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Computed]
    public function users()
    {
        return User::query()
            ->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%')->orWhere('email', 'like', '%'.$this->search.'%'))
            ->with('roles')
            ->orderBy('name')
            ->paginate(10);
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('users.delete'), 403);

        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            session()->flash('error', 'You cannot delete your own account.');

            return;
        }

        \DB::beginTransaction();
        try {
            $user->syncRoles([]);
            $user->delete();
            \DB::commit();

            session()->flash('success', 'User deleted successfully.');
            $this->redirectRoute('admin.user.index');
        } catch (\Throwable $th) {
            \DB::rollBack();
            session()->flash('error', 'Failed to delete user. '.$th->getMessage());
        }
    }
};
?>

<div>
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-6">

        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Users</h1>
                <p class="mt-1 text-sm text-mist">Manage users and their assigned roles</p>
            </div>
            @can('users.create')
                <a href="{{ route('admin.user.create') }}" wire:navigate
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-amber px-4 py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    New user
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-line bg-card p-4 flex items-center gap-3"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-amber-deep shrink-0"><i
                        class="fa-solid fa-circle-check"></i></span>
                <p class="text-sm font-medium text-ink">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-line bg-card p-4 flex items-center gap-3"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-red-600 shrink-0"><i
                        class="fa-solid fa-circle-exclamation"></i></span>
                <p class="text-sm font-medium text-ink">{{ session('error') }}</p>
            </div>
        @endif

        <div class="rounded-xl border border-line bg-card overflow-hidden"
            style="box-shadow: 0 8px 24px -12px var(--card-shadow);">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-line">
                <p class="text-sm font-medium text-ink">{{ $this->users->total() }} total</p>
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-mist text-xs"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name or email…"
                        class="field-input pl-9" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-mist border-b border-line">
                            <th class="font-normal px-5 py-2.5">Name</th>
                            <th class="font-normal px-5 py-2.5">Email</th>
                            <th class="font-normal px-5 py-2.5">Roles</th>
                            <th class="font-normal px-5 py-2.5">Created</th>
                            <th class="font-normal px-5 py-2.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($this->users as $user)
                            <tr class="hover:bg-surface transition">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex size-8 items-center justify-center rounded-full bg-surface-2 text-xs font-medium text-ink shrink-0">
                                            {{ str($user->name)->substr(0, 2)->upper() }}
                                        </span>
                                        <span class="font-medium text-ink">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-mist font-mono text-xs">{{ $user->email }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($user->roles as $role)
                                            <span class="rounded-full bg-surface-2 px-2.5 py-1 text-xs text-mist">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-mist">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-mist font-mono text-xs">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        @can('users.detail')
                                            <a href="{{ route('admin.user.detail', $user->id) }}" wire:navigate
                                                title="Detail"
                                                class="inline-flex size-8 items-center justify-center rounded-lg text-mist hover:text-ink hover:bg-surface-2 transition">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </a>
                                        @endcan
                                        @can('users.update')
                                            <a href="{{ route('admin.user.update', $user->id) }}" wire:navigate
                                                title="Edit"
                                                class="inline-flex size-8 items-center justify-center rounded-lg text-mist hover:text-ink hover:bg-surface-2 transition">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>
                                        @endcan
                                        @can('users.delete')
                                            <button type="button" wire:click="delete({{ $user->id }})"
                                                wire:confirm="Delete user \"{{ $user->name }}\"? This cannot be undone."
                                                title="Delete"
                                                class="inline-flex size-8 items-center justify-center rounded-lg text-mist hover:text-red-600 hover:bg-surface-2 transition">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center">
                                    <p class="text-sm text-mist">No users found</p>
                                    @can('users.create')
                                        <a href="{{ route('admin.user.create') }}" wire:navigate
                                            class="mt-2 inline-flex items-center gap-2 text-xs text-amber-deep hover:text-ink transition">
                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                            Create the first user
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($this->users->hasPages())
                <div class="px-5 py-4 border-t border-line">
                    {{ $this->users->links() }}
                </div>
            @endif
        </div>
    </main>
</div>