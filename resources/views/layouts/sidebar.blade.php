<!-- ================= DESKTOP SIDEBAR ================= -->
<aside id="sidebar" class="hidden sm:flex w-60 shrink-0 flex-col border-r border-line bg-surface transition">
    <div class="flex items-center gap-2.5 px-4 h-16 border-b border-line shrink-0">
        <span
            class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber text-on-amber font-display font-semibold text-lg shrink-0">A</span>
        <span
            class="sidebar-wordmark font-display text-lg font-medium tracking-tight whitespace-nowrap dark:text-white">Anchor
            HR</span>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5 text-sm">
        <a href="{{ route('admin.dashboard') }}" wire:navigate
            class="nav-item flex items-center gap-3 rounded-md px-3 py-2 font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-surface-2 text-ink' : 'hover:bg-surface-2 hover:text-ink text-mist' }}">
            <svg class="shrink-0 size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em"
                height="1em" viewBox="0 0 24 24">
                <path d="M0 0h24v24H0z" fill="none" />
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M2 5a2 2 0 0 1 2-2h6v18H4a2 2 0 0 1-2-2zm12-2h6a2 2 0 0 1 2 2v5h-8zm0 11h8v5a2 2 0 0 1-2 2h-6z" />
            </svg>

            <span class="sidebar-label whitespace-nowrap">Dashboard</span>
        </a>
        <div class="flex flex-col">
            <p class="sidebar-group-label pt-4 pb-1 px-3 text-[10px] uppercase tracking-wide text-mist/60">Pages</p>

            <div class="px-3 nav-item rounded-md text-mist hover:text-ink hover:bg-surface-2 transition">
                <details class="group">
                    <summary class="flex items-center justify-between py-3 cursor-pointer list-none select-none">
                        <div class="flex items-center gap-3 text-sm">
                            <svg class="shrink-0 size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                width="1em" height="1em" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path fill="currentColor"
                                    d="M15.088 19.163Q14 18.075 14 16.5t1.088-2.662t2.662-1.088t2.663 1.088T21.5 16.5t-1.088 2.663t-2.662 1.087t-2.662-1.088m3.9-1.424q.512-.513.512-1.238t-.513-1.237t-1.237-.513t-1.237.513T16 16.5t.513 1.238t1.237.512t1.238-.513M4 17.5v-2h8v2zm-.413-7.337Q2.5 9.075 2.5 7.5t1.088-2.662T6.25 3.75t2.663 1.088T10 7.5t-1.088 2.663T6.25 11.25t-2.662-1.088m3.9-1.425Q8 8.225 8 7.5t-.513-1.237T6.25 5.75t-1.237.513T4.5 7.5t.513 1.238t1.237.512t1.238-.513M12 8.5v-2h8v2zm-5.75-1" />
                            </svg>

                            <span class="sidebar-label whitespace-nowrap">Menu item</span>
                        </div>
                        <svg class="size-4 transition-transform duration-200 group-open:rotate-180 text-gray-500 dark:text-neutral-400"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="relative ml-2 mb-5 border-l border-gray-200 dark:border-neutral-700 pl-2 space-y-1">
                        <a class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:hover:text-white transition-colors text-sm"
                            href="#">
                            Submenu item
                        </a>
                        <a class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:hover:text-white transition-colors text-sm"
                            href="#">
                            Submenu item
                        </a>
                    </div>
                </details>
            </div>
        </div>

        <div class="flex flex-col">
            <p class="sidebar-group-label pt-4 pb-1 px-3 text-[10px] uppercase tracking-wide text-mist/60">Settings</p>
            @canany(['roles.index', 'permission.index'])
                <div class="px-3 nav-item rounded-md text-mist hover:text-ink transition
                {{
                    request()->routeIs('admin.roles.*') ||
                    request()->routeIs('admin.permission.*') ? 'bg-surface-2' : 'hover:bg-surface-2'
                }}">
                <details class="group"
                    {{
                       request()->routeIs('admin.roles.*') ||
                       request()->routeIs('admin.permission.*') ? 'open' : ''
                    }}>
                    <summary class="flex items-center justify-between py-3 cursor-pointer list-none select-none">
                        <div class="flex items-center gap-3 text-sm">
                            <svg class="shrink-0 size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                width="1em" height="1em" viewBox="0 0 32 32">
                                <path d="M0 0h32v32H0z" fill="none" />
                                <path fill="currentColor"
                                    d="M19.414 30H15v-4.414l5.034-5.034A5 5 0 0 1 20 20a5 5 0 1 1 4.448 4.966ZM17 28h1.586l5.206-5.206l.54.124a3.035 3.035 0 1 0-2.25-2.25l.124.54L17 26.414Z" />
                                <circle cx="25" cy="20" r="1" fill="currentColor" />
                                <path fill="currentColor" d="M8 6h12v2H8zm0 4h12v2H8zm0 4h6v2H8zm0 10h4v2H8z" />
                                <path fill="currentColor"
                                    d="M12 30H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v9h-2V4H6v24h6Z" />
                            </svg>


                            <span class="sidebar-label whitespace-nowrap">Authorization</span>
                        </div>
                        <svg class="size-4 transition-transform duration-200 group-open:rotate-180 text-gray-500 dark:text-neutral-400"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                        </svg>
                    </summary>
                    <div class="relative ml-2 mb-5 border-l border-gray-200 dark:border-neutral-700 pl-2 space-y-1">
                        @can('roles.index')
                            <a class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:hover:text-white transition-colors text-sm
                                {{
                                    request()->routeIs('admin.roles.*')
                                        ? 'dark:bg-neutral-800 bg-gray-100'
                                        : ''
                                }}"
                                href="{{ route('admin.roles.index') }}" wire:navigate>
                                Roles
                            </a>
                        @endcan
                        @can('permission.index')
                            <a class="block rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:hover:text-white transition-colors text-sm
                                {{
                                    request()->routeIs('admin.permission.*')
                                        ? 'dark:bg-neutral-800 bg-gray-100'
                                        : ''
                                }}"
                                href="{{ route('admin.permission.index') }}" wire:navigate>
                                Permissions
                            </a>
                        @endcan
                    </div>
                </details>
            </div>
            @endcanany
            @can('users.index')
                <a href="{{ route('admin.user.index') }}" wire:navigate
                class="nav-item flex items-center gap-3 rounded-md px-3 py-2 hover:text-ink transition {{ request()->routeIs('admin.user.*') ? 'bg-surface-2 text-ink' : 'hover:bg-surface-2 text-mist' }}">
                <svg class="shrink-0 size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em"
                    height="1em" viewBox="0 0 24 24">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 7a4 4 0 1 0 8 0a4 4 0 1 0-8 0M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2m1-17.87a4 4 0 0 1 0 7.75M21 21v-2a4 4 0 0 0-3-3.85" />
                </svg>

                <span class="sidebar-label whitespace-nowrap">User Managements</span>
            </a>
            @endcan
        </div>
    </nav>
