<?php

namespace Tests\Feature;

use App\Livewire\Admin\CustomerManager;
use App\Livewire\Admin\OpenPoManager;
use App\Livewire\Admin\ProductManager;
use App\Livewire\Admin\RecipeManager;
use App\Models\Customer;
use App\Models\OpenPo;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PhaseThreeMasterDataTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->owner = User::where('email', 'owner@sorasa.test')->first();
    }

    /**
     * Test ProductManager CRUD operations.
     */
    public function test_product_manager_can_create_update_toggle_and_search_products(): void
    {
        $this->actingAs($this->owner);

        // 1. Render ProductManager page
        $test = Livewire::test(ProductManager::class)
            ->assertStatus(200)
            ->assertSee('Nasi Liwet Komplit');

        // 2. Search product
        $test->set('search', 'Brownies')
            ->assertSee('Fudgy Brownies')
            ->assertDontSee('Rendang Sapi');

        // 3. Create product
        Livewire::test(ProductManager::class)
            ->call('openCreateModal')
            ->set('kode_produk', 'PRD-TEST-99')
            ->set('nama_produk', 'Ayam Bakar Madu Spesial')
            ->set('kategori', 'Makanan Utama')
            ->set('harga_jual', 55000)
            ->set('deskripsi', 'Ayam bakar madu legit manis gurih.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('products', [
            'kode_produk' => 'PRD-TEST-99',
            'nama_produk' => 'Ayam Bakar Madu Spesial',
            'harga_jual' => 55000,
        ]);

        $newProduct = Product::where('kode_produk', 'PRD-TEST-99')->first();

        // 4. Toggle active status
        $initialActive = $newProduct->is_active;
        Livewire::test(ProductManager::class)
            ->call('toggleActive', $newProduct->id);
        $this->assertEquals(! $initialActive, $newProduct->fresh()->is_active);

        // 5. Delete product without sales
        Livewire::test(ProductManager::class)
            ->call('delete', $newProduct->id);
        $this->assertDatabaseMissing('products', ['id' => $newProduct->id]);
    }

    /**
     * Test OpenPoManager CRUD and kitchen production recap.
     */
    public function test_open_po_manager_can_manage_batches_and_show_kitchen_recap(): void
    {
        $this->actingAs($this->owner);

        // 1. Create a new Open PO batch
        Livewire::test(OpenPoManager::class)
            ->call('openCreateModal')
            ->set('nama_po', 'PO Batch #99 - Promo Test')
            ->set('kode_po', 'PO-202612-99')
            ->set('tanggal_buka', '2026-12-01')
            ->set('tanggal_tutup', '2026-12-05')
            ->set('tanggal_pengiriman', '2026-12-07')
            ->set('status', 'open')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('open_pos', [
            'kode_po' => 'PO-202612-99',
            'nama_po' => 'PO Batch #99 - Promo Test',
        ]);

        $batch = OpenPo::where('kode_po', 'PO-202612-99')->first();

        // 2. Update status
        Livewire::test(OpenPoManager::class)
            ->call('updateStatus', $batch->id, 'closed');
        $this->assertEquals('closed', $batch->fresh()->status);

        // 3. Test kitchen recap on active batch with sales (PO #14)
        $batch14 = OpenPo::where('kode_po', 'PO-202611-01')->first();
        Livewire::test(OpenPoManager::class)
            ->call('showRecap', $batch14->id)
            ->assertSet('showDetailModal', true)
            ->assertSee($batch14->nama_po)
            ->assertSee('Kuantitas Masak / Produksi per Menu');
    }

    /**
     * Test CustomerManager CRUD, WA link, and order history modal.
     */
    public function test_customer_manager_can_create_customer_and_view_order_history(): void
    {
        $this->actingAs($this->owner);

        // 1. Create a new customer
        Livewire::test(CustomerManager::class)
            ->call('openCreateModal')
            ->set('nama', 'Citra Kirana')
            ->set('no_whatsapp', '081299887766')
            ->set('alamat', 'Jl. Merdeka No. 100, Bandung')
            ->set('kategori_pelanggan', 'VIP')
            ->set('catatan', 'Langganan pesanan rendang.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customers', [
            'nama' => 'Citra Kirana',
            'no_whatsapp' => '081299887766',
            'kategori_pelanggan' => 'VIP',
        ]);

        // 2. View customer order history detail
        $existingCustomer = Customer::where('nama', 'Maya Kusuma')->first();
        Livewire::test(CustomerManager::class)
            ->call('showCustomerDetail', $existingCustomer->id)
            ->assertSet('showDetailModal', true)
            ->assertSee('Maya Kusuma')
            ->assertSee('Riwayat Transaksi Pesanan');
    }

    /**
     * Test RecipeManager with dynamic repeater and HPP calculations.
     */
    public function test_recipe_manager_can_create_recipe_with_repeater_and_calculate_hpp(): void
    {
        $this->actingAs($this->owner);

        $product = Product::where('kode_produk', 'PRD-005')->first(); // Brownies

        Livewire::test(RecipeManager::class)
            ->call('openCreateModal')
            ->set('product_id', $product->id)
            ->set('nama_resep', 'Standar Resep Brownies Fudgy')
            ->set('porsi_hasil', 5)
            ->set('items', [
                [
                    'nama_bahan' => 'Dark Compound Chocolate',
                    'qty' => 0.5,
                    'satuan' => 'kg',
                    'harga_per_satuan' => 80000,
                    'harga_total' => 40000,
                ],
                [
                    'nama_bahan' => 'Mentega Anchor',
                    'qty' => 0.25,
                    'satuan' => 'kg',
                    'harga_per_satuan' => 100000,
                    'harga_total' => 25000,
                ],
                [
                    'nama_bahan' => 'Alumunium Foil Box & Mika',
                    'qty' => 5,
                    'satuan' => 'pcs',
                    'harga_per_satuan' => 2000,
                    'harga_total' => 10000,
                ],
            ])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('recipes', [
            'product_id' => $product->id,
            'nama_resep' => 'Standar Resep Brownies Fudgy',
            'porsi_hasil' => 5,
            'total_biaya_bahan' => 75000, // 40k + 25k + 10k
        ]);

        $createdRecipe = Recipe::where('product_id', $product->id)->first();
        $this->assertCount(3, $createdRecipe->items);
    }
}
