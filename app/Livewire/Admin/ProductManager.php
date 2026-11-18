<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Master Produk — SORASA')]
class ProductManager extends Component
{
    public function render(): View
    {
        $products = Product::query()->with('recipe')->get();

        return view('livewire.admin.product-manager', compact('products'));
    }
}
