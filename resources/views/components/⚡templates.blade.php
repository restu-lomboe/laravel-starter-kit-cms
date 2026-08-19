<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <div class="mx-auto max-w-3xl px-6 py-12">

        <div class="flex items-start justify-between gap-4 mb-2">
            <div>
                <h1 class="font-display text-2xl font-medium tracking-tight text-ink">Form components</h1>
                <p class="mt-1 text-sm text-mist">Reference sheet for every input used across Anchor HR</p>
            </div>
        </div>

        <div class="border-t border-line mt-6 mb-8"></div>

        <div class="space-y-10">

            <!-- Text / Email -->
            <section>
                <h2 class="text-sm font-semibold text-ink mb-4">Text &amp; email</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Full name</label>
                        <input type="text" placeholder="Hiroko Pearson" class="field-input" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Work email</label>
                        <input type="email" placeholder="you@company.com" class="field-input" />
                    </div>
                </div>
            </section>

            <!-- Password -->
            <section>
                <h2 class="text-sm font-semibold text-ink mb-4">Password</h2>
                <div class="max-w-sm">
                    <label for="pw-demo" class="block text-xs font-medium text-mist mb-1.5">Password</label>
                    <div class="relative">
                        <input id="pw-demo" type="password" placeholder="••••••••••" class="field-input pr-10" />
                        <button type="button" wire:click="$js.togglePasswordField('pw-demo', $event.currentTarget)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-mist hover:text-ink transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Number / Date -->
            <section>
                <h2 class="text-sm font-semibold text-ink mb-4">Number &amp; date</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Years of experience</label>
                        <input type="number" placeholder="0" class="field-input" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Start date</label>
                        <input type="date" class="field-input" />
                    </div>
                </div>
            </section>

            <!-- Prefix / suffix -->
            <section>
                <h2 class="text-sm font-semibold text-ink mb-4">Prefixed input</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Monthly salary</label>
                        <div
                            class="flex items-center rounded-lg border border-line bg-surface overflow-hidden focus-within:border-amber focus-within:ring-1 focus-within:ring-amber transition">
                            <span class="px-3 text-sm text-mist font-mono border-r border-line">IDR</span>
                            <input type="text" placeholder="0"
                                class="w-full bg-transparent px-3 py-2.5 text-sm text-ink outline-none" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Search</label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-mist text-xs"></i>
                            <input type="text" placeholder="Search employees…" class="field-input pl-9" />
                        </div>
                    </div>
                </div>
            </section>

            <!-- Textarea -->
            <section>
                <h2 class="text-sm font-semibold text-ink mb-4">Textarea</h2>
                <div class="max-w-md">
                    <label class="block text-xs font-medium text-mist mb-1.5">Notes</label>
                    <textarea rows="3" placeholder="Add a note about this employee…" class="field-input resize-none"></textarea>
                </div>
            </section>

            <!-- Select -->
            <section>
                <h2 class="text-sm font-semibold text-ink mb-4">Select</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Department</label>
                        <select class="field-input">
                            <option>Engineering</option>
                            <option>Design</option>
                            <option>Finance</option>
                            <option>People Operations</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Employment type</label>
                        <select class="field-input" disabled>
                            <option>Full-time</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- Checkbox / Radio -->
            <section>
                <h2 class="text-sm font-semibold text-ink mb-4">Checkbox &amp; radio</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm text-ink select-none">
                            <input type="checkbox" checked
                                class="h-3.5 w-3.5 rounded border-line bg-surface accent-amber" />
                            Send welcome email
                        </label>
                        <label class="flex items-center gap-2 text-sm text-mist select-none">
                            <input type="checkbox" class="h-3.5 w-3.5 rounded border-line bg-surface accent-amber" />
                            Require password reset on first login
                        </label>
                    </div>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm text-ink select-none">
                            <input type="radio" name="employment-status" checked
                                class="h-3.5 w-3.5 border-line bg-surface accent-amber" />
                            Active
                        </label>
                        <label class="flex items-center gap-2 text-sm text-mist select-none">
                            <input type="radio" name="employment-status"
                                class="h-3.5 w-3.5 border-line bg-surface accent-amber" />
                            On leave
                        </label>
                    </div>
                </div>
            </section>

            <!-- Toggle switch -->
            <section>
                <h2 class="text-sm font-semibold text-ink mb-4">Toggle switch</h2>
                <div class="flex items-center justify-between max-w-sm rounded-xl border border-line bg-card p-4">
                    <div>
                        <p class="text-sm font-medium text-ink">Two-factor authentication</p>
                        <p class="text-xs text-mist mt-0.5">Require a TOTP code at sign-in</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" checked class="peer sr-only">
                        <div
                            class="w-9 h-5 rounded-full bg-surface-2 border border-line peer-checked:bg-amber transition-colors">
                        </div>
                        <span
                            class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-card border border-line transition-transform peer-checked:translate-x-4 peer-checked:border-transparent"></span>
                    </label>
                </div>
            </section>

            <!-- File upload -->
            <section>
                <h2 class="text-sm font-semibold text-ink mb-4">File upload</h2>
                <label for="file-demo"
                    class="max-w-md flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-line bg-surface px-6 py-8 text-center cursor-pointer hover:border-mist transition">
                    <i class="fa-solid fa-cloud-arrow-up text-lg text-mist"></i>
                    <p class="text-sm text-ink"><span class="text-amber-deep font-medium">Click to upload</span> or
                        drag and drop</p>
                    <p class="text-xs text-mist">PDF or DOCX, up to 10MB</p>
                    <input id="file-demo" type="file" class="hidden" />
                </label>
            </section>

            <!-- States -->
            <section>
                <h2 class="text-sm font-semibold text-ink mb-4">Field states</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Helper text</label>
                        <input type="text" placeholder="e.g. EMP-2024-001" class="field-input" />
                        <p class="mt-1.5 text-xs text-mist">This ID is generated automatically and cannot be changed
                            later</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Error state</label>
                        <input type="email" value="notanemail" class="field-input"
                            style="border-color:#ef4444;" />
                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#ef4444;">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            Please enter a valid email address
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Success state</label>
                        <input type="text" value="anchorhr.northwind" class="field-input"
                            style="border-color:#10b981;" />
                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color:#10b981;">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                            This workspace name is available
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-mist mb-1.5">Disabled</label>
                        <input type="text" value="Cannot be edited" disabled class="field-input" />
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

<script>
    this.$js.togglePasswordField = (inputId, btn) => {
        console.log(inputId);
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        var isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !isPassword);
        icon.classList.toggle('fa-eye-slash', isPassword);
    }
</script>
