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
        <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center gap-3 rounded-md px-3 py-2 bg-surface-2 text-ink font-medium">
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
                    <div class="relative ml-2 border-l border-gray-200 dark:border-neutral-700 pl-2 space-y-1 pb-4">
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

            <a href="#"
                class="nav-item flex items-center gap-3 rounded-md px-3 py-2 text-mist hover:text-ink hover:bg-surface-2 transition">
                <svg class="shrink-0 size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em"
                    height="1em" viewBox="0 0 24 24">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 7a4 4 0 1 0 8 0a4 4 0 1 0-8 0M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2m1-17.87a4 4 0 0 1 0 7.75M21 21v-2a4 4 0 0 0-3-3.85" />
                </svg>

                <span class="sidebar-label whitespace-nowrap">Users</span>
            </a>
        </div>

        <p class="sidebar-group-label pt-4 pb-1 px-3 text-[10px] uppercase tracking-wide text-mist/60">Other</p>
        <a href="#"
            class="nav-item flex items-center gap-3 rounded-md px-3 py-2 text-mist hover:text-ink hover:bg-surface-2 transition">
            <svg class="shrink-0 size-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="1em"
                height="1em" viewBox="0 0 24 24">
                <path d="M0 0h24v24H0z" fill="none" />
                <path fill="currentColor"
                    d="M12 15.5A3.5 3.5 0 0 1 8.5 12A3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5a3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97s-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.31-.61-.22l-2.49 1c-.52-.39-1.06-.73-1.69-.98l-.37-2.65A.506.506 0 0 0 14 2h-4c-.25 0-.46.18-.5.42l-.37 2.65c-.63.25-1.17.59-1.69.98l-2.49-1c-.22-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1s.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.06.74 1.69.99l.37 2.65c.04.24.25.42.5.42h4c.25 0 .46-.18.5-.42l.37-2.65c.63-.26 1.17-.59 1.69-.99l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64z" />
            </svg>
            <span class="sidebar-label whitespace-nowrap">Company settings</span>
        </a>
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
