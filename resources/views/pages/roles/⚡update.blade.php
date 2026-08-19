<?php

use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new class extends Component {
    public int $roleId;
    public string $name = '';
    public string $guard_name = 'web';
    public array $selectedPermissions = [];

    public function mount($id): void
    {
        $role = Role::findOrFail($id);

        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->selectedPermissions = $role->permissions()->pluck('permissions.id')->all();
    }

    #[Computed]
    public function permissions()
    {
        return Permission::query()
            ->orderBy('page')
            ->orderBy('feature')
            ->orderBy('level')
            ->get();
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->where(fn ($query) => $query->where('guard_name', $this->guard_name))->ignore($this->roleId)],
            'guard_name' => ['required', 'string', Rule::in(array_keys(config('auth.guards')))],
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        \DB::beginTransaction();
        try {

            $role = Role::findOrFail($this->roleId);

            $role->update([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
            ]);

            $role->syncPermissions(array_map('intval', $this->selectedPermissions));
            \DB::commit();

            session()->flash('success', 'Role updated successfully.');
            $this->redirectRoute('admin.roles.index');

        } catch (\Throwable $th) {
            \DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
    }
};
?>

<div>
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-6">
        <div class="w-full">

            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Edit role</h1>
                    <p class="mt-1 text-sm text-mist">Update the role and its permissions</p>
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

                    <div class="space-y-2 py-4 px-6 rounded-xl border border-line max-h-80 overflow-y-auto overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-3 [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-amber/80 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-amber/80 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:border-2 [&::-webkit-scrollbar-thumb]:border-white dark:[&::-webkit-scrollbar-thumb]:border-neutral-700">
                        @forelse ($this->permissions->groupBy('page') as $page => $pagePermissions)
                            <div class="mb-4">
                                <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ $page ?? 'General' }}
                                </h3>
                                <div class="ml-4 space-y-2">
                                    @foreach ($pagePermissions->groupBy('feature') as $feature => $featurePermissions)
                                        <div>
                                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                                {{ $feature ?? 'No Feature' }}</p>
                                            <div class="ml-4 space-y-1">
                                                @foreach ($featurePermissions as $permission)
                                                    <label class="flex items-center cursor-pointer">
                                                        <input type="checkbox" wire:model="selectedPermissions"
                                                            value="{{ $permission->id }}"
                                                            {{ in_array($permission->id, $this->selectedPermissions) ? 'checked' : '' }}
                                                            class="w-4 h-4 text-blue-600 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                            {{ $permission->name }} <span
                                                                class="text-xs text-gray-500">({{ $permission->level }})</span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="max-w-sm w-full min-h-75 flex flex-col justify-center mx-auto px-6 py-4">
                                <div
                                    class="flex justify-center items-center size-11 bg-gray-100 rounded-lg dark:bg-neutral-800">
                                    <svg class="shrink-0 size-6 text-gray-600 dark:text-neutral-400"
                                        xmlns="http://www.w3.org/2000/svg" width="2048" height="2048"
                                        viewBox="0 0 2048 2048">
                                        <path fill="currentColor"
                                            d="M2048 1573v475h-512v-256h-256v-256h-256v-207q-74 39-155 59t-165 20q-97 0-187-25t-168-71t-142-110t-111-143t-71-168T0 704q0-97 25-187t71-168t110-142T349 96t168-71T704 0q97 0 187 25t168 71t142 110t111 143t71 168t25 187q0 51-8 101t-23 98zm-128 54l-690-690q22-57 36-114t14-119q0-119-45-224t-124-183t-183-123t-224-46q-119 0-224 45T297 297T174 480t-46 224q0 119 45 224t124 183t183 123t224 46q97 0 190-33t169-95h89v256h256v256h256v256h256zM512 384q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10" />
                                    </svg>
                                </div>

                                <h2 class="mt-5 font-semibold text-gray-800 dark:text-white">
                                    No Permissions Available
                                </h2>
                                <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                                    Create a new permission to get started
                                </p>

                                <div class="mt-5 flex flex-col sm:flex-row gap-2">
                                    @can('permission.create')
                                        <a href="{{ route('admin.permission.create') }}" wire:navigate
                                            class="mt-2 inline-flex items-center gap-2 text-xs text-amber-deep hover:text-ink transition">
                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                            Create the first permission
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="border-t border-line pt-5 flex items-center gap-3">
                    <button type="submit" wire:loading.attr="disabled"
                        class="rounded-lg bg-amber px-4 py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition disabled:opacity-50 disabled:cursor-wait">
                        Save changes
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
