@props([
    'value' => 0,
    'withSymbol' => true,
    'sign' => false,
])

@php
$amount = (float) $value;
$formatted = number_format(abs($amount), 0, ',', '.');
$prefix = $withSymbol ? 'Rp ' : '';
if ($sign && $amount > 0) {
    $prefix = '+' . $prefix;
} elseif ($amount < 0) {
    $prefix = '-' . $prefix;
}
@endphp

<span {{ $attributes->merge(['class' => 'tabular-nums tracking-tight whitespace-nowrap']) }}>{{ $prefix }}{{ $formatted }}</span>
