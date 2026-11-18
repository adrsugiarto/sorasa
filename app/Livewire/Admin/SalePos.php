<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\OpenPo;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Kasir POS — SORASA')]
class SalePos extends Component
{
    public function render(): View
    {
        $products = Product::where('is_active', true)->get();
        $customers = Customer::orderBy('nama')->get();
        $openPos = OpenPo::whereIn('status', ['open', 'draft'])->get();

        return view('livewire.admin.sale-pos', compact('products', 'customers', 'openPos'));
    }
}
