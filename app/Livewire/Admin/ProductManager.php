<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Master Produk — SORASA')]
class ProductManager extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public string $selectedCategory = '';

    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $productId = null;

    public string $kode_produk = '';

    public string $nama_produk = '';

    public string $kategori = 'Makanan Utama';

    public string|float|int $harga_jual = '';

    public ?string $deskripsi = '';

    /**
     * @var TemporaryUploadedFile|null
     */
    public $foto = null;

    public ?string $existingFoto = null;

    public bool $is_active = true;

    public bool $is_published = true;

    /**
     * Available categories.
     *
     * @var array<int, string>
     */
    public array $categories = [
        'Makanan Utama',
        'Snack & Dessert',
        'Paket Hampers',
        'Minuman',
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
     * Reset pagination when category changes.
     */
    public function updatingSelectedCategory(): void
    {
        $this->resetPage();
    }

    /**
     * Validation rules for product form.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'kode_produk' => ['required', 'string', 'max:50', 'unique:products,kode_produk,'.$this->productId],
            'nama_produk' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
            'is_published' => ['boolean'],
        ];
    }

    /**
     * Open modal to create a new product.
     */
    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEditing = false;

        // Auto generate next product code
        $nextId = (Product::max('id') ?? 0) + 1;
        $this->kode_produk = 'PRD-'.str_pad((string) $nextId, 3, '0', STR_PAD_LEFT);

        $this->showModal = true;
    }

    /**
     * Open modal to edit an existing product.
     */
    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $this->resetForm();

        $product = Product::query()->findOrFail($id);
        $this->productId = $product->id;
        $this->kode_produk = $product->kode_produk;
        $this->nama_produk = $product->nama_produk;
        $this->kategori = $product->kategori;
        $this->harga_jual = (float) $product->harga_jual;
        $this->deskripsi = $product->deskripsi;
        $this->existingFoto = $product->foto_produk;
        $this->is_active = (bool) $product->is_active;
        $this->is_published = (bool) $product->is_published;

        $this->isEditing = true;
        $this->showModal = true;
    }

    /**
     * Reset form fields.
     */
    public function resetForm(): void
    {
        $this->productId = null;
        $this->kode_produk = '';
        $this->nama_produk = '';
        $this->kategori = 'Makanan Utama';
        $this->harga_jual = '';
        $this->deskripsi = '';
        $this->foto = null;
        $this->existingFoto = null;
        $this->is_active = true;
        $this->is_published = true;
    }

    /**
     * Close modal.
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Save product (create or update).
     */
    public function save(): void
    {
        $validated = $this->validate();

        $fotoPath = $this->existingFoto;
        if ($this->foto) {
            if ($this->existingFoto && Storage::disk('public')->exists($this->existingFoto)) {
                Storage::disk('public')->delete($this->existingFoto);
            }
            $fotoPath = $this->foto->store('products', 'public');
        }

        Product::query()->updateOrCreate(
            ['id' => $this->productId],
            [
                'kode_produk' => strtoupper(trim($this->kode_produk)),
                'nama_produk' => trim($this->nama_produk),
                'kategori' => $this->kategori,
                'harga_jual' => (float) $this->harga_jual,
                'deskripsi' => $this->deskripsi,
                'foto_produk' => $fotoPath,
                'is_active' => $this->is_active,
                'is_published' => $this->is_published,
            ]
        );

        session()->flash('success', $this->isEditing ? 'Produk berhasil diperbarui!' : 'Produk baru berhasil ditambahkan!');

        $this->closeModal();
    }

    /**
     * Toggle product active state.
     */
    public function toggleActive(int $id): void
    {
        $product = Product::query()->findOrFail($id);
        $product->is_active = ! $product->is_active;
        $product->save();

        session()->flash('success', "Status produk {$product->nama_produk} berhasil diubah.");
    }

    /**
     * Delete product.
     */
    public function delete(int $id): void
    {
        $product = Product::query()->findOrFail($id);

        if ($product->saleItems()->exists()) {
            session()->flash('error', 'Produk ini tidak dapat dihapus karena sudah memiliki riwayat transaksi penjualan.');

            return;
        }

        if ($product->foto_produk && Storage::disk('public')->exists($product->foto_produk)) {
            Storage::disk('public')->delete($product->foto_produk);
        }

        $product->delete();

        session()->flash('success', 'Produk berhasil dihapus.');
    }

    /**
     * Render the component view.
     */
    public function render(): View
    {
        $query = Product::query()
            ->with('recipe')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama_produk', 'like', '%'.$this->search.'%')
                        ->orWhere('kode_produk', 'like', '%'.$this->search.'%')
                        ->orWhere('kategori', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->selectedCategory, function ($q) {
                $q->where('kategori', $this->selectedCategory);
            });

        $products = $query->orderBy('kategori')->orderBy('nama_produk')->paginate(12);

        return view('livewire.admin.product-manager', [
            'products' => $products,
        ]);
    }
}
