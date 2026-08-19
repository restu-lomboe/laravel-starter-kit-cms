<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

new class extends Component {
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Computed]
    public function permissions()
    {
        return Permission::query()
            ->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->paginate(10);
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('permission.delete'), 403);

        Permission::findOrFail($id)->delete();

        session()->flash('success', 'Permission deleted successfully.');

        $this->redirectRoute('admin.permission.index');
    }

    public function render()
    {
        return $this->view([
            'model' => Permission::class,
            'columns' => [
                'no' => '#',
                'name' => 'Name',
                'description' => 'Description',
                'page' => 'Page',
                'feature' => 'Feature',
                'level' => 'Level',
                'created_at' => 'Created At',
                'actions' => 'Actions',
            ],
            'formatters' => [
                'created_at' => 'date',
            ],
            'formatterOptions' => [
                'created_at' => [
                    'format' => 'd M Y h:i A',  // Any PHP date format string
                ],
            ],
            'customColumns' => [
                'actions' => 'components.admin.permissions.action',
            ],
            'unsortable' => ['actions'],
            'searchable' => ['name', 'description'],
        ]);
    }
};
?>

<div>
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-6">

        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Permissions</h1>
                <p class="mt-1 text-sm text-mist">Manage the granular permissions available across the app</p>
            </div>
            @can('permission.create')
                <a href="{{ route('admin.permission.create') }}" wire:navigate
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-amber px-4 py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    New permission
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
                'td_description' => 'w-50 !whitespace-normal flex flex-row items-center gap-2 flex-shrink break-words',
            ]"/>
    </main>
</div>
