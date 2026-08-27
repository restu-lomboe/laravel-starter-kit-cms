<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Events\TwoFactorAuthenticationFailed;
use Laravel\Fortify\Events\ValidTwoFactorAuthenticationCodeProvided;
use Laravel\Fortify\Fortify;
use Livewire\Component;

new class extends Component {
    public string $code = '';
    public string $recovery_code = '';
    public bool $recovery = false;

    public function mount(): void
    {
        if (! session()->has('login.id')) {
            $this->redirectRoute('login', navigate: true);

            return;
        }

        if (Auth::check()) {
            $this->redirectRoute('admin.dashboard', navigate: true);
        }
    }

    public function toggleRecovery(): void
    {
        $this->recovery = ! $this->recovery;
        $this->resetErrorBag();
        $this->reset('code', 'recovery_code');
    }

    public function challenge(): void
    {
        $this->validate($this->recovery ? [
            'recovery_code' => ['required', 'string'],
        ] : [
            'code' => ['required', 'string'],
        ]);

        $userId = session()->get('login.id');
        $user = $userId ? User::find($userId) : null;

        if (! $user) {
            session()->forget(['login.id', 'login.remember']);

            $this->redirectRoute('login', navigate: true);

            return;
        }

        if ($this->recovery) {
            $validCode = collect($user->recoveryCodes())->first(function ($code) {
                return hash_equals($code, $this->recovery_code) ? $code : null;
            });

            if (! $validCode) {
                event(new TwoFactorAuthenticationFailed($user));
                $this->addError('recovery_code', __('The provided two factor recovery code was invalid.'));

                return;
            }

            $user->replaceRecoveryCode($validCode);
        } else {
            $secret = Fortify::currentEncrypter()->decrypt($user->two_factor_secret);
            $isValid = app(TwoFactorAuthenticationProvider::class)->verify($secret, $this->code);

            if (! $isValid) {
                event(new TwoFactorAuthenticationFailed($user));
                $this->addError('code', __('The provided two factor authentication code was invalid.'));

                return;
            }
        }

        event(new ValidTwoFactorAuthenticationCodeProvided($user));

        $remember = (bool) session()->pull('login.remember', false);
        session()->forget('login.id');

        Auth::guard(config('fortify.guard', 'web'))->login($user, $remember);
        session()->regenerate();

        $this->redirectIntended(default: route('admin.dashboard'), navigate: true);
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
                <div wire:transition class="w-full">
                    <div class="mx-auto w-full max-w-sm mt-10">
                        <h1 class="font-display text-3xl font-medium tracking-tight text-ink">Two-factor
                            authentication</h1>
                        <p class="mt-2 text-xs leading-relaxed text-mist">
                            @if ($recovery)
                                Enter one of your recovery codes to continue.
                            @else
                                Enter the 6-digit code from your authenticator app to continue.
                            @endif
                        </p>

                        <form wire:submit="challenge" class="mt-6 space-y-5">

                            @include('components.messages')

                            @if ($recovery)
                                <div>
                                    <label for="recovery_code"
                                        class="block text-xs font-medium text-mist mb-1.5">Recovery code</label>
                                    <input id="recovery_code" type="text" wire:model="recovery_code"
                                        placeholder="xxxx-xxxx-xxxx" autocomplete="one-time-code"
                                        class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition" />
                                    @error('recovery_code')
                                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            @else
                                <div>
                                    <label for="code"
                                        class="block text-xs font-medium text-mist mb-1.5">Authentication code</label>
                                    <input id="code" type="text" wire:model="code" placeholder="000000"
                                        inputmode="numeric" autocomplete="one-time-code" maxlength="6"
                                        class="w-full rounded-lg border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder-mist/60 outline-none focus:border-amber focus:ring-1 focus:ring-amber transition tracking-[0.3em] text-center font-mono" />
                                    @error('code')
                                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            @endif

                            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                                class="w-full rounded-lg bg-amber py-2.5 text-sm font-semibold text-on-amber hover:brightness-95 transition disabled:cursor-wait">
                                <span wire:loading.remove>Verify</span>
                                <span wire:loading>Verifying…</span>
                            </button>

                            <div class="flex items-center justify-between text-xs">
                                <button type="button" wire:click="toggleRecovery"
                                    class="text-amber-deep hover:text-ink transition">
                                    {{ $recovery ? 'Use an authentication code' : 'Use a recovery code' }}
                                </button>
                                <a href="{{ route('login') }}" wire:navigate class="text-mist hover:text-ink transition">
                                    Back to sign in
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
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

<script>
    this.$js.togglePasswordField = (inputId, btn) => {
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        var isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('fa-eye-slash', !isPassword);
        icon.classList.toggle('fa-eye', isPassword);
    }
</script>
