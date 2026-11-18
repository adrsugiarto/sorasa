<?php

namespace Tests\Feature;

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Reports;
use App\Models\Customer;
use App\Models\OpenPo;
use App\Models\OtherExpense;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class PhaseFiveDashboardAndReportsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected OpenPo $po1;

    protected OpenPo $po2;

    protected Product $product1;

    protected Product $product2;

    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'admin@sorasa.test',
            'role' => 'admin',
        ]);

        $this->po1 = OpenPo::create([
            'nama_po' => 'PO Batch #1 Testing',
            'kode_po' => 'PO-TEST-01',
            'tanggal_buka' => Carbon::now()->subDays(10),
            'tanggal_tutup' => Carbon::now()->subDays(5),
            'tanggal_pengiriman' => Carbon::now()->subDays(4),
            'status' => 'completed',
            'is_published' => true,
        ]);

        $this->po2 = OpenPo::create([
            'nama_po' => 'PO Batch #2 Aktif',
            'kode_po' => 'PO-TEST-02',
            'tanggal_buka' => Carbon::now()->subDays(2),
            'tanggal_tutup' => Carbon::now()->addDays(2),
            'tanggal_pengiriman' => Carbon::now()->addDays(3),
            'status' => 'open',
            'is_published' => true,
        ]);

        $this->customer = Customer::create([
            'nama' => 'Ibu Maya',
            'no_whatsapp' => '081234567890',
            'alamat' => 'Jakarta Selatan',
            'kategori_pelanggan' => 'VIP',
        ]);

        $this->product1 = Product::create([
            'kode_produk' => 'PRD-01',
            'nama_produk' => 'Nasi Liwet Solo',
            'kategori' => 'Makanan Utama',
            'harga_jual' => 50000,
            'is_active' => true,
            'is_published' => true,
        ]);

        $this->product2 = Product::create([
            'kode_produk' => 'PRD-02',
            'nama_produk' => 'Fudgy Brownies Shiny',
            'kategori' => 'Snack',
            'harga_jual' => 75000,
            'is_active' => true,
            'is_published' => true,
        ]);

        // Seed Sale in PO 1: Omzet 100.000 (Lunas)
        $sale1 = Sale::create([
            'nomor_order' => 'INV-TEST-001',
            'open_po_id' => $this->po1->id,
            'customer_id' => $this->customer->id,
            'tanggal_pemesanan' => Carbon::now()->subDays(8),
            'subtotal' => 100000,
            'diskon' => 0,
            'ongkos_kirim' => 0,
            'total_tagihan' => 100000,
            'status_pembayaran' => 'lunas',
            'status_order' => 'selesai',
        ]);
        SaleItem::create([
            'sale_id' => $sale1->id,
            'product_id' => $this->product1->id,
            'nama_produk_snapshot' => $this->product1->nama_produk,
            'jumlah_paket' => 2,
            'harga_jual' => 50000,
            'subtotal' => 100000,
        ]);
        Payment::create([
            'sale_id' => $sale1->id,
            'tanggal_bayar' => Carbon::now()->subDays(8),
            'jenis_pembayaran' => 'pelunasan',
            'nominal_bayar' => 100000,
            'metode_pembayaran' => 'Transfer BCA',
            'status_verifikasi' => 'verified',
        ]);

        // Seed Sale in PO 2: Omzet 150.000 (DP 50.000, Piutang 100.000)
        $sale2 = Sale::create([
            'nomor_order' => 'INV-TEST-002',
            'open_po_id' => $this->po2->id,
            'customer_id' => $this->customer->id,
            'tanggal_pemesanan' => Carbon::now()->subDays(1),
            'subtotal' => 150000,
            'diskon' => 0,
            'ongkos_kirim' => 0,
            'total_tagihan' => 150000,
            'status_pembayaran' => 'dp',
            'status_order' => 'diproses',
        ]);
        SaleItem::create([
            'sale_id' => $sale2->id,
            'product_id' => $this->product2->id,
            'nama_produk_snapshot' => $this->product2->nama_produk,
            'jumlah_paket' => 2,
            'harga_jual' => 75000,
            'subtotal' => 150000,
        ]);
        Payment::create([
            'sale_id' => $sale2->id,
            'tanggal_bayar' => Carbon::now()->subDays(1),
            'jenis_pembayaran' => 'dp',
            'nominal_bayar' => 50000,
            'metode_pembayaran' => 'QRIS',
            'status_verifikasi' => 'verified',
        ]);

        // Purchases: PO1 = 40.000, PO2 = 60.000 -> Total = 100.000
        Purchase::create([
            'open_po_id' => $this->po1->id,
            'tanggal_pembelian' => Carbon::now()->subDays(9),
            'nama_toko_supplier' => 'Pasar Jaya',
            'nama_barang' => 'Beras & Bumbu',
            'jumlah' => 2,
            'satuan' => 'kg',
            'harga_satuan' => 20000,
            'total_pembelian' => 40000,
            'metode_pembayaran' => 'Cash',
        ]);
        Purchase::create([
            'open_po_id' => $this->po2->id,
            'tanggal_pembelian' => Carbon::now()->subDays(2),
            'nama_toko_supplier' => 'Toko Bahan Kue',
            'nama_barang' => 'Cokelat Bubuk & Mentega',
            'jumlah' => 1,
            'satuan' => 'kg',
            'harga_satuan' => 60000,
            'total_pembelian' => 60000,
            'metode_pembayaran' => 'Transfer',
        ]);

        // Other Expenses: PO1 = 10.000, PO2 = 15.000 -> Total = 25.000
        OtherExpense::create([
            'open_po_id' => $this->po1->id,
            'tanggal_pengeluaran' => Carbon::now()->subDays(8),
            'kategori_biaya' => 'Kemasan/Packaging',
            'nama_pengeluaran' => 'Dus Nasi 2 pcs',
            'nominal' => 10000,
            'metode_pembayaran' => 'Cash',
        ]);
        OtherExpense::create([
            'open_po_id' => $this->po2->id,
            'tanggal_pengeluaran' => Carbon::now()->subDays(1),
            'kategori_biaya' => 'Gas',
            'nama_pengeluaran' => 'Gas Elpiji',
            'nominal' => 15000,
            'metode_pembayaran' => 'Cash',
        ]);
    }

    public function test_dashboard_renders_with_correct_kpis_and_surplus_formula(): void
    {
        // Total Omzet = 100.000 + 150.000 = 250.000
        // Total Pembelian = 40.000 + 60.000 = 100.000
        // Total Biaya Lain = 10.000 + 15.000 = 25.000
        // Surplus = 250.000 - 100.000 - 25.000 = 125.000
        // Total Kas Masuk = 100.000 + 50.000 = 150.000
        // Total Piutang = 100.000

        Livewire::actingAs($this->user)
            ->test(Dashboard::class)
            ->assertStatus(200)
            ->assertViewHas('totalOmzet', 250000.0)
            ->assertViewHas('totalPembelian', 100000.0)
            ->assertViewHas('totalBiayaLain', 25000.0)
            ->assertViewHas('surplus', 125000.0)
            ->assertViewHas('totalPaid', 150000.0)
            ->assertViewHas('totalPiutang', 100000.0)
            ->assertSee('PO Batch #2 Aktif'); // Active open PO banner
    }

    public function test_dashboard_reactively_filters_by_open_po_batch(): void
    {
        // When filtered by PO 1:
        // Omzet = 100.000
        // Pembelian = 40.000
        // Biaya Lain = 10.000
        // Surplus = 50.000
        // Total Piutang = 0

        Livewire::actingAs($this->user)
            ->test(Dashboard::class)
            ->set('selectedOpenPoId', (string) $this->po1->id)
            ->assertViewHas('totalOmzet', 100000.0)
            ->assertViewHas('totalPembelian', 40000.0)
            ->assertViewHas('totalBiayaLain', 10000.0)
            ->assertViewHas('surplus', 50000.0)
            ->assertViewHas('totalPiutang', 0.0);
    }

    public function test_reports_displays_profit_loss_statement(): void
    {
        Livewire::actingAs($this->user)
            ->test(Reports::class)
            ->assertStatus(200)
            ->assertViewHas('totalOmzet', 250000.0)
            ->assertViewHas('totalPembelian', 100000.0)
            ->assertViewHas('totalBiayaLain', 25000.0)
            ->assertViewHas('surplus', 125000.0)
            ->assertSee('SORASA')
            ->assertSee('Laporan Laba Rugi Sederhana');
    }

    public function test_reports_can_export_csv(): void
    {
        Livewire::actingAs($this->user)
            ->test(Reports::class)
            ->call('exportCsv')
            ->assertFileDownloaded();
    }
}
