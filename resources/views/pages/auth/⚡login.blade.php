<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public $forgot_password = false;
    public $type;
    public $email = '';
    public $password = '';
    public $remember = false;

    public function mount()
    {
        $this->type = request()->query('type');

        if (Auth::check()) {
            $this->redirectRoute('admin.dashboard');
        }
    }

    public function forgotPassword()
    {
        $this->forgot_password = true;
    }

    #[On('back-to-login')]
    public function backToLogin()
    {
        $this->forgot_password = false;
    }

    public function login()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->flash('error', 'These credentials do not match our records.');

            return;
        }

        session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }
};
?>

<div>
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ================= LEFT — AUTH ================= -->
        <div class="flex-1 flex flex-col justify-between px-6 py-8 sm:px-12 sm:py-7 lg:px-20 lg:py-10">

            <!-- Wordmark -->
            <div class="mx-auto w-full max-w-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber text-on-amber font-display font-semibold text-lg">A</span>
                        <span class="font-display text-lg font-medium tracking-tight dark:text-white">Anchor HR</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="hidden sm:inline-flex items-center rounded-full border border-line px-3 py-1 text-xs font-mono text-mist">HRMS
                            Portal</span>
                        <button id="theme-toggle" type="button" data-action="toggleTheme" aria-label="Toggle dark mode"
                            class="inline-flex size-7 items-center justify-center rounded-full border border-line text-mist hover:text-ink hover:border-mist transition">
                            <svg id="icon-sun" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="4" />
                                <path
                                    d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
                            </svg>
                            <svg id="icon-moon" class="size-3 hidden" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="flex-1 flex items-center justify-center overflow-hidden">
                @if ($type == 'reset-password')
                    <div wire:transition class="w-full">
                        <livewire:pages::auth.change-password />
                    </div>
                @else
                    @if ($forgot_password)
                        <div wire:transition class="w-full">
                            <livewire:pages::auth.forgot-password />
                        </div>
                    @else
                        <div wire:transition class="w-full">
                            <div class="mx-auto w-full max-w-sm mt-10">
                                <h1 class="font-display text-3xl font-medium tracking-tight text-ink">Sign in to your
                                    workspace</h1>
                                <p class="mt-2 text-xs leading-relaxed text-mist">People, time, and pay for your
                                    organization — all in
                                    one place.</p>

                                <form wire:submit="login" class="mt-6 space-y-5">

                                    @include('components.messages')

                                    <div>
                                        <label for="email" class="block text-xs font-medium text-mist mb-1.5">Work
                                            email</label>
                                        <input id="email" type="email" wire:model="email"
                                            placeholder="you@company.com"
                                            class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label for="password"
                                                class="block text-xs font-medium text-mist">Password</label>
                                            <button type="button" wire:click="forgotPassword"
                                                class="text-xs text-amber-deep hover:text-ink transition">Forgot
                                                password?</button>
                                        </div>
                                        <input id="password" type="password" wire:model="password"
                                            placeholder="••••••••••"
                                            class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                                        @error('password')
                                            <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <label class="flex items-center gap-2 text-xs text-mist select-none">
                                        <input type="checkbox" wire:model="remember"
                                            class="h-3.5 w-3.5 rounded border-line bg-surface accent-amber" />
                                        Keep me signed in on this device
                                    </label>

                                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                                        class="w-full rounded-lg bg-amber py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition disabled:cursor-wait">
                                        <span wire:loading.remove>Sign in</span>
                                        <span wire:loading>Signing in…</span>
                                    </button>

                                    <div class="flex items-center gap-3 py-1">
                                        <div class="h-px flex-1 bg-line"></div>
                                        <span class="text-xs text-mist font-mono">or continue with</span>
                                        <div class="h-px flex-1 bg-line"></div>
                                    </div>

                                    <button type="button"
                                        class="w-full flex items-center justify-center gap-2.5 rounded-lg border border-line bg-surface py-2.5 text-sm font-medium text-ink hover:border-mist transition">
                                        <svg width="16" height="16" viewBox="0 0 48 48">
                                            <path fill="#EA4335"
                                                d="M24 9.5c3.5 0 6.6 1.2 9.1 3.6l6.8-6.8C35.9 2.4 30.4 0 24 0 14.6 0 6.4 5.4 2.5 13.2l7.9 6.1C12.3 13.1 17.6 9.5 24 9.5z" />
                                            <path fill="#4285F4"
                                                d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.5 3-2.2 5.5-4.7 7.2l7.4 5.7c4.3-4 6.8-9.9 6.8-17.4z" />
                                            <path fill="#FBBC05"
                                                d="M10.4 19.3A14.5 14.5 0 0 0 9.5 24c0 1.6.3 3.2.9 4.7l-7.9 6.1A24 24 0 0 1 0 24c0-3.9.9-7.5 2.5-10.7l7.9 6z" />
                                            <path fill="#34A853"
                                                d="M24 48c6.4 0 11.9-2.1 15.9-5.8l-7.4-5.7c-2.1 1.4-4.8 2.3-8.5 2.3-6.4 0-11.7-3.6-13.6-8.8l-7.9 6.1C6.4 42.6 14.6 48 24 48z" />
                                        </svg>
                                        Continue with Google
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Footer -->
            <div class="mx-auto w-full max-w-sm">
                <p class="text-center text-xs text-mist">
                    Access is provisioned by your HR administrator. Trouble signing in?
                    <a href="#" class="text-amber-deep hover:text-ink transition">Contact IT support</a>
                </p>
            </div>
        </div>

        <!-- ================= RIGHT — VISUAL ================= -->
        <div class="hidden lg:block lg:w-[46%] relative overflow-hidden bg-surface h-[90vh] my-auto rounded-l-xl">

            <!-- texture band -->
            <div class="h-20 w-full relative"
                style="background:
                    radial-gradient(120% 160% at 15% 0%, var(--grad-a) 0%, transparent 45%),
                    radial-gradient(120% 160% at 85% 20%, var(--grad-b) 0%, transparent 55%),
                    linear-gradient(160deg, var(--grad-c1) 0%, var(--grad-c2) 55%, var(--grad-c3) 100%);">
                <div class="absolute inset-0"
                    style="background-image: repeating-linear-gradient(115deg, #000 0 2px, transparent 2px 6px); opacity: var(--noise-opacity); mix-blend-mode: var(--noise-blend);">
                </div>
            </div>

            <!-- app chrome mock -->
            <div class="p-8 mt-10">
                <div class="flex overflow-hidden rounded-xl border border-line bg-card"
                    style="box-shadow: 0 24px 48px -16px var(--card-shadow);">

                    <!-- sidebar -->
                    <div class="w-40 shrink-0 border-r border-line bg-surface px-3 py-4">
                        <div class="flex items-center gap-1.5 px-1 mb-4">
                            <span
                                class="flex h-5 w-5 items-center justify-center rounded bg-amber text-on-amber font-display font-semibold text-[10px]">A</span>
                            <span class="font-display text-xs font-medium dark:text-white">Anchor HR</span>
                        </div>
                        <nav class="space-y-0.5 text-xs text-mist">
                            <div class="rounded-md bg-surface-2 px-2.5 py-1.5 text-ink font-medium">Overview</div>
                            <div class="px-2.5 py-1.5">Employees</div>
                            <div class="px-2.5 py-1.5">Attendance</div>
                            <div class="px-2.5 py-1.5">Payroll</div>
                            <div class="px-2.5 py-1.5">Reports</div>
                            <div class="pt-3 pb-1 px-2.5 text-[10px] uppercase tracking-wide text-mist/60">Other</div>
                            <div class="px-2.5 py-1.5">Company settings</div>
                        </nav>
                    </div>

                    <!-- main -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 border-b border-line px-4 py-3 text-xs text-mist">
                            <span>Overview</span><span>/</span><span class="text-ink">Northwind Co.</span>
                        </div>

                        <div class="px-4 py-4 border-b border-line">
                            <div class="flex items-center justify-between text-[10px] font-mono text-mist mb-2">
                                <span>Aug 07 – Aug 13, 2026</span>
                                <span class="text-amber-deep">↑ 3.2%</span>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-[10px] text-mist mb-0.5">Active headcount</p>
                                    <p class="font-display text-2xl dark:text-white">1,248</p>
                                </div>
                                <svg width="90" height="28" viewBox="0 0 90 28" fill="none">
                                    <polyline points="0,22 15,20 30,16 45,18 60,10 75,8 90,3"
                                        style="stroke: var(--sparkline);" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>

                        <div class="px-4 py-3">
                            <p class="text-[10px] text-mist mb-2">Today's attendance</p>
                            <ul class="font-mono text-[10.5px] text-mist space-y-1.5">
                                <li class="border-l-2 border-amber pl-2 text-ink">09:02 · Amara Osei checked in</li>
                                <li class="border-l-2 border-amber pl-2 text-ink">09:05 · Levi Nakamura checked in
                                </li>
                                <li class="border-l-2 border-line pl-2">09:10 · Priya Chandra on leave</li>
                                <li class="border-l-2 border-amber pl-2 text-ink">09:14 · Dario Conti checked in</li>
                                <li class="border-l-2 border-line pl-2">09:20 · Elena Vasquez pending</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-4 flex items-center justify-center gap-1.5 rounded-lg border border-line bg-surface px-3 py-2 text-[11px] font-mono text-mist">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <path d="M3 10h18M8 2v4M16 2v4" />
                    </svg>
                    {{ date('j F, Y') }}
                </div>
            </div>
        </div>
    </div>
</div>
