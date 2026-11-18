<?php

namespace App\Livewire\Admin;

use App\Models\OpenPo;
use App\Models\Purchase;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Pembelian Bahan — SORASA')]
class PurchaseManager extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public string $selectedOpenPoId = '';

    public ?string $startDate = null;

    public ?string $endDate = null;

    // Modal Form properties
    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $purchaseId = null;

    public ?int $open_po_id = null;

    public string $tanggal_pembelian = '';

    public string $nama_toko_supplier = '';

    public string $nama_barang = '';

    public float|int|string $jumlah = 1;

    public string $satuan = 'kg';

    public float|int|string $harga_satuan = 0;

    public string $metode_pembayaran = 'Cash';

    public ?string $keterangan = '';

    /**
     * @var TemporaryUploadedFile|null
     */
    public $foto_nota = null;

    public ?string $existingFotoNota = null;

    // Receipt Modal preview
    public bool $showReceiptModal = false;

    public ?string $receiptUrl = null;

    public ?string $receiptTitle = null;

    /**
     * Satuan options.
     *
     * @var array<int, string>
     */
    public array $satuanOptions = [
        'kg',
        'gram',
        'ekor',
        'liter',
        'ml',
        'pack',
        'pcs',
        'tray',
        'ikat',
        'dus',
    ];

    /**
     * Payment methods.
     *
     * @var array<int, string>
     */
    public array $paymentMethods = [
        'Cash',
        'Transfer',
        'QRIS',
        'E-Wallet',
    ];

    /**
     * Reset pagination when search query changes.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when PO filter changes.
     */
    public function updatingSelectedOpenPoId(): void
    {
        $this->resetPage();
    }

    /**
     * Validation rules.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'open_po_id' => ['nullable', 'exists:open_pos,id'],
            'tanggal_pembelian' => ['required', 'date'],
            'nama_toko_supplier' => ['required', 'string', 'max:255'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'numeric', 'min:0.01'],
            'satuan' => ['required', 'string', 'max:50'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
            'metode_pembayaran' => ['required', 'in:Cash,Transfer,QRIS,E-Wallet'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'foto_nota' => ['nullable', 'image', 'max:3072'],
        ];
    }

    /**
     * Calculated total purchase amount.
     */
    public function getTotalPembelianProperty(): float
    {
        $qty = (float) $this->jumlah;
        $price = (float) $this->harga_satuan;

        return round($qty * $price, 2);
    }

    /**
     * Open create purchase modal.
     */
    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->purchaseId = null;
        $this->isEditing = false;

        $activePo = OpenPo::where('status', 'open')->first();
        $this->open_po_id = $activePo ? $activePo->id : null;
        $this->tanggal_pembelian = Carbon::today()->format('Y-m-d');
        $this->nama_toko_supplier = '';
        $this->nama_barang = '';
        $this->jumlah = 1;
        $this->satuan = 'kg';
        $this->harga_satuan = 0;
        $this->metode_pembayaran = 'Cash';
        $this->keterangan = '';
        $this->foto_nota = null;
        $this->existingFotoNota = null;

        $this->showModal = true;
    }

    /**
     * Open edit purchase modal.
     */
    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $p = Purchase::query()->findOrFail($id);

        $this->purchaseId = $p->id;
        $this->open_po_id = $p->open_po_id;
        $this->tanggal_pembelian = $p->tanggal_pembelian->format('Y-m-d');
        $this->nama_toko_supplier = $p->nama_toko_supplier;
        $this->nama_barang = $p->nama_barang;
        $this->jumlah = (float) $p->jumlah;
        $this->satuan = $p->satuan;
        $this->harga_satuan = (float) $p->harga_satuan;
        $this->metode_pembayaran = $p->metode_pembayaran;
        $this->keterangan = $p->keterangan;
        $this->foto_nota = null;
        $this->existingFotoNota = $p->foto_nota;

        $this->isEditing = true;
        $this->showModal = true;
    }

    /**
     * Close modal.
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->purchaseId = null;
    }

    /**
     * Save purchase.
     */
    public function save(): void
    {
        $this->validate();

        $notaPath = $this->existingFotoNota;
        if ($this->foto_nota) {
            if ($this->existingFotoNota && Storage::disk('public')->exists($this->existingFotoNota)) {
                Storage::disk('public')->delete($this->existingFotoNota);
            }
            $notaPath = $this->foto_nota->store('purchases', 'public');
        }

        $total = round(((float) $this->jumlah) * ((float) $this->harga_satuan), 2);

        Purchase::query()->updateOrCreate(
            ['id' => $this->purchaseId],
            [
                'open_po_id' => $this->open_po_id,
                'tanggal_pembelian' => $this->tanggal_pembelian,
                'nama_toko_supplier' => trim($this->nama_toko_supplier),
                'nama_barang' => trim($this->nama_barang),
                'jumlah' => (float) $this->jumlah,
                'satuan' => $this->satuan,
                'harga_satuan' => (float) $this->harga_satuan,
                'total_pembelian' => $total,
                'metode_pembayaran' => $this->metode_pembayaran,
                'keterangan' => $this->keterangan,
                'foto_nota' => $notaPath,
            ]
        );

        session()->flash('success', $this->isEditing ? 'Pembelian bahan berhasil diperbarui!' : 'Pembelian bahan berhasil dicatat!');

        $this->closeModal();
    }

    /**
     * Delete purchase record.
     */
    public function delete(int $id): void
    {
        $purchase = Purchase::query()->findOrFail($id);

        if ($purchase->foto_nota && Storage::disk('public')->exists($purchase->foto_nota)) {
            Storage::disk('public')->delete($purchase->foto_nota);
        }

        $purchase->delete();
        session()->flash('success', 'Catatan pembelian berhasil dihapus.');
    }

    /**
     * View receipt in modal lightbox.
     */
    public function viewReceipt(string $path, string $title): void
    {
        $this->receiptUrl = asset('storage/'.$path);
        $this->receiptTitle = $title;
        $this->showReceiptModal = true;
    }

    /**
     * Close receipt modal.
     */
    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->receiptUrl = null;
        $this->receiptTitle = null;
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        $query = Purchase::query()->with('openPo')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama_barang', 'like', '%'.$this->search.'%')
                        ->orWhere('nama_toko_supplier', 'like', '%'.$this->search.'%')
                        ->orWhere('keterangan', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->selectedOpenPoId, function ($q) {
                $q->where('open_po_id', $this->selectedOpenPoId);
            })
            ->when($this->startDate, function ($q) {
                $q->whereDate('tanggal_pembelian', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($q) {
                $q->whereDate('tanggal_pembelian', '<=', $this->endDate);
            });

        $totalFiltered = (float) $query->clone()->sum('total_pembelian');
        $purchases = $query->latest('tanggal_pembelian')->latest('id')->paginate(15);
        $openPos = OpenPo::query()->latest('tanggal_buka')->get();

        return view('livewire.admin.purchase-manager', [
            'purchases' => $purchases,
            'openPos' => $openPos,
            'totalFiltered' => $totalFiltered,
        ]);
    }
}
