@props([
    'variant' => 'slate',
    'dot' => false,
])

@php
$classes = match($variant) {
    'blue', 'primary' => 'bg-blue-50 text-blue-700 border-blue-200/80 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
    'emerald', 'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800',
    'amber', 'warning' => 'bg-amber-50 text-amber-700 border-amber-200/80 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800',
    'rose', 'danger' => 'bg-rose-50 text-rose-700 border-rose-200/80 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800',
    'sky', 'info' => 'bg-sky-50 text-sky-700 border-sky-200/80 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800',
    'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-200/80 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800',
    default => 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700',
};

$dotClasses = match($variant) {
    'blue', 'primary' => 'bg-blue-600',
    'emerald', 'success' => 'bg-emerald-500',
    'amber', 'warning' => 'bg-amber-500',
    'rose', 'danger' => 'bg-rose-500',
    'sky', 'info' => 'bg-sky-500',
    'indigo' => 'bg-indigo-500',
    default => 'bg-slate-400',
};
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold tracking-wide border ' . $classes]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses }}"></span>
    @endif
    {{ $slot }}
</span>
