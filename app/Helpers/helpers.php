<?php

if (! function_exists('format_rupiah')) {
    /**
     * Format a numeric amount to Indonesian Rupiah.
     */
    function format_rupiah(float|int|string|null $amount, bool $withSymbol = true): string
    {
        $num = (float) ($amount ?? 0);
        $formatted = number_format($num, 0, ',', '.');

        return $withSymbol ? 'Rp '.$formatted : $formatted;
    }
}
