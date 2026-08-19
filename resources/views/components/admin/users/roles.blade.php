<div>
    <div class="flex flex-wrap gap-1">
        @forelse ($item->roles as $role)
            <span class="rounded-full bg-surface-2 px-2.5 py-1 text-xs text-mist">
                {{ $role->name }}
            </span>
        @empty
            <span class="text-xs text-mist">—</span>
        @endforelse
    </div>
</div>
