<?php

namespace App\Livewire\Admin;

use App\Models\OpenPo;
use App\Models\SaleItem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Batch Open PO — SORASA')]
class OpenPoManager extends Component
{
    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $batchId = null;

    // Form fields
    public string $nama_po = '';

    public string $kode_po = '';

    public string $tanggal_buka = '';

    public string $tanggal_tutup = '';

    public string $tanggal_pengiriman = '';

    public string $status = 'open';

    public bool $is_published = true;

    public ?string $catatan = '';

    // Detail Modal for Kitchen Recap
    public bool $showDetailModal = false;

    public ?OpenPo $selectedBatch = null;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $kitchenRecap = [];

    public float $batchOmzet = 0;

    public float $batchPiutang = 0;

    public int $batchTotalOrders = 0;

    /**
     * Validation rules.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'nama_po' => ['required', 'string', 'max:255'],
            'kode_po' => ['required', 'string', 'max:50', 'unique:open_pos,kode_po,'.$this->batchId],
            'tanggal_buka' => ['required', 'date'],
            'tanggal_tutup' => ['required', 'date', 'after_or_equal:tanggal_buka'],
            'tanggal_pengiriman' => ['required', 'date', 'after_or_equal:tanggal_tutup'],
            'status' => ['required', 'in:draft,open,closed,completed'],
            'is_published' => ['boolean'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Open create batch modal with default values.
     */
    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->batchId = null;
        $this->isEditing = false;

        $nextBatchNum = OpenPo::count() + 1;
        $now = Carbon::now();

        $this->nama_po = "PO Batch #{$nextBatchNum} - Special";
        $this->kode_po = 'PO-'.$now->format('Ym').'-'.str_pad((string) $nextBatchNum, 2, '0', STR_PAD_LEFT);
        $this->tanggal_buka = $now->format('Y-m-d');
        $this->tanggal_tutup = $now->copy()->addDays(5)->format('Y-m-d');
        $this->tanggal_pengiriman = $now->copy()->addDays(7)->format('Y-m-d');
        $this->status = 'open';
        $this->is_published = true;
        $this->catatan = '';

        $this->showModal = true;
    }

    /**
     * Open edit batch modal.
     */
    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $batch = OpenPo::query()->findOrFail($id);

        $this->batchId = $batch->id;
        $this->nama_po = $batch->nama_po;
        $this->kode_po = $batch->kode_po;
        $this->tanggal_buka = $batch->tanggal_buka->format('Y-m-d');
        $this->tanggal_tutup = $batch->tanggal_tutup->format('Y-m-d');
        $this->tanggal_pengiriman = $batch->tanggal_pengiriman->format('Y-m-d');
        $this->status = $batch->status;
        $this->is_published = (bool) $batch->is_published;
        $this->catatan = $batch->catatan;

        $this->isEditing = true;
        $this->showModal = true;
    }

    /**
     * Close modal.
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->batchId = null;
    }

    /**
     * Save batch (create or update).
     */
    public function save(): void
    {
        $this->validate();

        OpenPo::query()->updateOrCreate(
            ['id' => $this->batchId],
            [
                'nama_po' => trim($this->nama_po),
                'kode_po' => strtoupper(trim($this->kode_po)),
                'tanggal_buka' => $this->tanggal_buka,
                'tanggal_tutup' => $this->tanggal_tutup,
                'tanggal_pengiriman' => $this->tanggal_pengiriman,
                'status' => $this->status,
                'is_published' => $this->is_published,
                'catatan' => $this->catatan,
            ]
        );

        session()->flash('success', $this->isEditing ? 'Batch Open PO berhasil diperbarui!' : 'Batch Open PO baru berhasil dibuat!');

        $this->closeModal();
    }

    /**
     * Update status directly from table.
     */
    public function updateStatus(int $id, string $newStatus): void
    {
        if (! in_array($newStatus, ['draft', 'open', 'closed', 'completed'], true)) {
            return;
        }

        $batch = OpenPo::query()->findOrFail($id);
        $batch->status = $newStatus;
        $batch->save();

        session()->flash('success', "Status batch {$batch->nama_po} berhasil diubah menjadi ".strtoupper($newStatus).'.');
    }

    /**
     * View kitchen production recap for a specific batch.
     */
    public function showRecap(int $id): void
    {
        $batch = OpenPo::query()->with(['sales.items.product', 'sales.payments'])->findOrFail($id);
        $this->selectedBatch = $batch;

        $this->batchTotalOrders = $batch->sales->count();
        $this->batchOmzet = (float) $batch->sales->sum('total_tagihan');

        $totalPaid = (float) $batch->sales->flatMap->payments->where('status_verifikasi', 'verified')->sum('nominal_bayar');
        $this->batchPiutang = max(0, $this->batchOmzet - $totalPaid);

        // Calculate kitchen cooking recap: total quantity per product
        $recap = SaleItem::query()
            ->select(
                'products.nama_produk',
                'products.kategori',
                DB::raw('SUM(sale_items.jumlah_paket) as total_qty'),
                DB::raw('SUM(sale_items.subtotal) as total_nominal')
            )
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->where('sales.open_po_id', $batch->id)
            ->where('sales.status_order', '!=', 'dibatalkan')
            ->groupBy('products.id', 'products.nama_produk', 'products.kategori')
            ->orderByDesc('total_qty')
            ->get();

        $this->kitchenRecap = $recap->toArray();
        $this->showDetailModal = true;
    }

    /**
     * Close detail modal.
     */
    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedBatch = null;
        $this->kitchenRecap = [];
    }

    /**
     * Delete batch (only if no sales recorded).
     */
    public function delete(int $id): void
    {
        $batch = OpenPo::query()->findOrFail($id);

        if ($batch->sales()->exists()) {
            session()->flash('error', 'Batch ini tidak dapat dihapus karena sudah memiliki transaksi pesanan.');

            return;
        }

        $batch->delete();
        session()->flash('success', 'Batch Open PO berhasil dihapus.');
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        $batches = OpenPo::query()
            ->withCount('sales')
            ->withSum('sales as total_omzet', 'total_tagihan')
            ->latest('tanggal_buka')
            ->get();

        return view('livewire.admin.open-po-manager', [
            'batches' => $batches,
        ]);
    }
}
