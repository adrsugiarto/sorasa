<?php

namespace App\Livewire\Admin;

use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Pembayaran & Piutang — SORASA')]
class PaymentManager extends Component
{
    public function render(): View
    {
        $unpaidSales = Sale::with(['customer', 'payments', 'openPo'])
            ->whereIn('status_pembayaran', ['belum_bayar', 'dp'])
            ->get();

        return view('livewire.admin.payment-manager', compact('unpaidSales'));
    }
}
