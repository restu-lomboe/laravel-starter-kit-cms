<!-- topbar -->
<header class="flex items-center justify-between gap-4 h-16 px-4 sm:px-6 border-b border-line shrink-0">
    <div class="flex items-center gap-3 min-w-0">
        <div class="hidden sm:flex relative -left-10">
            <div class="shrink-0">
                <button id="sidebar-toggle" type="button" data-action="toggleSidebar"
                    class="nav-item flex w-full items-center gap-3 rounded-md px-1.5 py-2 text-sm text-on-amber hover:text-ink transition border border-line bg-amber">
                    <i id="sidebar-collapse-icon"
                        class="fa-solid fa-angle-left w-4 text-center shrink-0 transition-transform"></i>
                </button>
            </div>
        </div>
        <div class="sm:hidden flex items-center gap-3 min-w-0">
            <button id="mobile-sidebar-toggle" type="button" data-action="toggleMobileSidebar"
                class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-line text-mist hover:text-ink transition shrink-0">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="flex items-center gap-2.5">
                <span class="font-display text-lg font-medium tracking-tight dark:text-white">Anchor HR</span>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2 shrink-0">
        <button id="theme-toggle" type="button" data-action="toggleTheme" aria-label="Toggle dark mode"
            class="inline-flex size-9 items-center justify-center rounded-full border border-line text-mist hover:text-ink hover:border-mist transition">
            <svg id="icon-sun" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="4" />
                <path
                    d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
            </svg>
            <svg id="icon-moon" class="size-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
            </svg>
        </button>
        <button
            class="relative inline-flex h-9 w-9 items-center justify-center rounded-full border border-line text-mist hover:text-ink hover:border-mist transition">
            <i class="fa-solid fa-bell"></i>
            <span class="absolute top-1.5 right-2 h-1.5 w-1.5 rounded-full bg-amber-deep"></span>
        </button>
        <div class="relative inline-block text-left">
            <button id="profile-trigger" type="button" data-action="toggleProfileMenu"
                class="flex items-center gap-2 rounded-full hover:bg-surface-2 transition hover:pr-3 hover:pl-1">
                <span
                    class="flex size-9 items-center justify-center rounded-full bg-surface-2 text-xs font-medium text-ink">DA</span>
                <span
                    class="hidden sm:inline-block text-sm text-zinc-500 dark:text-white/80 group-hover:text-zinc-800 dark:group-hover:text-white font-medium truncate">
                    Hiroko Pearson
                </span>
                <svg class="hidden sm:inline-block shrink-0 size-4 dark:text-white font-bold" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 256 256">
                    <path d="M0 0h256v256H0z" fill="none" />
                    <path fill="currentColor"
                        d="M184.49 167.51a12 12 0 0 1 0 17l-48 48a12 12 0 0 1-17 0l-48-48a12 12 0 0 1 17-17L128 207l39.51-39.52a12 12 0 0 1 16.98.03m-96-79L128 49l39.51 39.52a12 12 0 0 0 17-17l-48-48a12 12 0 0 0-17 0l-48 48a12 12 0 0 0 17 17Z" />
                </svg>

            </button>

            <div wire:transition id="profile-menu"
                class="hidden absolute right-0 mt-2 w-64 rounded-xl border border-line bg-neutral-900 pt-4 z-50"
                style="box-shadow: 0 12px 32px -12px var(--card-shadow, rgba(0,0,0,.25));">

                <div class="px-4 pb-3">
                    <p class="text-sm font-semibold text-ink">Super Admin <span
                            class="font-normal text-mist">(user)</span></p>
                    <p class="text-xs text-mist mt-0.5">superadmin@saas.local</p>
                </div>

                <div class="border-t border-line flex items-center justify-between">
                    <a href="{{ route('admin.setting.profile') }}"
                        class="flex w-full items-center gap-2 text-sm text-mist hover:text-ink hover:bg-surface-2 transition px-4 py-4">
                        <i class="fa-solid fa-gear"></i>
                        Settings
                    </a>
                </div>

                <div class="border-t border-line">
                    <button type="button"
                        class="flex w-full items-center gap-2 text-sm text-mist hover:text-ink hover:bg-surface-2 transition px-4 py-4">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Log out
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
