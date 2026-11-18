<?php

namespace App\Livewire\Admin;

use App\Models\OpenPo;
use App\Models\OtherExpense;
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
#[Title('Pengeluaran Operasional Lain — SORASA')]
class OtherExpenseManager extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public string $selectedOpenPoId = '';

    public string $selectedCategory = '';

    public ?string $startDate = null;

    public ?string $endDate = null;

    // Modal Form properties
    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $expenseId = null;

    public ?int $open_po_id = null;

    public string $tanggal_pengeluaran = '';

    public string $kategori_biaya = 'Kemasan/Packaging';

    public string $nama_pengeluaran = '';

    public float|int|string $nominal = 0;

    public string $metode_pembayaran = 'Cash';

    public ?string $keterangan = '';

    /**
     * @var TemporaryUploadedFile|null
     */
    public $bukti_nota = null;

    public ?string $existingBuktiNota = null;

    // Receipt Modal preview
    public bool $showReceiptModal = false;

    public ?string $receiptUrl = null;

    public ?string $receiptTitle = null;

    /**
     * Expense categories.
     *
     * @var array<int, string>
     */
    public array $categories = [
        'Kemasan/Packaging',
        'Gas',
        'Listrik/Air',
        'Tenaga Kerja',
        'Transportasi',
        'Lainnya',
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

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSelectedOpenPoId(): void
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory(): void
    {
        $this->resetPage();
    }

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    /**
     * Open create expense modal.
     */
    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEditing = false;
        $this->tanggal_pengeluaran = Carbon::today()->format('Y-m-d');
        $this->kategori_biaya = 'Kemasan/Packaging';
        $this->metode_pembayaran = 'Cash';
        $this->nominal = 0;

        // Auto select current open PO if available
        $activePo = OpenPo::query()->where('status', 'open')->latest('id')->first();
        if ($activePo) {
            $this->open_po_id = $activePo->id;
        }

        $this->showModal = true;
    }

    /**
     * Open edit expense modal.
     */
    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $expense = OtherExpense::query()->findOrFail($id);

        $this->expenseId = $expense->id;
        $this->isEditing = true;
        $this->open_po_id = $expense->open_po_id;
        $this->tanggal_pengeluaran = $expense->tanggal_pengeluaran->format('Y-m-d');
        $this->kategori_biaya = $expense->kategori_biaya;
        $this->nama_pengeluaran = $expense->nama_pengeluaran;
        $this->nominal = (float) $expense->nominal;
        $this->metode_pembayaran = $expense->metode_pembayaran;
        $this->keterangan = $expense->keterangan;
        $this->existingBuktiNota = $expense->bukti_nota;
        $this->bukti_nota = null;

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Reset form fields.
     */
    public function resetForm(): void
    {
        $this->expenseId = null;
        $this->open_po_id = null;
        $this->tanggal_pengeluaran = Carbon::today()->format('Y-m-d');
        $this->kategori_biaya = 'Kemasan/Packaging';
        $this->nama_pengeluaran = '';
        $this->nominal = 0;
        $this->metode_pembayaran = 'Cash';
        $this->keterangan = '';
        $this->bukti_nota = null;
        $this->existingBuktiNota = null;
    }

    /**
     * Save expense (create or update).
     */
    public function save(): void
    {
        $this->validate([
            'open_po_id' => ['nullable', 'exists:open_pos,id'],
            'tanggal_pengeluaran' => ['required', 'date'],
            'kategori_biaya' => ['required', 'string', 'max:50'],
            'nama_pengeluaran' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'metode_pembayaran' => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'bukti_nota' => ['nullable', 'image', 'max:3072'],
        ]);

        $notaPath = $this->existingBuktiNota;
        if ($this->bukti_nota) {
            if ($this->existingBuktiNota && Storage::disk('public')->exists($this->existingBuktiNota)) {
                Storage::disk('public')->delete($this->existingBuktiNota);
            }
            $notaPath = $this->bukti_nota->store('expenses', 'public');
        }

        $payload = [
            'open_po_id' => $this->open_po_id ?: null,
            'tanggal_pengeluaran' => $this->tanggal_pengeluaran,
            'kategori_biaya' => $this->kategori_biaya,
            'nama_pengeluaran' => $this->nama_pengeluaran,
            'nominal' => (float) $this->nominal,
            'metode_pembayaran' => $this->metode_pembayaran,
            'keterangan' => $this->keterangan,
            'bukti_nota' => $notaPath,
        ];

        if ($this->isEditing && $this->expenseId) {
            $expense = OtherExpense::query()->findOrFail($this->expenseId);
            $expense->update($payload);
            session()->flash('success', 'Data pengeluaran berhasil diperbarui.');
        } else {
            OtherExpense::create($payload);
            session()->flash('success', 'Pengeluaran operasional baru berhasil dicatat.');
        }

        $this->closeModal();
    }

    /**
     * Delete expense record.
     */
    public function delete(int $id): void
    {
        $expense = OtherExpense::query()->findOrFail($id);

        if ($expense->bukti_nota && Storage::disk('public')->exists($expense->bukti_nota)) {
            Storage::disk('public')->delete($expense->bukti_nota);
        }

        $expense->delete();
        session()->flash('success', 'Catatan pengeluaran berhasil dihapus.');
    }

    /**
     * Preview receipt in lightbox modal.
     */
    public function viewReceipt(string $path, string $title): void
    {
        $this->receiptUrl = asset('storage/'.$path);
        $this->receiptTitle = $title;
        $this->showReceiptModal = true;
    }

    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->receiptUrl = null;
        $this->receiptTitle = null;
    }

    /**
     * Render component.
     */
    public function render(): View
    {
        $openPos = OpenPo::query()->latest('id')->get(['id', 'nama_po', 'kode_po', 'status']);

        $query = OtherExpense::query()
            ->with('openPo')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama_pengeluaran', 'like', '%'.$this->search.'%')
                        ->orWhere('keterangan', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->selectedOpenPoId, function ($q) {
                $q->where('open_po_id', $this->selectedOpenPoId);
            })
            ->when($this->selectedCategory, function ($q) {
                $q->where('kategori_biaya', $this->selectedCategory);
            })
            ->when($this->startDate, function ($q) {
                $q->whereDate('tanggal_pengeluaran', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($q) {
                $q->whereDate('tanggal_pengeluaran', '<=', $this->endDate);
            });

        $totalFilteredExpenses = (float) $query->clone()->sum('nominal');

        $expenses = $query->latest('tanggal_pengeluaran')->latest('id')->paginate(15);

        return view('livewire.admin.other-expense-manager', [
            'expenses' => $expenses,
            'openPos' => $openPos,
            'totalFilteredExpenses' => $totalFilteredExpenses,
        ]);
    }
}
