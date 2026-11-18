<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Pengaturan Usaha — SORASA')]
class Settings extends Component
{
    public function render(): View
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('livewire.admin.settings', compact('settings'));
    }
}