</aside>

<!-- ================= MOBILE SIDEBAR OVERLAY ================= -->
<div id="mobile-sidebar-overlay" data-action="toggleMobileSidebar"
    class="hidden fixed inset-0 bg-black/40 z-40 lg:hidden"></div>

<!-- ================= MOBILE SIDEBAR ================= -->
<aside id="mobile-sidebar"
    class="hidden fixed inset-y-0 left-0 w-64 bg-surface border-r border-line z-50 flex-col lg:hidden">
    <div class="flex items-center justify-between gap-2.5 px-4 h-16 border-b border-line">
        <div class="flex items-center gap-2.5">
            <span
                class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber text-on-amber font-display font-semibold text-lg">A</span>
            <span class="font-display text-lg font-medium tracking-tight dark:text-white">Anchor HR</span>
        </div>
        <button type="button" data-action="toggleMobileSidebar" class="text-mist hover:text-ink"><i
                class="fa-solid fa-xmark"></i></button>
    </div>
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5 text-sm">
        <a href="#" class="flex items-center gap-3 rounded-md px-3 py-2 bg-surface-2 text-ink font-medium"><i
                class="fa-solid fa-gauge w-4 text-center"></i>Overview</a>
        <a href="#" class="flex items-center gap-3 rounded-md px-3 py-2 text-mist"><i
                class="fa-solid fa-users w-4 text-center"></i>Employees</a>
        <a href="#" class="flex items-center gap-3 rounded-md px-3 py-2 text-mist"><i
                class="fa-solid fa-clock w-4 text-center"></i>Attendance</a>
        <a href="#" class="flex items-center gap-3 rounded-md px-3 py-2 text-mist"><i
                class="fa-solid fa-sack-dollar w-4 text-center"></i>Payroll</a>
        <a href="#" class="flex items-center gap-3 rounded-md px-3 py-2 text-mist"><i
                class="fa-solid fa-chart-line w-4 text-center"></i>Reports</a>
        <p class="pt-4 pb-1 px-3 text-[10px] uppercase tracking-wide text-mist/60">Other</p>
        <a href="#" class="flex items-center gap-3 rounded-md px-3 py-2 text-mist"><i
                class="fa-solid fa-gear w-4 text-center"></i>Company settings</a>
    </nav>
</aside>
