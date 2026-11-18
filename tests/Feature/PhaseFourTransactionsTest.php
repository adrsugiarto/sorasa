<?php

namespace Tests\Feature;

use App\Livewire\Admin\DeliveryManager;
use App\Livewire\Admin\OtherExpenseManager;
use App\Livewire\Admin\PaymentManager;
use App\Livewire\Admin\PurchaseManager;
use App\Livewire\Admin\SaleList;
use App\Livewire\Admin\SalePos;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\OpenPo;
use App\Models\OtherExpense;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PhaseFourTransactionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected OpenPo $openPo;

    protected Customer $customer;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'admin@sorasa.test',
            'role' => 'admin',
        ]);

        $this->openPo = OpenPo::create([
            'nama_po' => 'PO Batch Testing',
            'kode_po' => 'PO-TEST-01',
            'tanggal_buka' => Carbon::now()->subDays(2),
            'tanggal_tutup' => Carbon::now()->addDays(2),
            'tanggal_pengiriman' => Carbon::now()->addDays(3),
            'status' => 'open',
            'is_published' => true,
        ]);

        $this->customer = Customer::create([
            'nama' => 'Ibu Ratna',
            'no_whatsapp' => '081234567890',
            'alamat' => 'Jl. Kenanga No. 12, Kebayoran Baru, Jakarta Selatan',
            'kategori_pelanggan' => 'VIP',
        ]);

        $this->product = Product::create([
            'kode_produk' => 'PRD-TEST',
            'nama_produk' => 'Ayam Betutu Khas Bali',
            'kategori' => 'Makanan Utama',
            'harga_jual' => 85000,
            'is_active' => true,
            'is_published' => true,
        ]);
    }

    public function test_purchase_manager_can_create_purchase_with_receipt(): void
    {
        Storage::fake('public');
        $photo = UploadedFile::fake()->image('nota.jpg');

        Livewire::actingAs($this->user)
            ->test(PurchaseManager::class)
            ->call('openCreateModal')
            ->set('open_po_id', $this->openPo->id)
            ->set('tanggal_pembelian', Carbon::today()->format('Y-m-d'))
            ->set('nama_toko_supplier', 'Pasar Tradisional Kebayoran')
            ->set('nama_barang', 'Ayam Kampung Utuh')
            ->set('jumlah', 10)
            ->set('satuan', 'ekor')
            ->set('harga_satuan', 45000)
            ->set('metode_pembayaran', 'Cash')
            ->set('foto_nota', $photo)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('purchases', [
            'nama_barang' => 'Ayam Kampung Utuh',
            'jumlah' => 10,
            'harga_satuan' => 45000,
            'total_pembelian' => 450000,
            'metode_pembayaran' => 'Cash',
        ]);

        $purchase = Purchase::where('nama_barang', 'Ayam Kampung Utuh')->first();
        $this->assertNotNull($purchase->foto_nota);
        Storage::disk('public')->assertExists($purchase->foto_nota);
    }

    public function test_sale_pos_can_add_item_and_checkout_with_delivery_and_dp(): void
    {
        Livewire::actingAs($this->user)
            ->test(SalePos::class)
            ->set('open_po_id', $this->openPo->id)
            ->set('customer_id', $this->customer->id)
            ->call('addToCart', $this->product->id)
            ->call('updateQty', $this->product->id, 2) // 2 x 85.000 = 170.000
            ->set('diskon', 10000) // Subtotal 170k - 10k = 160k
            ->set('ongkos_kirim', 15000) // Total tagihan = 175.000
            ->set('nama_penerima', 'Pak Joko (Suami Ibu Ratna)')
            ->set('no_telepon_penerima', '081298765432')
            ->set('alamat_lengkap', 'Jl. Kenanga No. 12, Jakarta')
            ->set('tanggal_pengiriman', Carbon::tomorrow()->format('Y-m-d'))
            ->set('pembayaran_awal', 'dp')
            ->set('nominal_bayar', 100000) // DP Rp 100.000
            ->set('metode_pembayaran', 'Transfer BCA')
            ->call('checkout')
            ->assertHasNoErrors()
            ->assertSet('showSuccessModal', true);

        $sale = Sale::latest('id')->first();
        $this->assertNotNull($sale);
        $this->assertEquals(170000, (float) $sale->subtotal);
        $this->assertEquals(10000, (float) $sale->diskon);
        $this->assertEquals(15000, (float) $sale->ongkos_kirim);
        $this->assertEquals(175000, (float) $sale->total_tagihan);
        $this->assertEquals('dp', $sale->status_pembayaran);

        // Check delivery record
        $this->assertDatabaseHas('deliveries', [
            'sale_id' => $sale->id,
            'nama_penerima' => 'Pak Joko (Suami Ibu Ratna)',
        ]);

        // Check DP payment record
        $this->assertDatabaseHas('payments', [
            'sale_id' => $sale->id,
            'nominal_bayar' => 100000,
            'jenis_pembayaran' => 'dp',
        ]);
    }

    public function test_sale_list_can_update_status(): void
    {
        $sale = Sale::create([
            'nomor_order' => 'INV-20261118-TEST',
            'open_po_id' => $this->openPo->id,
            'customer_id' => $this->customer->id,
            'tanggal_pemesanan' => Carbon::today(),
            'subtotal' => 85000,
            'diskon' => 0,
            'ongkos_kirim' => 0,
            'total_tagihan' => 85000,
            'status_pembayaran' => 'belum_bayar',
            'status_order' => 'menunggu_konfirmasi',
        ]);

        Livewire::actingAs($this->user)
            ->test(SaleList::class)
            ->call('updateOrderStatus', $sale->id, 'diproses');

        $this->assertEquals('diproses', $sale->fresh()->status_order);
    }

    public function test_delivery_manager_can_update_status_and_generate_courier_format(): void
    {
        $sale = Sale::create([
            'nomor_order' => 'INV-20261118-DELIV',
            'open_po_id' => $this->openPo->id,
            'customer_id' => $this->customer->id,
            'tanggal_pemesanan' => Carbon::today(),
            'subtotal' => 85000,
            'diskon' => 0,
            'ongkos_kirim' => 0,
            'total_tagihan' => 85000,
            'status_pembayaran' => 'lunas',
            'status_order' => 'siap_kirim',
        ]);

        $delivery = Delivery::create([
            'sale_id' => $sale->id,
            'tanggal_pengiriman' => Carbon::tomorrow(),
            'jam_pengiriman' => '10:00 - 12:00',
            'nama_penerima' => 'Ibu Ratna',
            'no_telepon_penerima' => '081234567890',
            'alamat_lengkap' => 'Jl. Kenanga No. 12, Jakarta',
            'patokan_lokasi' => 'Pagar putih sebelah minimarket',
            'status_pengantaran' => 'menunggu',
        ]);

        Livewire::actingAs($this->user)
            ->test(DeliveryManager::class)
            ->call('updateStatus', $delivery->id, 'dalam_pengiriman')
            ->assertHasNoErrors();

        $this->assertEquals('dalam_pengiriman', $delivery->fresh()->status_pengantaran);

        $format = $delivery->format_kurir;
        $this->assertStringContainsString('PENGANTARAN SORASA', $format);
        $this->assertStringContainsString('Ibu Ratna', $format);
        $this->assertStringContainsString('Pagar putih sebelah minimarket', $format);
    }

    public function test_payment_manager_tracks_piutang_and_settles_balance(): void
    {
        $sale = Sale::create([
            'nomor_order' => 'INV-20261118-PIUTANG',
            'open_po_id' => $this->openPo->id,
            'customer_id' => $this->customer->id,
            'tanggal_pemesanan' => Carbon::today(),
            'subtotal' => 100000,
            'diskon' => 0,
            'ongkos_kirim' => 0,
            'total_tagihan' => 100000,
            'status_pembayaran' => 'belum_bayar',
            'status_order' => 'diproses',
        ]);

        // 1. Partial payment (DP)
        Livewire::actingAs($this->user)
            ->test(PaymentManager::class)
            ->call('openPaymentModal', $sale->id)
            ->set('nominal_bayar', 40000)
            ->set('jenis_pembayaran', 'dp')
            ->set('metode_pembayaran', 'Transfer BCA')
            ->call('savePayment')
            ->assertHasNoErrors();

        $sale->refresh();
        $this->assertEquals('dp', $sale->status_pembayaran);
        $this->assertEquals(60000, (float) $sale->remaining_balance);

        // 2. Full settlement (Pelunasan)
        Livewire::actingAs($this->user)
            ->test(PaymentManager::class)
            ->call('openPaymentModal', $sale->id)
            ->call('fillFullPayment')
            ->set('jenis_pembayaran', 'pelunasan')
            ->call('savePayment')
            ->assertHasNoErrors();

        $sale->refresh();
        $this->assertEquals('lunas', $sale->status_pembayaran);
        $this->assertEquals(0, (float) $sale->remaining_balance);
    }

    public function test_other_expense_manager_crud(): void
    {
        Livewire::actingAs($this->user)
            ->test(OtherExpenseManager::class)
            ->call('openCreateModal')
            ->set('open_po_id', $this->openPo->id)
            ->set('tanggal_pengeluaran', Carbon::today()->format('Y-m-d'))
            ->set('kategori_biaya', 'Kemasan/Packaging')
            ->set('nama_pengeluaran', 'Dus Box Hampers 100 pcs')
            ->set('nominal', 250000)
            ->set('metode_pembayaran', 'Transfer')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('other_expenses', [
            'nama_pengeluaran' => 'Dus Box Hampers 100 pcs',
            'nominal' => 250000,
        ]);

        $expense = OtherExpense::where('nama_pengeluaran', 'Dus Box Hampers 100 pcs')->first();

        // Test Edit
        Livewire::actingAs($this->user)
            ->test(OtherExpenseManager::class)
            ->call('openEditModal', $expense->id)
            ->set('nominal', 275000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals(275000, (float) $expense->fresh()->nominal);

        // Test Delete
        Livewire::actingAs($this->user)
            ->test(OtherExpenseManager::class)
            ->call('delete', $expense->id);

        $this->assertDatabaseMissing('other_expenses', [
            'id' => $expense->id,
        ]);
    }

    public function test_sale_pos_searchable_customer_selection_syncs_delivery_info(): void
    {
        Livewire::actingAs($this->user)
            ->test(SalePos::class)
            ->call('selectCustomer', $this->customer->id)
            ->assertSet('customer_id', $this->customer->id)
            ->assertSet('nama_penerima', $this->customer->nama)
            ->assertSet('no_telepon_penerima', $this->customer->no_whatsapp)
            ->assertSet('alamat_lengkap', $this->customer->alamat)
            ->call('selectCustomer', null)
            ->assertSet('customer_id', null)
            ->assertSet('nama_penerima', '');
    }
}
