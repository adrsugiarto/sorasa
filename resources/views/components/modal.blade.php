@props([
    'name',
    'title' => null,
    'maxWidth' => '2xl',
])

@php
$maxWidthClasses = match($maxWidth) {
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    '5xl' => 'max-w-5xl',
    'full' => 'max-w-full mx-4',
    default => 'max-w-2xl',
};
@endphp

<div
    x-data="{ show: false, focusables() { let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\']\'; return [...$el.querySelectorAll(selector)].filter(el => ! el.hasAttribute('disabled')) } }"
    x-init="$watch('show', value => { if (value) { document.body.classList.add('overflow-y-hidden') } else { document.body.classList.remove('overflow-y-hidden') } })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
    style="display: none;"
>
    <!-- Backdrop -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
        x-on:click="show = false"
    ></div>

    <!-- Modal Panel -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full {{ $maxWidthClasses }} mx-auto my-8 bg-white dark:bg-gray-900 rounded-lg shadow-xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all text-slate-800 dark:text-slate-200"
    >
        @if($title)
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
                <button
                    type="button"
                    x-on:click="show = false"
                    class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-gray-800 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="p-5">
            {{ $slot }}
        </div>

        @if(isset($footer))
            <div class="px-5 py-3 bg-slate-50/80 dark:bg-gray-800/50 border-t border-slate-100 dark:border-gray-800 flex items-center justify-end gap-2.5">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
