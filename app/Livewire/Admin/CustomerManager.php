<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Database Customer — SORASA')]
class CustomerManager extends Component
{
    public function render(): View
    {
        $customers = Customer::with('sales')->get();

        return view('livewire.admin.customer-manager', compact('customers'));
    }
}
