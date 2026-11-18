<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Database Customer — SORASA')]
class CustomerManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $selectedCategory = '';

    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $customerId = null;

    // Form fields
    public string $nama = '';

    public string $no_whatsapp = '';

    public string $alamat = '';

    public string $kategori_pelanggan = 'Reguler';

    public ?string $catatan = '';

    // Customer Detail Slide-over / Modal
    public bool $showDetailModal = false;

    public ?Customer $selectedCustomer = null;

    /**
     * Available customer categories.
     *
     * @var array<int, string>
     */
    public array $customerCategories = [
        'Reguler',
        'Reseller',
        'VIP',
        'Keluarga/Teman',
    ];

    /**
     * Reset pagination on search.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination on category filter.
     */
    public function updatingSelectedCategory(): void
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
            'nama' => ['required', 'string', 'max:255'],
            'no_whatsapp' => ['required', 'string', 'max:25'],
            'alamat' => ['required', 'string', 'max:1000'],
            'kategori_pelanggan' => ['required', 'in:Reguler,Reseller,VIP,Keluarga/Teman'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Open create customer modal.
     */
    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->customerId = null;
        $this->isEditing = false;
        $this->nama = '';
        $this->no_whatsapp = '';
        $this->alamat = '';
        $this->kategori_pelanggan = 'Reguler';
        $this->catatan = '';

        $this->showModal = true;
    }

    /**
     * Open edit customer modal.
     */
    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $customer = Customer::query()->findOrFail($id);

        $this->customerId = $customer->id;
        $this->nama = $customer->nama;
        $this->no_whatsapp = $customer->no_whatsapp;
        $this->alamat = $customer->alamat;
        $this->kategori_pelanggan = $customer->kategori_pelanggan;
        $this->catatan = $customer->catatan;

        $this->isEditing = true;
        $this->showModal = true;
    }

    /**
     * Close modal.
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->customerId = null;
    }

    /**
     * Save customer.
     */
    public function save(): void
    {
        $this->validate();

        // Normalize phone number (trim spaces and dashes)
        $cleanedPhone = preg_replace('/[^0-9]/', '', $this->no_whatsapp);

        Customer::query()->updateOrCreate(
            ['id' => $this->customerId],
            [
                'nama' => trim($this->nama),
                'no_whatsapp' => $cleanedPhone ?: $this->no_whatsapp,
                'alamat' => trim($this->alamat),
                'kategori_pelanggan' => $this->kategori_pelanggan,
                'catatan' => $this->catatan,
            ]
        );

        session()->flash('success', $this->isEditing ? 'Data pelanggan berhasil diperbarui!' : 'Pelanggan baru berhasil ditambahkan!');

        $this->closeModal();
    }

    /**
     * Open customer detail history modal.
     */
    public function showCustomerDetail(int $id): void
    {
        $this->selectedCustomer = Customer::query()
            ->with(['sales.openPo', 'sales.items.product', 'sales.payments'])
            ->findOrFail($id);

        $this->showDetailModal = true;
    }

    /**
     * Close customer detail modal.
     */
    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedCustomer = null;
    }

    /**
     * Delete customer.
     */
    public function delete(int $id): void
    {
        $customer = Customer::query()->findOrFail($id);

        if ($customer->sales()->exists()) {
            session()->flash('error', 'Pelanggan ini tidak dapat dihapus karena sudah memiliki riwayat order.');

            return;
        }

        $customer->delete();
        session()->flash('success', 'Pelanggan berhasil dihapus.');
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        $customers = Customer::query()
            ->withCount('sales')
            ->withSum('sales as total_belanja', 'total_tagihan')
            ->withMax('sales as last_order_at', 'tanggal_pemesanan')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama', 'like', '%'.$this->search.'%')
                        ->orWhere('no_whatsapp', 'like', '%'.$this->search.'%')
                        ->orWhere('alamat', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->selectedCategory, function ($q) {
                $q->where('kategori_pelanggan', $this->selectedCategory);
            })
            ->orderByDesc('total_belanja')
            ->paginate(15);

        return view('livewire.admin.customer-manager', [
            'customers' => $customers,
        ]);
    }
}
