<?php

namespace App\Livewire\Admin;

use App\Models\Recipe;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Resep & Estimasi HPP — SORASA')]
class RecipeManager extends Component
{
    public function render(): View
    {
        $recipes = Recipe::with(['product', 'items'])->get();

        return view('livewire.admin.recipe-manager', compact('recipes'));
    }
}
