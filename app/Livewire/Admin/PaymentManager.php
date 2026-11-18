<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Pembayaran & Piutang — SORASA')]
class PaymentManager extends Component
{
    use WithFileUploads, WithPagination;

    public string $activeTab = 'piutang'; // 'piutang' or 'riwayat'

    public string $search = '';

    // Record Payment Modal
    public bool $showPaymentModal = false;

    public ?int $saleId = null;

    public ?Sale $selectedSale = null;

    public string $tanggal_bayar = '';

    public string $jenis_pembayaran = 'pelunasan'; // dp, cicilan, pelunasan

    public float|int|string $nominal_bayar = 0;

    public string $metode_pembayaran = 'Transfer BCA';

    /**
     * @var TemporaryUploadedFile|null
     */
    public $bukti_bayar = null;

    public ?string $catatan = '';

    // Receipt Proof Modal
    public bool $showProofModal = false;

    public ?string $proofUrl = null;

    /**
     * Payment methods list.
     *
     * @var array<int, string>
     */
    public array $paymentMethods = [
        'Transfer BCA',
        'QRIS',
        'Cash',
        'Transfer Mandiri',
        'Transfer BNI / BRI',
        'E-Wallet (GoPay/OVO/ShopeePay)',
    ];

    /**
     * Reset pagination when searching or changing tabs.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    /**
     * Open record payment modal for a specific sale.
     */
    public function openPaymentModal(int $saleId): void
    {
        $this->resetValidation();
        $sale = Sale::query()->with(['customer', 'payments'])->findOrFail($saleId);

        $this->saleId = $sale->id;
        $this->selectedSale = $sale;
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
        if ($this->selectedSale) {
            $this->nominal_bayar = (float) $this->selectedSale->remaining_balance;
            $this->jenis_pembayaran = 'pelunasan';
        }
    }

    /**
     * Close payment modal.
     */
    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->saleId = null;
        $this->selectedSale = null;
    }

    /**
     * Save payment and auto-update sale payment status.
     */
    public function savePayment(): void
    {
        $this->validate([
            'saleId' => ['required', 'exists:sales,id'],
            'tanggal_bayar' => ['required', 'date'],
            'jenis_pembayaran' => ['required', 'in:dp,cicilan,pelunasan'],
            'nominal_bayar' => ['required', 'numeric', 'min:1'],
            'metode_pembayaran' => ['required', 'string', 'max:100'],
            'bukti_bayar' => ['nullable', 'image', 'max:3072'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () {
            $sale = Sale::query()->with('payments')->findOrFail($this->saleId);

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
     * View proof image.
     */
    public function viewProof(string $path): void
    {
        $this->proofUrl = asset('storage/'.$path);
        $this->showProofModal = true;
    }

    public function closeProofModal(): void
    {
        $this->showProofModal = false;
        $this->proofUrl = null;
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        if ($this->activeTab === 'piutang') {
            // Unpaid sales query
            $query = Sale::query()
                ->with(['customer', 'openPo', 'payments'])
                ->whereIn('status_pembayaran', ['belum_bayar', 'dp'])
                ->when($this->search, function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('nomor_order', 'like', '%'.$this->search.'%')
                            ->orWhereHas('customer', function ($c) {
                                $c->where('nama', 'like', '%'.$this->search.'%')
                                    ->orWhere('no_whatsapp', 'like', '%'.$this->search.'%');
                            });
                    });
                });

            $totalPiutangAll = 0;
            $allUnpaid = $query->clone()->get();
            foreach ($allUnpaid as $s) {
                $totalPiutangAll += $s->remaining_balance;
            }

            $unpaidSales = $query->latest('tanggal_pemesanan')->paginate(15);

            return view('livewire.admin.payment-manager', [
                'unpaidSales' => $unpaidSales,
                'totalPiutangAll' => $totalPiutangAll,
            ]);
        }

        // Tab: Riwayat Pembayaran Masuk
        $query = Payment::query()
            ->with(['sale.customer', 'sale.openPo'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('metode_pembayaran', 'like', '%'.$this->search.'%')
                        ->orWhere('catatan', 'like', '%'.$this->search.'%')
                        ->orWhereHas('sale', function ($s) {
                            $s->where('nomor_order', 'like', '%'.$this->search.'%')
                                ->orWhereHas('customer', function ($c) {
                                    $c->where('nama', 'like', '%'.$this->search.'%');
                                });
                        });
                });
            });

        $totalPaidAll = (float) $query->clone()->where('status_verifikasi', 'verified')->sum('nominal_bayar');
        $paymentsHistory = $query->latest('tanggal_bayar')->latest('id')->paginate(15);

        return view('livewire.admin.payment-manager', [
            'paymentsHistory' => $paymentsHistory,
            'totalPaidAll' => $totalPaidAll,
        ]);
    }
}
