<div>
    <div class="relative inline-block text-left"
        x-data="{
            open: false,
            menuStyle: ''
        }">
        <button type="button"
            @click="
                open = !open;
                if (open) {
                    const rect = $event.currentTarget.getBoundingClientRect();
                    menuStyle = `
                        position: fixed;
                        top: ${rect.bottom + 8}px;
                        left: ${rect.right - 176}px;
                    `;
                }
            "
            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-mist hover:text-ink hover:bg-surface-2 transition">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="1"></circle>
                <circle cx="19" cy="12" r="1"></circle>
                <circle cx="5" cy="12" r="1"></circle>
            </svg>
        </button>

        <template x-teleport="body">
            <div
                x-show="open"
                :style="menuStyle"
                class="fixed z-[9999] w-44 rounded-lg border border-line bg-card p-1.5" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.outside="open = false" @click="open = false" x-cloak
                style="box-shadow: 0 12px 32px -16px var(--card-shadow, rgba(0,0,0,.25));">
                <p class="px-3 py-1.5 text-[10px] font-medium uppercase tracking-wide text-mist">
                    Actions
                </p>

                @can('permissions.detail')
                    <a href="{{ route('admin.permission.detail', $item->id) }}" wire:navigate
                        class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm text-ink hover:bg-surface-2 transition" title="Detail">
                        <i class="fa-regular fa-eye size-3 text-center text-mist"></i>
                        View
                    </a>
                @endcan

                @can('permissions.update')
                    <a href="{{ route('admin.permission.update', $item->id) }}" wire:navigate
                        class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm text-ink hover:bg-surface-2 transition" title="Edit">
                        <i class="fa-regular fa-pen-to-square size-3 text-center text-mist"></i>
                        Edit
                    </a>
                @endcan

                @can('permissions.delete')
                    <div class="my-1.5 border-t border-line"></div>

                    <button type="button" wire:click="delete({{ $item->id }})"
                        wire:confirm="Delete permission "{{ $item->name }}"? This cannot be undone."
                        title="Delete"
                        class="flex w-full items-center gap-2.5 rounded-md px-3 py-2 text-sm text-red-600 hover:bg-surface-2 transition">
                        <i class="fa-regular fa-trash-can w-4 text-center"></i>
                        Delete
                    </button>
                @endcan
            </div>
        </template>
    </div>
</div>
