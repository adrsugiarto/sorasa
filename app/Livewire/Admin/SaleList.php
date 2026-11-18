<?php

namespace App\Livewire\Admin;

use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Daftar Penjualan — SORASA')]
class SaleList extends Component
{
    public function render(): View
    {
        $sales = Sale::with(['customer', 'openPo', 'items', 'payments'])->latest('tanggal_pemesanan')->get();

        return view('livewire.admin.sale-list', compact('sales'));
    }
}
