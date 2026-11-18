<?php

use App\Livewire\Admin\CustomerManager;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\DeliveryManager;
use App\Livewire\Admin\OpenPoManager;
use App\Livewire\Admin\OtherExpenseManager;
use App\Livewire\Admin\PaymentManager;
use App\Livewire\Admin\ProductManager;
use App\Livewire\Admin\PurchaseManager;
use App\Livewire\Admin\RecipeManager;
use App\Livewire\Admin\Reports;
use App\Livewire\Admin\SaleList;
use App\Livewire\Admin\SalePos;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\UserManager;
use App\Livewire\Auth\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Admin panel routes protected by auth
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/open-po', OpenPoManager::class)->name('open-po');
    Route::get('/pos', SalePos::class)->name('sales.pos');
    Route::get('/sales', SaleList::class)->name('sales.index');
    Route::get('/deliveries', DeliveryManager::class)->name('deliveries');
    Route::get('/payments', PaymentManager::class)->name('payments');
    Route::get('/purchases', PurchaseManager::class)->name('purchases');
    Route::get('/expenses', OtherExpenseManager::class)->name('expenses');
    Route::get('/products', ProductManager::class)->name('products');
    Route::get('/recipes', RecipeManager::class)->name('recipes');
    Route::get('/customers', CustomerManager::class)->name('customers');
    Route::get('/reports', Reports::class)->name('reports');
    Route::get('/users', UserManager::class)->name('users');
    Route::get('/settings', Settings::class)->name('settings');
});
