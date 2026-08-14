<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <!-- content -->
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6 space-y-6">

        <div>
            <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Good morning, Daniel</h1>
            <p class="mt-1 text-sm text-mist">Here's what's happening across Northwind Co. today.</p>
        </div>

        <!-- stat cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="rounded-xl border border-line bg-card p-4"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <div class="flex items-center justify-between">
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-amber-deep"><i
                            class="fa-solid fa-users"></i></span>
                    <span class="text-xs font-mono text-amber-deep">↑ 3.2%</span>
                </div>
                <p class="mt-3 font-display text-2xl text-ink">1,248</p>
                <p class="text-xs text-mist">Active headcount</p>
            </div>

            <div class="rounded-xl border border-line bg-card p-4"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <div class="flex items-center justify-between">
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-amber-deep"><i
                            class="fa-solid fa-clock"></i></span>
                    <span class="text-xs font-mono text-mist">91.5%</span>
                </div>
                <p class="mt-3 font-display text-2xl text-ink">1,142</p>
                <p class="text-xs text-mist">Present today</p>
            </div>

            <div class="rounded-xl border border-line bg-card p-4"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <div class="flex items-center justify-between">
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-amber-deep"><i
                            class="fa-solid fa-plane"></i></span>
                    <span class="text-xs font-mono text-mist">this week</span>
                </div>
                <p class="mt-3 font-display text-2xl text-ink">34</p>
                <p class="text-xs text-mist">On leave</p>
            </div>

            <div class="rounded-xl border border-line bg-card p-4"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <div class="flex items-center justify-between">
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-surface-2 text-amber-deep"><i
                            class="fa-solid fa-hourglass-half"></i></span>
                    <span class="text-xs font-mono text-mist">needs review</span>
                </div>
                <p class="mt-3 font-display text-2xl text-ink">7</p>
                <p class="text-xs text-mist">Pending approvals</p>
            </div>
        </div>

        <!-- chart + activity -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

            <div class="xl:col-span-2 rounded-xl border border-line bg-card p-5"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-ink">Attendance trend</p>
                        <p class="text-xs text-mist font-mono">Aug 07 – Aug 13, 2026</p>
                    </div>
                    <span class="text-xs rounded-full border border-line px-2.5 py-1 text-mist">Last 7
                        days</span>
                </div>
                <svg viewBox="0 0 560 160" class="w-full h-40">
                    <polyline points="0,110 80,95 160,120 240,80 320,90 400,55 480,60 560,30" fill="none"
                        style="stroke: var(--color-amber-deep);" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <polyline points="0,110 80,95 160,120 240,80 320,90 400,55 480,60 560,30 560,160 0,160"
                        style="fill: color-mix(in srgb, var(--color-amber) 15%, transparent); stroke: none;" />
                </svg>
            </div>

            <div class="rounded-xl border border-line bg-card p-5"
                style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
                <p class="text-sm font-medium text-ink mb-4">Today's attendance</p>
                <ul class="font-mono text-xs text-mist space-y-2.5">
                    <li class="border-l-2 border-amber pl-2.5 text-ink">09:02 · Amara Osei checked in</li>
                    <li class="border-l-2 border-amber pl-2.5 text-ink">09:05 · Levi Nakamura checked in</li>
                    <li class="border-l-2 border-line pl-2.5">09:10 · Priya Chandra on leave</li>
                    <li class="border-l-2 border-amber pl-2.5 text-ink">09:14 · Dario Conti checked in</li>
                    <li class="border-l-2 border-line pl-2.5">09:20 · Elena Vasquez pending</li>
                </ul>
            </div>
        </div>

        <!-- table -->
        <div class="rounded-xl border border-line bg-card overflow-hidden"
            style="box-shadow: 0 8px 24px -12px var(--card-shadow);">
            <div class="flex items-center justify-between px-5 py-4 border-b border-line">
                <p class="text-sm font-medium text-ink">Pending leave requests</p>
                <a href="#" class="text-xs text-amber-deep hover:text-ink transition">View all</a>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-mist border-b border-line">
                        <th class="font-normal px-5 py-2.5">Employee</th>
                        <th class="font-normal px-5 py-2.5">Department</th>
                        <th class="font-normal px-5 py-2.5">Dates</th>
                        <th class="font-normal px-5 py-2.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    <tr>
                        <td class="px-5 py-3 text-ink">Priya Chandra</td>
                        <td class="px-5 py-3 text-mist">Design</td>
                        <td class="px-5 py-3 text-mist font-mono text-xs">Aug 14 – Aug 16</td>
                        <td class="px-5 py-3"><span
                                class="rounded-full bg-surface-2 px-2.5 py-1 text-xs text-mist">Pending</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3 text-ink">Elena Vasquez</td>
                        <td class="px-5 py-3 text-mist">Finance</td>
                        <td class="px-5 py-3 text-mist font-mono text-xs">Aug 18 – Aug 19</td>
                        <td class="px-5 py-3"><span
                                class="rounded-full bg-surface-2 px-2.5 py-1 text-xs text-mist">Pending</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3 text-ink">Marco Lindgren</td>
                        <td class="px-5 py-3 text-mist">Engineering</td>
                        <td class="px-5 py-3 text-mist font-mono text-xs">Aug 21 – Aug 25</td>
                        <td class="px-5 py-3"><span
                                class="rounded-full bg-surface-2 px-2.5 py-1 text-xs text-mist">Pending</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>
