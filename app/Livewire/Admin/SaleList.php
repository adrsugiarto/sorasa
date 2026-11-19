<?php

namespace App\Livewire\Admin;

use App\Models\OpenPo;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Daftar Penjualan — SORASA')]
class SaleList extends Component
{
    use WithFileUploads;
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
     * @var array<int, array{id: string, bank_name: string, account_number: string, account_name: string, is_primary: bool}>
     */
    public array $bankAccounts = [];

    // Catat Pembayaran Masuk Modal
    public bool $showPaymentModal = false;

    public ?int $paymentSaleId = null;

    public ?Sale $paymentSale = null;

    public string $tanggal_bayar = '';

    public string $jenis_pembayaran = 'pelunasan';

    public $nominal_bayar = '';

    public string $metode_pembayaran = 'Transfer BCA';

    public $bukti_bayar = null;

    public string $catatan = '';

    /**
     * @var array<int, string>
     */
    public array $paymentMethods = [
        'Transfer BCA',
        'Transfer Mandiri',
        'Transfer BRI',
        'Transfer BNI',
        'QRIS',
        'Cash / Tunai',
        'GoPay',
        'ShopeePay',
        'Lainnya',
    ];

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
        $sale = Sale::query()->with('delivery')->findOrFail($saleId);
        $sale->status_order = $status;
        $sale->save();

        if ($sale->delivery) {
            if ($status === 'siap_kirim' && in_array($sale->delivery->status_pengantaran, ['menunggu'])) {
                $sale->delivery->status_pengantaran = 'dijadwalkan';
                $sale->delivery->save();
            } elseif ($status === 'selesai' && $sale->delivery->status_pengantaran !== 'terkirim') {
                $sale->delivery->status_pengantaran = 'terkirim';
                $sale->delivery->save();
            } elseif ($status === 'dibatalkan') {
                $sale->delivery->status_pengantaran = 'menunggu';
                $sale->delivery->save();
            }
        }

        session()->flash('success', "Status order {$sale->nomor_order} berhasil diubah menjadi ".strtoupper(str_replace('_', ' ', $status)).'.');
    }

    /**
     * Update delivery status directly from sale list.
     */
    public function updateDeliveryStatus(int $saleId, string $deliveryStatus): void
    {
        $sale = Sale::query()->with('delivery')->findOrFail($saleId);
        if ($sale->delivery) {
            $sale->delivery->status_pengantaran = $deliveryStatus;
            $sale->delivery->save();

            // Auto-sync sale status
            if ($deliveryStatus === 'terkirim' && $sale->status_order !== 'selesai') {
                $sale->status_order = 'selesai';
                $sale->save();
            } elseif ($deliveryStatus === 'dalam_pengiriman' && in_array($sale->status_order, ['menunggu_konfirmasi', 'diproses'])) {
                $sale->status_order = 'siap_kirim';
                $sale->save();
            }

            session()->flash('success', "Status pengantaran untuk order {$sale->nomor_order} diperbarui ke ".strtoupper(str_replace('_', ' ', $deliveryStatus)).'.');
        }
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
        $this->bankAccounts = Setting::getBankAccounts();
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
     * Open record payment modal for a specific sale.
     */
    public function openPaymentModal(int $saleId): void
    {
        $this->resetValidation();
        $sale = Sale::query()->with(['customer', 'payments'])->findOrFail($saleId);

        $this->paymentSaleId = $sale->id;
        $this->paymentSale = $sale;
        $this->tanggal_bayar = Carbon::today()->format('Y-m-d');
        $this->nominal_bayar = (float) $sale->remaining_balance;
        $this->jenis_pembayaran = $sale->status_pembayaran === 'dp' ? 'pelunasan' : ($this->nominal_bayar >= $sale->total_tagihan ? 'pelunasan' : 'dp');
        $this->metode_pembayaran = 'Transfer BCA';
        $this->bukti_bayar = null;
        $this->catatan = '';

        $this->showPaymentModal = true;
    }

    /**
     * Set nominal to pay full remaining balance.
     */
    public function fillFullPayment(): void
    {
        if ($this->paymentSale) {
            $this->nominal_bayar = (float) $this->paymentSale->remaining_balance;
            $this->jenis_pembayaran = 'pelunasan';
        }
    }

    /**
     * Close payment modal.
     */
    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->paymentSaleId = null;
        $this->paymentSale = null;
        $this->bukti_bayar = null;
        $this->resetValidation();
    }

    /**
     * Save payment and auto-update sale payment status.
     */
    public function savePayment(): void
    {
        $this->validate([
            'paymentSaleId' => ['required', 'exists:sales,id'],
            'tanggal_bayar' => ['required', 'date'],
            'jenis_pembayaran' => ['required', 'in:dp,cicilan,pelunasan'],
            'nominal_bayar' => ['required', 'numeric', 'min:1'],
            'metode_pembayaran' => ['required', 'string', 'max:100'],
            'bukti_bayar' => ['nullable', 'image', 'max:3072'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () {
            $sale = Sale::query()->with('payments')->findOrFail($this->paymentSaleId);

            $proofPath = null;
            if ($this->bukti_bayar) {
                $proofPath = $this->bukti_bayar->store('payments', 'public');
            }

            // Create Payment record
            $sale->payments()->create([
                'tanggal_bayar' => $this->tanggal_bayar,
                'jenis_pembayaran' => $this->jenis_pembayaran,
                'nominal_bayar' => (float) $this->nominal_bayar,
                'metode_pembayaran' => $this->metode_pembayaran,
                'bukti_bayar' => $proofPath,
                'status_verifikasi' => 'verified',
                'catatan' => $this->catatan,
            ]);

            // Re-evaluate Sale Payment Status
            $newTotalPaid = (float) $sale->payments()->where('status_verifikasi', 'verified')->sum('nominal_bayar');

            if ($newTotalPaid >= (float) $sale->total_tagihan) {
                $sale->status_pembayaran = 'lunas';
            } elseif ($newTotalPaid > 0) {
                $sale->status_pembayaran = 'dp';
            } else {
                $sale->status_pembayaran = 'belum_bayar';
            }

            $sale->save();
        });

        session()->flash('success', 'Pembayaran berhasil dicatat!');
        $this->closePaymentModal();
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        $query = Sale::query()
            ->with(['customer', 'openPo', 'items', 'payments', 'delivery'])
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
