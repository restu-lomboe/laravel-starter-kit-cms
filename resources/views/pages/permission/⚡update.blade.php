<?php

use Illuminate\Validation\Rule;
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
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_.-]+$/', Rule::unique('permissions', 'name')->where(fn ($query) => $query->where('guard_name', $this->guard_name))->ignore($this->permissionId)],
            'description' => ['nullable', 'string', 'max:255'],
            'page' => ['nullable', 'string', 'max:255'],
            'feature' => ['nullable', 'string', 'max:255'],
            'level' => ['nullable', 'string', Rule::in(['page', 'feature', 'create', 'read', 'update', 'delete'])],
            'guard_name' => ['required', 'string', Rule::in(array_keys(config('auth.guards')))],
        ]);

        Permission::findOrFail($this->permissionId)->update([
            'name' => $this->name,
            'description' => $this->description,
            'page' => $this->page,
            'feature' => $this->feature,
            'level' => $this->level,
            'guard_name' => $this->guard_name,
        ]);

        session()->flash('success', 'Permission updated successfully.');

        $this->redirectRoute('admin.permission.index');
    }
};
?>

<div>
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-6">
        <div class="w-full">

            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Edit permission</h1>
                    <p class="mt-1 text-sm text-mist">Update the permission details</p>
                </div>
                <a href="{{ route('admin.permission.index') }}" wire:navigate
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Back
                </a>
            </div>

            <form wire:submit="save" class="mt-6 rounded-xl border border-line bg-card p-6 space-y-5"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">

                <div>
                    <label for="name" class="block text-xs font-medium text-mist mb-1.5">Permission name</label>
                    <input id="name" type="text" wire:model="name" placeholder="e.g. users.create"
                        class="field-input" />
                    @error('name')
                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="desc" class="block text-xs font-medium text-mist mb-1.5">Description</label>
                    <textarea id="desc" wire:model="description" rows="3"
                        placeholder="Describe what this permission allows…" class="field-input resize-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="page" class="block text-xs font-medium text-mist mb-1.5">Page</label>
                        <input id="page" type="text" wire:model="page"
                            placeholder="Select or create new page…" class="field-input" />
                    </div>

                    <div>
                        <label for="feature" class="block text-xs font-medium text-mist mb-1.5">Feature</label>
                        <input id="feature" type="text" wire:model="feature" placeholder="e.g. users"
                            class="field-input" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="level" class="block text-xs font-medium text-mist mb-1.5">Level</label>
                        <select id="level" wire:model="level" class="field-input">
                            <option value="">Select level…</option>
                            <option value="page">page</option>
                            <option value="feature">feature</option>
                            <option value="create">create</option>
                            <option value="read">read</option>
                            <option value="update">update</option>
                            <option value="delete">delete</option>
                        </select>
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
                </div>

                <div class="border-t border-line pt-5 flex items-center gap-3">
                    <button type="submit"
                        class="rounded-lg bg-amber px-4 py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition">
                        Save changes
                    </button>
                    <a href="{{ route('admin.permission.index') }}" wire:navigate
                        class="rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
