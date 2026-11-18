<?php

namespace App\Livewire\Admin;

use App\Models\OpenPo;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Daftar Penjualan — SORASA')]
class SaleList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $selectedOpenPoId = '';

    public string $selectedStatusPembayaran = '';

    public string $selectedStatusOrder = '';

    // Receipt View Modal
    public bool $showReceiptModal = false;

    public ?Sale $selectedSale = null;

    /**
     * @var array<string, string|null>
     */
    public array $storeSettings = [];

    /**
     * Reset pagination when search query changes.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Update order status directly.
     */
    public function updateOrderStatus(int $saleId, string $status): void
    {
        $sale = Sale::query()->findOrFail($saleId);
        $sale->status_order = $status;
        $sale->save();

        session()->flash('success', "Status order {$sale->nomor_order} berhasil diubah menjadi ".strtoupper(str_replace('_', ' ', $status)).'.');
    }

    /**
     * Open receipt preview modal.
     */
    public function viewReceipt(int $saleId): void
    {
        $this->selectedSale = Sale::query()
            ->with(['customer', 'openPo', 'items.product', 'delivery', 'payments'])
            ->findOrFail($saleId);

        $this->storeSettings = Setting::all()->pluck('value', 'key')->toArray();
        $this->showReceiptModal = true;
    }

    /**
     * Close receipt modal.
     */
    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->selectedSale = null;
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        $query = Sale::query()
            ->with(['customer', 'openPo', 'items', 'payments'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nomor_order', 'like', '%'.$this->search.'%')
                        ->orWhereHas('customer', function ($c) {
                            $c->where('nama', 'like', '%'.$this->search.'%')
                                ->orWhere('no_whatsapp', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->selectedOpenPoId, function ($q) {
                $q->where('open_po_id', $this->selectedOpenPoId);
            })
            ->when($this->selectedStatusPembayaran, function ($q) {
                $q->where('status_pembayaran', $this->selectedStatusPembayaran);
            })
            ->when($this->selectedStatusOrder, function ($q) {
                $q->where('status_order', $this->selectedStatusOrder);
            });

        $totalOmzetFiltered = (float) $query->clone()->sum('total_tagihan');
        $sales = $query->latest('tanggal_pemesanan')->latest('id')->paginate(15);
        $openPos = OpenPo::query()->latest('tanggal_buka')->get();

        return view('livewire.admin.sale-list', [
            'sales' => $sales,
            'openPos' => $openPos,
            'totalOmzetFiltered' => $totalOmzetFiltered,
        ]);
    }
}
