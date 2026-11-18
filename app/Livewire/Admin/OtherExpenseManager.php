<?php

namespace App\Livewire\Admin;

use App\Models\OtherExpense;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Pengeluaran Lain — SORASA')]
class OtherExpenseManager extends Component
{
    public function render(): View
    {
        $expenses = OtherExpense::with('openPo')->latest('tanggal_pengeluaran')->get();

        return view('livewire.admin.other-expense-manager', compact('expenses'));
    }
}
