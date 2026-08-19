<?php

use App\Models\User;
use Livewire\Component;

new class extends Component {

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

    public function render()
    {
        return $this->view([
            'model' => User::class,
            'columns' => [
                'no' => '#',
                'name' => 'Name',
                'roles' => 'Roles',
                'email' => 'Email',
                'created_at' => 'Created At',
                'actions' => 'Actions',
            ],
            'formatters' => [
                'created_at' => 'date',
                'name' => [
                    'type' => 'link',
                    'options' => [
                        'route' => 'users.detail',       // named route
                        'params' => ['id'],   // route param => column name
                        'target' => '_blank',   // optional
                        'class' => 'text-blue-600 dark:text-blue-400 hover:underline',
                    ],
                ],
            ],
            'formatterOptions' => [
                'created_at' => [
                    'format' => 'd M Y h:i A',  // Any PHP date format string
                ],
            ],
            'customColumns' => [
                'roles' => 'components.admin.users.roles',
                'actions' => 'components.admin.users.action',
            ],
            'unsortable' => ['actions', 'roles'],
            'searchable' => ['name', 'email'],
        ])->layout('layouts.app');
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

        <livewire:livewire-datatable
            :model="$model"
            :columns="$columns"
            :searchable="$searchable"
            :customColumns="$customColumns"
            :unsortable="$unsortable"
            :formatters="$formatters"
            :formatterOptions="$formatterOptions"
            :theme="[
                'search_wrapper' => 'pb-4 px-3 pt-3 flex flex-col sm:flex-row items-center justify-between gap-4 dark:bg-surface bg-white',
                'table_wrapper' => 'overflow-x-auto border border-gray-200 dark:border-gray-700 shadow dark:bg-surface bg-white',
                'filter_panel' => 'transition duration-300 ease-in-out p-4 border-r border-gray-200 dark:border-gray-700 dark:bg-surface bg-white',
                'pagination_wrapper' => 'p-4 bg-white dark:bg-surface',
                'td_name' => 'w-50 !whitespace-normal flex flex-row items-center gap-2 flex-shrink break-words',
            ]"/>
    </main>
</div>
