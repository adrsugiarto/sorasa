<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\OpenPo;
use App\Models\OtherExpense;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Recipe;
use App\Models\Sale;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeedAndRelationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the database seeds properly and relations are correctly established.
     */
    public function test_database_seeds_and_establishes_all_relationships(): void
    {
        $this->seed(DatabaseSeeder::class);

        // 1. Verify Users
        $this->assertDatabaseHas('users', ['email' => 'owner@sorasa.test', 'role' => 'owner']);
        $this->assertDatabaseHas('users', ['email' => 'admin@sorasa.test', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['email' => 'felicia@customer.test', 'role' => 'customer']);

        // 2. Verify Open PO batches
        $this->assertEquals(4, OpenPo::count());
        $activePo = OpenPo::where('status', 'open')->first();
        $this->assertNotNull($activePo);
        $this->assertNotEmpty($activePo->sales);

        // 3. Verify Products and Recipes
        $this->assertEquals(6, Product::count());
        $productWithRecipe = Product::where('kode_produk', 'PRD-001')->first();
        $this->assertInstanceOf(Recipe::class, $productWithRecipe->recipe);
        $this->assertGreaterThan(0, $productWithRecipe->recipe->items()->count());

        // 4. Verify Customers & Sales Relations
        $this->assertGreaterThanOrEqual(8, Customer::count());
        $customer = Customer::first();
        $this->assertNotEmpty($customer->sales);
        $this->assertStringStartsWith('https://wa.me/', $customer->wa_link);

        // 5. Verify Sales, Delivery, and Payments
        $sale = Sale::with(['items', 'delivery', 'payments', 'customer', 'openPo'])->first();
        $this->assertNotNull($sale);
        $this->assertNotEmpty($sale->items);
        $this->assertNotNull($sale->delivery);
        $this->assertNotNull($sale->customer);
        $this->assertGreaterThan(0, $sale->subtotal);

        // 6. Verify Financial Figures match Project Brief
        $totalOmzet = (float) Sale::sum('total_tagihan');
        $totalPembelian = (float) Purchase::sum('total_pembelian');
        $totalBiayaLain = (float) OtherExpense::sum('nominal');
        $surplusUsaha = $totalOmzet - $totalPembelian - $totalBiayaLain;
        $totalTerbayar = (float) Payment::where('status_verifikasi', 'verified')->sum('nominal_bayar');
        $totalPiutang = $totalOmzet - $totalTerbayar;

        // Omzet ~ Rp15.000.000 (toleransi 14.5jt - 16jt)
        $this->assertGreaterThanOrEqual(14500000, $totalOmzet);
        $this->assertLessThanOrEqual(16000000, $totalOmzet);

        // Pembelian ~ Rp7.500.000 (toleransi 7jt - 8jt)
        $this->assertGreaterThanOrEqual(7000000, $totalPembelian);
        $this->assertLessThanOrEqual(8000000, $totalPembelian);

        // Biaya lain ~ Rp1.500.000 (toleransi 1.3jt - 1.8jt)
        $this->assertGreaterThanOrEqual(1300000, $totalBiayaLain);
        $this->assertLessThanOrEqual(1800000, $totalBiayaLain);

        // Surplus usaha positif
        $this->assertGreaterThan(0, $surplusUsaha);

        // Ada piutang (DP atau Belum Bayar) yang belum terbayar
        $this->assertGreaterThan(500000, $totalPiutang);

        // Status penjualan berstatus DP dan Belum Bayar ada dalam database
        $this->assertGreaterThan(0, Sale::where('status_pembayaran', 'dp')->count());
        $this->assertGreaterThan(0, Sale::where('status_pembayaran', 'belum_bayar')->count());
        $this->assertGreaterThan(0, Sale::where('status_pembayaran', 'lunas')->count());
    }
}
