<?php

namespace App\Livewire\Admin;

use App\Models\Delivery;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Pengantaran & Kurir — SORASA')]
class DeliveryManager extends Component
{
    public function render(): View
    {
        $deliveries = Delivery::with(['sale.customer', 'sale.items.product'])->latest('tanggal_pengiriman')->get();

        return view('livewire.admin.delivery-manager', compact('deliveries'));
    }
}
