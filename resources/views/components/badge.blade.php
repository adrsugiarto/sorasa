@props([
    'variant' => 'slate',
    'dot' => false,
])

@php
$classes = match($variant) {
    'emerald', 'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
    'amber', 'warning' => 'bg-amber-50 text-amber-700 border-amber-200/80',
    'rose', 'danger' => 'bg-rose-50 text-rose-700 border-rose-200/80',
    'sky', 'info' => 'bg-sky-50 text-sky-700 border-sky-200/80',
    'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-200/80',
    default => 'bg-slate-50 text-slate-700 border-slate-200',
};

$dotClasses = match($variant) {
    'emerald', 'success' => 'bg-emerald-500',
    'amber', 'warning' => 'bg-amber-500',
    'rose', 'danger' => 'bg-rose-500',
    'sky', 'info' => 'bg-sky-500',
    'indigo' => 'bg-indigo-500',
    default => 'bg-slate-400',
};
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border ' . $classes]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses }}"></span>
    @endif
    {{ $slot }}
</span>
