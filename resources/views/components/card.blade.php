@props([
    'title' => null,
    'description' => null,
    'action' => null,
    'footer' => null,
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-850 rounded-2xl border border-gray-200/90 dark:border-gray-800 shadow-2xs overflow-hidden']) }}>
    @if($title || $action)
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between gap-4">
            <div>
                @if($title)
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white tracking-tight">{{ $title }}</h3>
                @endif
                @if($description)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $description }}</p>
                @endif
            </div>
            @if($action)
                <div class="shrink-0">
                    {{ $action }}
                </div>
            @endif
        </div>
    @endif

    <div @class([
        'p-5 sm:p-6' => ! $noPadding,
        'p-0' => $noPadding,
    ])>
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-5 py-3 bg-gray-50/80 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800 text-xs text-gray-600 dark:text-gray-400">
            {{ $footer }}
        </div>
    @endif
</div>
