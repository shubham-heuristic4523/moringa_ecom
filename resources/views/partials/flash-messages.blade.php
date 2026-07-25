{{--
 | Partial: partials/flash-messages.blade.php
 | Purpose: Renders Laravel session flash messages (success, error, warning, info).
--}}
@if(session()->hasAny(['success', 'error', 'warning', 'info']))
    <div class="fixed top-4 right-4 z-[100] flex flex-col gap-sm max-w-sm w-full" id="flash-container" role="alert">

        @if(session('success'))
            <div class="flex items-start gap-md p-md bg-secondary-container text-on-secondary-container rounded-lg shadow-md border border-secondary/20 animate-in slide-in-from-right duration-300">
                <span class="material-symbols-outlined text-secondary shrink-0 mt-px fill">check_circle</span>
                <p class="font-body-sm text-body-sm flex-1">{{ session('success') }}</p>
                <button onclick="this.closest('[role=alert] > div').remove()" class="text-on-secondary-container/60 hover:text-on-secondary-container transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-start gap-md p-md bg-error-container text-on-error-container rounded-lg shadow-md border border-error/20 animate-in slide-in-from-right duration-300">
                <span class="material-symbols-outlined text-error shrink-0 mt-px fill">error</span>
                <p class="font-body-sm text-body-sm flex-1">{{ session('error') }}</p>
                <button onclick="this.closest('[role=alert] > div').remove()" class="text-on-error-container/60 hover:text-on-error-container transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="flex items-start gap-md p-md bg-tertiary-fixed text-on-tertiary-container rounded-lg shadow-md border border-tertiary/20 animate-in slide-in-from-right duration-300">
                <span class="material-symbols-outlined text-tertiary shrink-0 mt-px fill">warning</span>
                <p class="font-body-sm text-body-sm flex-1">{{ session('warning') }}</p>
                <button onclick="this.closest('[role=alert] > div').remove()" class="text-on-tertiary-container/60 hover:text-on-tertiary-container transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div class="flex items-start gap-md p-md bg-surface-container text-on-surface rounded-lg shadow-md border border-outline-variant/50 animate-in slide-in-from-right duration-300">
                <span class="material-symbols-outlined text-primary shrink-0 mt-px fill">info</span>
                <p class="font-body-sm text-body-sm flex-1">{{ session('info') }}</p>
                <button onclick="this.closest('[role=alert] > div').remove()" class="text-on-surface-variant hover:text-on-surface transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

    </div>
@endif
