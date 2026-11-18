<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Resep & Estimasi HPP — SORASA')]
class RecipeManager extends Component
{
    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $recipeId = null;

    // Form fields
    public ?int $product_id = null;

    public string $nama_resep = '';

    public int $porsi_hasil = 1;

    public ?string $catatan_pembuatan = '';

    /**
     * Dynamic ingredients rows.
     *
     * @var array<int, array{id?: int|null, nama_bahan: string, qty: float|string, satuan: string, harga_per_satuan: float|string, harga_total: float|string}>
     */
    public array $items = [];

    /**
     * Satuan options.
     *
     * @var array<int, string>
     */
    public array $unitOptions = [
        'gram',
        'kg',
        'ml',
        'liter',
        'pcs',
        'pack',
        'sdm',
        'sdt',
        'lembar',
        'butir',
    ];

    /**
     * Validation rules.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'nama_resep' => ['required', 'string', 'max:255'],
            'porsi_hasil' => ['required', 'integer', 'min:1'],
            'catatan_pembuatan' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.nama_bahan' => ['required', 'string', 'max:255'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01'],
            'items.*.satuan' => ['required', 'string', 'max:50'],
            'items.*.harga_per_satuan' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Add an ingredient row to the repeater.
     */
    public function addIngredient(): void
    {
        $this->items[] = [
            'id' => null,
            'nama_bahan' => '',
            'qty' => 1,
            'satuan' => 'gram',
            'harga_per_satuan' => 0,
            'harga_total' => 0,
        ];
    }

    /**
     * Remove an ingredient row from the repeater.
     */
    public function removeIngredient(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    /**
     * Calculate row total when qty or unit price changes.
     */
    public function calculateRowTotal(int $index): void
    {
        if (isset($this->items[$index])) {
            $qty = (float) ($this->items[$index]['qty'] ?? 0);
            $price = (float) ($this->items[$index]['harga_per_satuan'] ?? 0);
            $this->items[$index]['harga_total'] = round($qty * $price, 2);
        }
    }

    /**
     * Get the estimated total material cost of the recipe.
     */
    public function getTotalCostProperty(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $qty = (float) ($item['qty'] ?? 0);
            $price = (float) ($item['harga_per_satuan'] ?? 0);
            $total += ($qty * $price);
        }

        return $total;
    }

    /**
     * Get estimated cost per portion.
     */
    public function getCostPerPortionProperty(): float
    {
        $porsi = max(1, (int) $this->porsi_hasil);

        return round($this->totalCost / $porsi, 2);
    }

    /**
     * Open create recipe modal.
     */
    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->recipeId = null;
        $this->isEditing = false;

        // Default to first product without recipe or first product
        $firstProduct = Product::whereDoesntHave('recipe')->first() ?? Product::first();

        $this->product_id = $firstProduct ? $firstProduct->id : null;
        $this->nama_resep = $firstProduct ? "Standar Resep {$firstProduct->nama_produk}" : 'Resep Baru';
        $this->porsi_hasil = 10;
        $this->catatan_pembuatan = '';

        $this->items = [
            [
                'id' => null,
                'nama_bahan' => '',
                'qty' => 1,
                'satuan' => 'kg',
                'harga_per_satuan' => 0,
                'harga_total' => 0,
            ],
        ];

        $this->showModal = true;
    }

    /**
     * Update recipe name suggestion when product selected.
     */
    public function updatedProductId(int $val): void
    {
        if (! $this->isEditing) {
            $product = Product::find($val);
            if ($product) {
                $this->nama_resep = "Standar Resep {$product->nama_produk}";
            }
        }
    }

    /**
     * Open edit recipe modal.
     */
    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $recipe = Recipe::query()->with('items')->findOrFail($id);

        $this->recipeId = $recipe->id;
        $this->product_id = $recipe->product_id;
        $this->nama_resep = $recipe->nama_resep;
        $this->porsi_hasil = $recipe->porsi_hasil;
        $this->catatan_pembuatan = $recipe->catatan_pembuatan;

        $this->items = [];
        foreach ($recipe->items as $it) {
            $this->items[] = [
                'id' => $it->id,
                'nama_bahan' => $it->nama_bahan,
                'qty' => (float) $it->qty,
                'satuan' => $it->satuan,
                'harga_per_satuan' => (float) $it->harga_per_satuan,
                'harga_total' => (float) $it->harga_total,
            ];
        }

        $this->isEditing = true;
        $this->showModal = true;
    }

    /**
     * Close modal.
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->recipeId = null;
        $this->items = [];
    }

    /**
     * Save recipe and its ingredient items.
     */
    public function save(): void
    {
        $this->validate();

        DB::transaction(function () {
            // Recalculate row totals
            $totalCost = 0;
            foreach ($this->items as &$item) {
                $rowTotal = round(((float) $item['qty']) * ((float) $item['harga_per_satuan']), 2);
                $item['harga_total'] = $rowTotal;
                $totalCost += $rowTotal;
            }

            $recipe = Recipe::query()->updateOrCreate(
                ['id' => $this->recipeId],
                [
                    'product_id' => $this->product_id,
                    'nama_resep' => trim($this->nama_resep),
                    'porsi_hasil' => (int) $this->porsi_hasil,
                    'total_biaya_bahan' => $totalCost,
                    'catatan_pembuatan' => $this->catatan_pembuatan,
                ]
            );

            // Re-sync recipe items
            $recipe->items()->delete();
            foreach ($this->items as $it) {
                $recipe->items()->create([
                    'nama_bahan' => trim($it['nama_bahan']),
                    'qty' => (float) $it['qty'],
                    'satuan' => $it['satuan'],
                    'harga_per_satuan' => (float) $it['harga_per_satuan'],
                    'harga_total' => (float) $it['harga_total'],
                ]);
            }
        });

        session()->flash('success', $this->isEditing ? 'Resep berhasil diperbarui!' : 'Resep baru berhasil disimpan!');

        $this->closeModal();
    }

    /**
     * Delete recipe.
     */
    public function delete(int $id): void
    {
        $recipe = Recipe::query()->findOrFail($id);
        $recipe->delete();

        session()->flash('success', 'Resep berhasil dihapus.');
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        $recipes = Recipe::query()
            ->with(['product', 'items'])
            ->latest()
            ->get();

        $products = Product::query()->orderBy('nama_produk')->get();

        return view('livewire.admin.recipe-manager', [
            'recipes' => $recipes,
            'products' => $products,
        ]);
    }
}
