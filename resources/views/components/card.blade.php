@props([
    'title' => null,
    'description' => null,
    'action' => null,
    'footer' => null,
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-900 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs overflow-hidden']) }}>
    @if($title || $action)
        <div class="px-4 py-3 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between gap-4">
            <div>
                @if($title)
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white tracking-tight">{{ $title }}</h3>
                @endif
                @if($description)
                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">{{ $description }}</p>
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
        'p-4 sm:p-5' => ! $noPadding,
        'p-0' => $noPadding,
    ])>
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-4 py-2.5 bg-slate-50/80 dark:bg-gray-800/50 border-t border-slate-100 dark:border-gray-800 text-xs text-slate-600 dark:text-gray-400">
            {{ $footer }}
        </div>
    @endif
</div>
