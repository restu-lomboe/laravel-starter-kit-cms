<?php

use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new class extends Component {
    public string $name = '';
    public string $guard_name = 'web';
    public array $selectedPermissions = [];

    #[Computed]
    public function permissions()
    {
        return Permission::query()->orderBy('name')->get();
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->where(fn ($query) => $query->where('guard_name', $this->guard_name))],
            'guard_name' => ['required', 'string', Rule::in(array_keys(config('auth.guards')))],
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        \DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);

            $role->syncPermissions(array_map('intval', $this->selectedPermissions));

            \DB::commit();

            session()->flash('success', 'Role created successfully.');
            $this->redirectRoute('admin.roles.index');

        } catch (\Throwable $th) {
            dd($th);
            \DB::rollBack();
            session()->flash('error', 'Failed to create role.'. $th->getMessage());
        }
    }
};
?>

<div>
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-6">
        <div class="w-full">

            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Create role</h1>
                    <p class="mt-1 text-sm text-mist">Add a new role and choose its permissions</p>
                </div>
                <a href="{{ route('admin.roles.index') }}" wire:navigate
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Back
                </a>
            </div>

            <form wire:submit="save" class="mt-6 rounded-xl border border-line bg-card p-6 space-y-5"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">

                <div>
                    <label for="name" class="block text-xs font-medium text-mist mb-1.5">Role name</label>
                    <input id="name" type="text" wire:model="name" placeholder="e.g. editor"
                        class="field-input" />
                    @error('name')
                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="guard_name" class="block text-xs font-medium text-mist mb-1.5">Guard</label>
                    <select id="guard_name" wire:model="guard_name" class="field-input">
                        @foreach (array_keys(config('auth.guards')) as $guard)
                            <option value="{{ $guard }}">{{ $guard }}</option>
                        @endforeach
                    </select>
                    @error('guard_name')
                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="border-t border-line pt-5">
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-sm font-semibold text-ink">Permissions</p>
                        <span class="text-xs text-mist font-mono">{{ count($this->selectedPermissions) }} selected</span>
                    </div>
                    <p class="text-xs text-mist mb-4">Select the permissions this role will have</p>

                    @error('selectedPermissions')
                        <p class="mb-3 text-xs flex items-center gap-1" style="color:#ef4444;">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror

                    <div class="rounded-xl border border-line divide-y divide-line max-h-80 overflow-y-auto">
                        @forelse ($this->permissions as $permission)
                            <label class="flex items-center gap-3 px-4 py-3 text-sm text-ink hover:bg-surface transition select-none cursor-pointer">
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}"
                                    class="h-3.5 w-3.5 rounded border-line bg-surface accent-amber" />
                                <span class="flex-1">{{ $permission->name }}</span>
                                <span class="text-xs font-mono text-mist">{{ $permission->guard_name }}</span>
                            </label>
                        @empty
                            <p class="px-4 py-8 text-center text-sm text-mist">No permissions available yet</p>
                        @endforelse
                    </div>
                </div>

                <div class="border-t border-line pt-5 flex items-center gap-3">
                    <button type="submit" wire:loading.attr="disabled"
                        class="rounded-lg bg-amber px-4 py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition disabled:opacity-50 disabled:cursor-wait">
                        Create role
                    </button>
                    <a href="{{ route('admin.roles.index') }}" wire:navigate
                        class="rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
