<?php

namespace App\Livewire\Admin;

use App\Models\Purchase;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Pembelian Bahan — SORASA')]
class PurchaseManager extends Component
{
    public function render(): View
    {
        $purchases = Purchase::with('openPo')->latest('tanggal_pembelian')->get();

        return view('livewire.admin.purchase-manager', compact('purchases'));
    }
}
