<div>
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition.opacity.duration.300ms
            class="relative overflow-hidden rounded-xl border border-line bg-card p-4 flex items-center gap-3 mt-2"
            style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-red-600 shrink-0">
                <i class="fa-solid fa-circle-exclamation"></i>
            </span>

            <p class="text-sm font-medium text-ink">
                {{ session('error') }}
            </p>

            {{-- Loading bar --}}
            <div class="absolute bottom-0 left-0 w-full h-1 bg-red-100">
                <div class="h-full bg-red-600" style="animation: errorProgress 3s linear forwards;"></div>
            </div>
        </div>

        <style>
            @keyframes errorProgress {
                from {
                    width: 100%;
                }

                to {
                    width: 0%;
                }
            }
        </style>
    @endif

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition:leave.opacity.duration.300ms
            class="relative overflow-hidden rounded-xl border border-line bg-card p-4 flex items-center gap-3 mt-2"
            style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
            {{-- Icon --}}
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-amber-deep shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </span>

            {{-- Message --}}
            <p class="text-sm font-medium text-ink">
                {{ session('success') }}
            </p>

            {{-- Loading bar --}}
            <div class="absolute bottom-0 left-0 w-full h-1 bg-amber-deep/10">
                <div class="h-full bg-amber-deep" style="animation: successProgress 3s linear forwards;"></div>
            </div>
        </div>

        <style>
            @keyframes successProgress {
                from {
                    width: 100%;
                }

                to {
                    width: 0%;
                }
            }
        </style>
    @endif
</div>
