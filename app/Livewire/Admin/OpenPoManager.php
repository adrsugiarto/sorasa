<?php

namespace App\Livewire\Admin;

use App\Models\OpenPo;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Batch Open PO — SORASA')]
class OpenPoManager extends Component
{
    public function render(): View
    {
        $batches = OpenPo::query()->withCount('sales')->latest('tanggal_buka')->get();

        return view('livewire.admin.open-po-manager', compact('batches'));
    }
}
