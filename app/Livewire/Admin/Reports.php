<?php

namespace App\Livewire\Admin;

use App\Models\OtherExpense;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Laporan Laba Rugi — SORASA')]
class Reports extends Component
{
    public function render(): View
    {
        $omzet = (float) Sale::sum('total_tagihan');
        $pembelian = (float) Purchase::sum('total_pembelian');
        $biayaLain = (float) OtherExpense::sum('nominal');
        $surplus = $omzet - $pembelian - $biayaLain;

        return view('livewire.admin.reports', compact('omzet', 'pembelian', 'biayaLain', 'surplus'));
    }
}
