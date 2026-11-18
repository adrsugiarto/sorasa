<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\OpenPo;
use App\Models\OtherExpense;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Dashboard — SORASA')]
class Dashboard extends Component
{
    public function render(): View
    {
        $omzet = (float) Sale::sum('total_tagihan');
        $pembelian = (float) Purchase::sum('total_pembelian');
        $biayaLain = (float) OtherExpense::sum('nominal');
        $surplus = $omzet - $pembelian - $biayaLain;
        $totalTerbayar = (float) Payment::where('status_verifikasi', 'verified')->sum('nominal_bayar');
        $totalPiutang = max(0, $omzet - $totalTerbayar);

        return view('livewire.admin.dashboard', [
            'omzet' => $omzet,
            'pembelian' => $pembelian,
            'biayaLain' => $biayaLain,
            'surplus' => $surplus,
            'totalTerbayar' => $totalTerbayar,
            'totalPiutang' => $totalPiutang,
            'totalOrders' => Sale::count(),
            'totalCustomers' => Customer::count(),
            'activeOpenPo' => OpenPo::where('status', 'open')->first(),
        ]);
    }
}
