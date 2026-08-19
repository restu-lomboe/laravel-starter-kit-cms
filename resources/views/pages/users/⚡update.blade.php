<?php

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\Permission\Models\Role;

new class extends Component {
    public int $userId;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public array $selectedRoles = [];

    public function mount($id): void
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles()->pluck('roles.id')->all();
    }

    #[Computed]
    public function roles()
    {
        return Role::query()->orderBy('name')->get();
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'password' => ['nullable', 'string', 'min:8'],
            'selectedRoles' => ['array'],
            'selectedRoles.*' => ['integer', 'exists:roles,id'],
        ]);

        \DB::beginTransaction();
        try {
            $user = User::findOrFail($this->userId);

            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password !== '' ? $this->password : $user->password,
            ]);

            $user->syncRoles(array_map('intval', $this->selectedRoles));

            \DB::commit();

            session()->flash('success', 'User updated successfully.');
            $this->redirectRoute('admin.user.index');
        } catch (\Throwable $th) {
            \DB::rollBack();
            session()->flash('error', 'Failed to update user. '.$th->getMessage());
        }
    }
};
?>

<div>
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-6">
        <div class="w-full">

            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Edit user</h1>
                    <p class="mt-1 text-sm text-mist">Update the user and their assigned roles</p>
                </div>
                <a href="{{ route('admin.user.index') }}" wire:navigate
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Back
                </a>
            </div>

            <form wire:submit="save" class="mt-6 rounded-xl border border-line bg-card p-6 space-y-5"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label for="name" class="block text-xs font-medium text-mist mb-1.5">Name</label>
                        <input id="name" type="text" wire:model="name" placeholder="e.g. Hiroko Pearson"
                            class="field-input" />
                        @error('name')
                            <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-medium text-mist mb-1.5">Email</label>
                        <input id="email" type="email" wire:model="email" placeholder="you@company.com"
                            class="field-input" />
                        @error('email')
                            <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-medium text-mist mb-1.5">Password</label>
                        <div class="relative w-full">
                            <input id="password" type="password" wire:model="password" placeholder="Leave blank to keep current password"
                                class="field-input pr-10" />
                            <button type="button" wire:click="$js.togglePasswordField('password', $event.currentTarget)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-mist hover:text-ink transition">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                        <p class="mt-1.5 text-xs text-mist">Leave blank to keep the current password</p>
                        @error('password')
                            <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>


                <div class="border-t border-line pt-5">
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-sm font-semibold text-ink">Roles</p>
                        <span class="text-xs text-mist font-mono">{{ count($this->selectedRoles) }} selected</span>
                    </div>
                    <p class="text-xs text-mist mb-4">Select the roles this user will have</p>

                    @error('selectedRoles')
                        <p class="mb-3 text-xs flex items-center gap-1" style="color:#ef4444;">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror

                    <div class="rounded-xl border border-line divide-y divide-line max-h-80 overflow-y-auto">
                        @forelse ($this->roles as $role)
                            <label class="flex items-center gap-3 px-4 py-3 text-sm text-ink hover:bg-surface transition select-none cursor-pointer">
                                <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}"
                                    class="h-3.5 w-3.5 rounded border-line bg-surface accent-amber" />
                                <span class="flex-1">{{ $role->name }}</span>
                                <span class="text-xs font-mono text-mist">{{ $role->guard_name }}</span>
                            </label>
                        @empty
                            <p class="px-4 py-8 text-center text-sm text-mist">No roles available yet. Create a role first.</p>
                        @endforelse
                    </div>
                </div>

                <div class="border-t border-line pt-5 flex items-center gap-3">
                    <button type="submit" wire:loading.attr="disabled"
                        class="rounded-lg bg-amber px-4 py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition disabled:opacity-50 disabled:cursor-wait">
                        Save changes
                    </button>
                    <a href="{{ route('admin.user.index') }}" wire:navigate
                        class="rounded-lg border border-line bg-surface px-4 py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
