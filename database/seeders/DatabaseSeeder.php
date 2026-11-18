<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\OpenPo;
use App\Models\OtherExpense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Recipe;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with realistic Open PO business data.
     */
    public function run(): void
    {
        // 1. Users
        $owner = User::query()->create([
            'name' => 'Sorasa Owner',
            'email' => 'owner@sorasa.test',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        $admin = User::query()->create([
            'name' => 'Admin Dapur Sorasa',
            'email' => 'admin@sorasa.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $customerUser = User::query()->create([
            'name' => 'dr. Felicia Cindy',
            'email' => 'felicia@customer.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // 2. Settings (Profil Usaha SORASA)
        $settings = [
            'store_name' => 'SORASA Kitchen & Pastry',
            'store_tagline' => 'Spesialis Pre-Order Masakan Nusantara & Dessert Homemade',
            'store_phone' => '081223456789',
            'store_email' => 'halo@sorasa.test',
            'store_address' => 'Jl. Cendrawasih No. 24, Sukajadi, Kota Bandung, Jawa Barat 40162',
            'kitchen_notes' => 'Harap konfirmasi jadwal kirim H-1 sebelum tanggal pengiriman PO.',
            'bank_name' => 'BCA (Bank Central Asia)',
            'bank_account_number' => '8420198273',
            'bank_account_name' => 'SORASA KULINER NUSANTARA',
            'qris_enabled' => '1',
            'currency_symbol' => 'Rp',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // 3. Products
        $productsData = [
            [
                'kode_produk' => 'PRD-001',
                'nama_produk' => 'Nasi Liwet Komplit Ayam Bakar Serundeng',
                'kategori' => 'Makanan Utama',
                'harga_jual' => 45000,
                'deskripsi' => 'Nasi liwet wangi teri medan, ayam bakar bumbu rujak serundeng, tahu/tempe bacem, lalapan segar & sambal terasi matang.',
                'foto_produk' => null,
                'is_active' => true,
                'is_published' => true,
            ],
            [
                'kode_produk' => 'PRD-002',
                'nama_produk' => 'Rendang Sapi Daging Pilihan (Pack 500g)',
                'kategori' => 'Makanan Utama',
                'harga_jual' => 160000,
                'deskripsi' => 'Rendang daging sapi has dalam empuk dimasak perlahan 8 jam dengan kelapa sangrai dan rempah Minang otentik.',
                'foto_produk' => null,
                'is_active' => true,
                'is_published' => true,
            ],
            [
                'kode_produk' => 'PRD-003',
                'nama_produk' => 'Ayam Ungkep Lengkuas Siap Goreng (1 Ekor)',
                'kategori' => 'Makanan Utama',
                'harga_jual' => 85000,
                'deskripsi' => 'Ayam pejantan utuh 1 ekor diungkep bumbu lengkuas melimpah, vaccum pack higienis dan siap goreng.',
                'foto_produk' => null,
                'is_active' => true,
                'is_published' => true,
            ],
            [
                'kode_produk' => 'PRD-004',
                'nama_produk' => 'Lasagna Panggang Creamy Beef (Medium 20x10)',
                'kategori' => 'Snack & Dessert',
                'harga_jual' => 75000,
                'deskripsi' => 'Lapisan pasta lembut dengan saus bolognese daging sapi cincang gurih, béchamel creamy dan taburan lelehan mozzarella.',
                'foto_produk' => null,
                'is_active' => true,
                'is_published' => true,
            ],
            [
                'kode_produk' => 'PRD-005',
                'nama_produk' => 'Fudgy Brownies Shiny Crust (Topping Almond & Chocochip)',
                'kategori' => 'Snack & Dessert',
                'harga_jual' => 65000,
                'deskripsi' => 'Brownies cokelat murni couverture legit dan pekat dengan lapisan shiny crust berkilau ukuran 20x10 cm.',
                'foto_produk' => null,
                'is_active' => true,
                'is_published' => true,
            ],
            [
                'kode_produk' => 'PRD-006',
                'nama_produk' => 'Hampers Nasi Box Berkah (Paket 5 Porsi)',
                'kategori' => 'Paket Hampers',
                'harga_jual' => 235000,
                'deskripsi' => 'Paket 5 box premium nasi liwet ayam bakar serundeng lengkap dengan kemasan hardbox mewah, pita, & kartu ucapan.',
                'foto_produk' => null,
                'is_active' => true,
                'is_published' => true,
            ],
        ];

        $products = [];
        foreach ($productsData as $p) {
            $products[$p['kode_produk']] = Product::query()->create($p);
        }

        // 4. Recipes & Recipe Items (Contoh estimasi HPP untuk PRD-001 & PRD-004)
        $recipe1 = Recipe::query()->create([
            'product_id' => $products['PRD-001']->id,
            'nama_resep' => 'Standar Porsi Nasi Liwet Ayam Bakar (Batch 10 Porsi)',
            'porsi_hasil' => 10,
            'total_biaya_bahan' => 215000,
            'catatan_pembuatan' => 'Beras direndam 20 menit sebelum dimasak dengan air kaldu ayam & santan encer.',
        ]);

        $recipe1Items = [
            ['nama_bahan' => 'Beras Pandan Wangi', 'qty' => 1.5, 'satuan' => 'kg', 'harga_per_satuan' => 17500, 'harga_total' => 26250],
            ['nama_bahan' => 'Ayam Pejantan (Potong 10)', 'qty' => 2.5, 'satuan' => 'kg', 'harga_per_satuan' => 42000, 'harga_total' => 105000],
            ['nama_bahan' => 'Minyak Goreng & Kelapa Parut', 'qty' => 1.0, 'satuan' => 'kg', 'harga_per_satuan' => 22000, 'harga_total' => 22000],
            ['nama_bahan' => 'Bumbu Halus & Rempah Liwet', 'qty' => 0.5, 'satuan' => 'kg', 'harga_per_satuan' => 35000, 'harga_total' => 17500],
            ['nama_bahan' => 'Tahu, Tempe & Lalapan Sambal', 'qty' => 1.0, 'satuan' => 'pack', 'harga_per_satuan' => 25000, 'harga_total' => 25000],
            ['nama_bahan' => 'Kotak Bento & Sendok Set', 'qty' => 10.0, 'satuan' => 'pcs', 'harga_per_satuan' => 1925, 'harga_total' => 19250],
        ];
        foreach ($recipe1Items as $item) {
            $recipe1->items()->create($item);
        }

        $recipe2 = Recipe::query()->create([
            'product_id' => $products['PRD-004']->id,
            'nama_resep' => 'Lasagna Beef Creamy (Batch 5 Loyang Medium)',
            'porsi_hasil' => 5,
            'total_biaya_bahan' => 187500,
            'catatan_pembuatan' => 'Panggang pada suhu 180C api atas-bawah selama 25 menit hingga keju kecokelatan.',
        ]);

        $recipe2Items = [
            ['nama_bahan' => 'Daging Sapi Cincang', 'qty' => 0.75, 'satuan' => 'kg', 'harga_per_satuan' => 135000, 'harga_total' => 101250],
            ['nama_bahan' => 'Pasta Lasagna Sheets', 'qty' => 1.0, 'satuan' => 'pack', 'harga_per_satuan' => 32000, 'harga_total' => 32000],
            ['nama_bahan' => 'Susu Cair & Mentega Béchamel', 'qty' => 0.8, 'satuan' => 'liter', 'harga_per_satuan' => 20000, 'harga_total' => 16000],
            ['nama_bahan' => 'Keju Mozzarella Parut', 'qty' => 0.35, 'satuan' => 'kg', 'harga_per_satuan' => 85000, 'harga_total' => 29750],
            ['nama_bahan' => 'Alumunium Foil Tray 20x10', 'qty' => 5.0, 'satuan' => 'pcs', 'harga_per_satuan' => 1700, 'harga_total' => 8500],
        ];
        foreach ($recipe2Items as $item) {
            $recipe2->items()->create($item);
        }

        // 5. Customers
        $customersData = [
            [
                'user_id' => null,
                'nama' => 'Maya Kusuma',
                'no_whatsapp' => '08122334455',
                'alamat' => 'Komplek Dago Regency Blok B No. 12, Coblong, Kota Bandung',
                'kategori_pelanggan' => 'VIP',
                'catatan' => 'Suka pesan untuk arisan keluarga, minta sambal dipisah ekstra pedas.',
            ],
            [
                'user_id' => null,
                'nama' => 'Budi Prasetyo',
                'no_whatsapp' => '08139988776',
                'alamat' => 'Jl. Setiabudi Permai Kavling 45, Cidadap, Kota Bandung',
                'kategori_pelanggan' => 'Reguler',
                'catatan' => 'Langganan ayam ungkep dan brownies.',
            ],
            [
                'user_id' => null,
                'nama' => 'Ibu Ratna Juwita',
                'no_whatsapp' => '08156677889',
                'alamat' => 'Perumahan Antapani Regency Cluster C-3, Antapani, Kota Bandung',
                'kategori_pelanggan' => 'Reseller',
                'catatan' => 'Reseller aktif, order dalam kuantitas banyak untuk dijual kembali.',
            ],
            [
                'user_id' => null,
                'nama' => 'Dimas Anggara',
                'no_whatsapp' => '08182233445',
                'alamat' => 'Jl. Sukajadi Timur No. 18, Sukasari, Kota Bandung',
                'kategori_pelanggan' => 'Keluarga/Teman',
                'catatan' => 'Teman sekolah, pengiriman sering pickup mandiri atau titip ojol.',
            ],
            [
                'user_id' => $customerUser->id,
                'nama' => 'dr. Felicia Cindy',
                'no_whatsapp' => '08129900112',
                'alamat' => 'Pasteur Indah Permai Blok D-9, Sukajadi, Kota Bandung',
                'kategori_pelanggan' => 'VIP',
                'catatan' => 'Dokter RS Advent, rutin pesan hampers untuk rekan sejawat.',
            ],
            [
                'user_id' => null,
                'nama' => 'Hendra Tan',
                'no_whatsapp' => '08561122334',
                'alamat' => 'Taman Kopo Indah II Blok E No. 77, Bandung Kulon',
                'kategori_pelanggan' => 'Reguler',
                'catatan' => 'Pelanggan setia rendang sapi pack 500g.',
            ],
            [
                'user_id' => null,
                'nama' => 'Sarah Amelia',
                'no_whatsapp' => '08173344556',
                'alamat' => 'Buah Batu Square No. F-15, Bojongsoang, Bandung',
                'kategori_pelanggan' => 'Reguler',
                'catatan' => 'Pembayaran selalu tepat waktu via QRIS.',
            ],
            [
                'user_id' => null,
                'nama' => 'Kevin Sanjaya',
                'no_whatsapp' => '08138899001',
                'alamat' => 'Apartemen Mekarwangi Square Tower A Unit 1102, Cibaduyut',
                'kategori_pelanggan' => 'Reseller',
                'catatan' => 'Reseller snack & hampers event kantor.',
            ],
        ];

        $customers = [];
        foreach ($customersData as $c) {
            $customerModel = Customer::query()->create($c);
            $customers[] = $customerModel;
        }

        // 6. Open PO Batches (Periode 1 bulan terakhir)
        // Hari ini disimulasikan ~ pertengahan November 2026
        $po1 = OpenPo::query()->create([
            'nama_po' => 'PO Batch #12 - Weekend Nusantara',
            'kode_po' => 'PO-202610-01',
            'tanggal_buka' => '2026-10-10',
            'tanggal_tutup' => '2026-10-15',
            'tanggal_pengiriman' => '2026-10-17',
            'status' => 'completed',
            'is_published' => true,
            'catatan' => 'Batch menu khas Nusantara fokus Nasi Liwet & Rendang. Pengiriman lancar.',
        ]);

        $po2 = OpenPo::query()->create([
            'nama_po' => 'PO Batch #13 - Gajian Treat & Hampers',
            'kode_po' => 'PO-202610-02',
            'tanggal_buka' => '2026-10-24',
            'tanggal_tutup' => '2026-10-29',
            'tanggal_pengiriman' => '2026-10-31',
            'status' => 'completed',
            'is_published' => true,
            'catatan' => 'Batch akhir bulan dengan variasi Hampers & Dessert Brownies / Lasagna.',
        ]);

        $po3 = OpenPo::query()->create([
            'nama_po' => 'PO Batch #14 - Mid November Special',
            'kode_po' => 'PO-202611-01',
            'tanggal_buka' => '2026-11-10',
            'tanggal_tutup' => '2026-11-19',
            'tanggal_pengiriman' => '2026-11-21',
            'status' => 'open',
            'is_published' => true,
            'catatan' => 'Batch aktif minggu ini. Pemesanan sedang berjalan.',
        ]);

        $po4 = OpenPo::query()->create([
            'nama_po' => 'PO Batch #15 - Akhir Bulan Ceria',
            'kode_po' => 'PO-202611-02',
            'tanggal_buka' => '2026-11-23',
            'tanggal_tutup' => '2026-11-28',
            'tanggal_pengiriman' => '2026-11-30',
            'status' => 'draft',
            'is_published' => false,
            'catatan' => 'Draft menu promo hampers gajian akhir November.',
        ]);

        // 7. Purchases (Pembelian Bahan Baku - Total Target: ~Rp7.500.000)
        // Catatan: langsung biaya habis pakai, tidak ada stok barang.
        $purchasesData = [
            // Batch #12
            [
                'open_po_id' => $po1->id,
                'tanggal_pembelian' => '2026-10-14',
                'nama_toko_supplier' => 'Toko Daging Barokah Ciroyom',
                'nama_barang' => 'Daging Sapi Has Dalam Segar',
                'jumlah' => 12.00,
                'satuan' => 'kg',
                'harga_satuan' => 135000,
                'total_pembelian' => 1620000,
                'metode_pembayaran' => 'Transfer',
                'keterangan' => 'Daging sapi kualitas super untuk rendang Batch 12.',
                'foto_nota' => null,
            ],
            [
                'open_po_id' => $po1->id,
                'tanggal_pembelian' => '2026-10-15',
                'nama_toko_supplier' => 'Pasar Induk Gedebage (Kios Bu Haji Siti)',
                'nama_barang' => 'Ayam Pejantan Segar',
                'jumlah' => 25.00,
                'satuan' => 'ekor',
                'harga_satuan' => 38000,
                'total_pembelian' => 950000,
                'metode_pembayaran' => 'Cash',
                'keterangan' => 'Ayam segar potong pagi untuk pesanan nasi liwet & ungkep.',
                'foto_nota' => null,
            ],
            [
                'open_po_id' => $po1->id,
                'tanggal_pembelian' => '2026-10-15',
                'nama_toko_supplier' => 'Toko Sembako Berkah Jaya',
                'nama_barang' => 'Beras Pandan Wangi Super',
                'jumlah' => 40.00,
                'satuan' => 'kg',
                'harga_satuan' => 17500,
                'total_pembelian' => 700000,
                'metode_pembayaran' => 'Transfer',
                'keterangan' => 'Beras wangi pulen untuk nasi liwet.',
                'foto_nota' => null,
            ],
            [
                'open_po_id' => $po1->id,
                'tanggal_pembelian' => '2026-10-15',
                'nama_toko_supplier' => 'Kios Kelapa & Rempah Cihapit',
                'nama_barang' => 'Kelapa Parut Santan Kental & Rempah Liwet',
                'jumlah' => 1.00,
                'satuan' => 'pack',
                'harga_satuan' => 450000,
                'total_pembelian' => 450000,
                'metode_pembayaran' => 'Cash',
                'keterangan' => 'Santan murni dan racikan rempah lengkuas, serai, daun salam.',
                'foto_nota' => null,
            ],

            // Batch #13
            [
                'open_po_id' => $po2->id,
                'tanggal_pembelian' => '2026-10-28',
                'nama_toko_supplier' => 'Toko Daging Barokah Ciroyom',
                'nama_barang' => 'Daging Sapi Giling & Has Rendang',
                'jumlah' => 8.00,
                'satuan' => 'kg',
                'harga_satuan' => 135000,
                'total_pembelian' => 1080000,
                'metode_pembayaran' => 'Transfer',
                'keterangan' => 'Daging sapi cincang untuk lasagna & rendang batch 13.',
                'foto_nota' => null,
            ],
            [
                'open_po_id' => $po2->id,
                'tanggal_pembelian' => '2026-10-28',
                'nama_toko_supplier' => 'TBK Prima Rasa Bahan Kue',
                'nama_barang' => 'Keju Cheddar, Mozzarella & Lasagna Sheet',
                'jumlah' => 1.00,
                'satuan' => 'pack',
                'harga_satuan' => 670000,
                'total_pembelian' => 670000,
                'metode_pembayaran' => 'QRIS',
                'keterangan' => 'Bahan dairy dan pasta untuk pesanan lasagna.',
                'foto_nota' => null,
            ],
            [
                'open_po_id' => $po2->id,
                'tanggal_pembelian' => '2026-10-29',
                'nama_toko_supplier' => 'TBK Kurnia Bahan Pastry',
                'nama_barang' => 'Dark Couverture Chocolate & Cocoa Powder Van Houten',
                'jumlah' => 1.00,
                'satuan' => 'pack',
                'harga_satuan' => 520000,
                'total_pembelian' => 520000,
                'metode_pembayaran' => 'Transfer',
                'keterangan' => 'Cokelat kualitas tinggi untuk fudgy brownies batch 13.',
                'foto_nota' => null,
            ],
            [
                'open_po_id' => $po2->id,
                'tanggal_pembelian' => '2026-10-29',
                'nama_toko_supplier' => 'Pasar Induk Gedebage (Kios Bu Haji Siti)',
                'nama_barang' => 'Ayam Pejantan & Telur Ayam',
                'jumlah' => 18.00,
                'satuan' => 'ekor',
                'harga_satuan' => 38000,
                'total_pembelian' => 684000,
                'metode_pembayaran' => 'Cash',
                'keterangan' => 'Ayam pejantan dan 2 tray telur ayam fresh.',
                'foto_nota' => null,
            ],

            // Batch #14 (Sedang berjalan)
            [
                'open_po_id' => $po3->id,
                'tanggal_pembelian' => '2026-11-16',
                'nama_toko_supplier' => 'Toko Daging Barokah Ciroyom',
                'nama_barang' => 'Daging Sapi & Ayam Pejantan Awal',
                'jumlah' => 4.00,
                'satuan' => 'kg',
                'harga_satuan' => 137500,
                'total_pembelian' => 550000,
                'metode_pembayaran' => 'Transfer',
                'keterangan' => 'Belanja bahan daging dasar PO Batch 14.',
                'foto_nota' => null,
            ],
            [
                'open_po_id' => $po3->id,
                'tanggal_pembelian' => '2026-11-17',
                'nama_toko_supplier' => 'Pasar Sederhana Sukajadi',
                'nama_barang' => 'Bawang Merah, Bawang Putih, Cabe & Bumbu Dapur',
                'jumlah' => 1.00,
                'satuan' => 'pack',
                'harga_satuan' => 230000,
                'total_pembelian' => 230000,
                'metode_pembayaran' => 'QRIS',
                'keterangan' => 'Bumbu dapur fresh persiapan masak PO 14.',
                'foto_nota' => null,
            ],
            [
                'open_po_id' => $po3->id,
                'tanggal_pembelian' => '2026-11-17',
                'nama_toko_supplier' => 'TBK Prima Rasa',
                'nama_barang' => 'Topping Almond Slice & Kertas Roti Baking',
                'jumlah' => 1.00,
                'satuan' => 'pack',
                'harga_satuan' => 66000,
                'total_pembelian' => 66000,
                'metode_pembayaran' => 'Cash',
                'keterangan' => 'Topping brownies batch 14.',
                'foto_nota' => null,
            ],
        ];

        // Total Pembelian: 3.720.000 + 2.954.000 + 846.000 = Rp7.520.000 (~Rp7.500.000)

        foreach ($purchasesData as $p) {
            Purchase::query()->create($p);
        }

        // 8. Other Expenses (Biaya Operasional - Total Target: ~Rp1.500.000)
        $expensesData = [
            [
                'open_po_id' => $po1->id,
                'tanggal_pengeluaran' => '2026-10-15',
                'kategori_biaya' => 'Gas',
                'nama_pengeluaran' => 'Isi Ulang Gas Elpiji 12kg (2 Tabung)',
                'nominal' => 430000,
                'metode_pembayaran' => 'Cash',
                'keterangan' => 'Gas untuk masak rendang 8 jam & ungkepan liwet.',
                'bukti_nota' => null,
            ],
            [
                'open_po_id' => $po1->id,
                'tanggal_pengeluaran' => '2026-10-16',
                'kategori_biaya' => 'Kemasan/Packaging',
                'nama_pengeluaran' => 'Box Bento Foodgrade, Paperbag & Stiker Logo',
                'nominal' => 380000,
                'metode_pembayaran' => 'QRIS',
                'keterangan' => 'Packaging bento ramah lingkungan dan stiker seal higienis.',
                'bukti_nota' => null,
            ],
            [
                'open_po_id' => $po2->id,
                'tanggal_pengeluaran' => '2026-10-30',
                'kategori_biaya' => 'Tenaga Kerja',
                'nama_pengeluaran' => 'Upah Asisten Masak & Packing (2 Orang x 2 Hari)',
                'nominal' => 450000,
                'metode_pembayaran' => 'Transfer',
                'keterangan' => 'Bantuan prep bahan, penggorengan dan packing hampers.',
                'bukti_nota' => null,
            ],
            [
                'open_po_id' => $po2->id,
                'tanggal_pengeluaran' => '2026-10-31',
                'kategori_biaya' => 'Transportasi',
                'nama_pengeluaran' => 'Bantuan Bensin & Ongkir Khusus Titik Drop Dapur',
                'nominal' => 90000,
                'metode_pembayaran' => 'Cash',
                'keterangan' => 'Drop pesanan ke kurir logistik kota.',
                'bukti_nota' => null,
            ],
            [
                'open_po_id' => $po3->id,
                'tanggal_pengeluaran' => '2026-11-15',
                'kategori_biaya' => 'Listrik/Air',
                'nama_pengeluaran' => 'Token Listrik Operasional Dapur & Deep Freezer',
                'nominal' => 200000,
                'metode_pembayaran' => 'Transfer',
                'keterangan' => 'Kapasitas listrik oven dan pendingin daging.',
                'bukti_nota' => null,
            ],
        ];

        // Total Other Expenses: 430.000 + 380.000 + 450.000 + 90.000 + 200.000 = Rp1.550.000 (~Rp1.500.000)
        foreach ($expensesData as $e) {
            OtherExpense::query()->create($e);
        }

        // 9. Sales (Target Omzet: ~Rp15.000.000, Pembayaran Bertahap & Piutang)
        // Order Simulation:
        $salesList = [
            // Order 1 (Batch 12) - Maya Kusuma (Lunas BCA)
            [
                'nomor_order' => 'INV-20261012-0001',
                'open_po_id' => $po1->id,
                'customer_id' => $customers[0]->id, // Maya
                'tanggal_pemesanan' => '2026-10-12',
                'diskon' => 0,
                'ongkos_kirim' => 20000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'selesai',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Kirim jam 11 siang untuk makan siang arisan.',
                'items' => [
                    ['product_id' => $products['PRD-001']->id, 'qty' => 15], // 15 x 45.000 = 675.000
                    ['product_id' => $products['PRD-002']->id, 'qty' => 2],  // 2 x 160.000 = 320.000
                ],
                'delivery' => [
                    'tanggal_pengiriman' => '2026-10-17',
                    'jam_pengiriman' => '11:00',
                    'nama_penerima' => 'Maya Kusuma',
                    'no_telepon_penerima' => '08122334455',
                    'alamat_lengkap' => 'Komplek Dago Regency Blok B No. 12, Coblong, Kota Bandung',
                    'patokan_lokasi' => 'Pagar abu-abu, depan pos sekuriti cluster Dago',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.8851,107.6135',
                    'status_pengantaran' => 'terkirim',
                    'catatan_kurir' => 'Diterima langsung oleh Ibu Maya.',
                ],
                'payments' => [
                    ['tanggal' => '2026-10-12', 'jenis' => 'pelunasan', 'nominal' => 1015000, 'metode' => 'Transfer BCA'],
                ],
            ],

            // Order 2 (Batch 12) - Budi Prasetyo (Lunas QRIS)
            [
                'nomor_order' => 'INV-20261013-0002',
                'open_po_id' => $po1->id,
                'customer_id' => $customers[1]->id, // Budi
                'tanggal_pemesanan' => '2026-10-13',
                'diskon' => 15000, // Diskon promo
                'ongkos_kirim' => 15000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'selesai',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Ayam ungkep tolong divakum rapi mau dibawa ke Jakarta.',
                'items' => [
                    ['product_id' => $products['PRD-003']->id, 'qty' => 3], // 3 x 85.000 = 255.000
                    ['product_id' => $products['PRD-005']->id, 'qty' => 2], // 2 x 65.000 = 130.000
                ],
                'delivery' => [
                    'tanggal_pengiriman' => '2026-10-17',
                    'jam_pengiriman' => '14:30',
                    'nama_penerima' => 'Budi Prasetyo',
                    'no_telepon_penerima' => '08139988776',
                    'alamat_lengkap' => 'Jl. Setiabudi Permai Kavling 45, Cidadap, Kota Bandung',
                    'patokan_lokasi' => 'Rumah tingkat dua samping minimarket',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.8621,107.5951',
                    'status_pengantaran' => 'terkirim',
                    'catatan_kurir' => 'Diterima oleh ART.',
                ],
                'payments' => [
                    ['tanggal' => '2026-10-13', 'jenis' => 'pelunasan', 'nominal' => 385000, 'metode' => 'QRIS'],
                ],
            ],

            // Order 3 (Batch 12) - Ibu Ratna Juwita (Reseller - Lunas Transfer)
            [
                'nomor_order' => 'INV-20261013-0003',
                'open_po_id' => $po1->id,
                'customer_id' => $customers[2]->id, // Ratna
                'tanggal_pemesanan' => '2026-10-13',
                'diskon' => 50000, // Diskon reseller
                'ongkos_kirim' => 25000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'selesai',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Paket reseller untuk dijual kembali di cluster Antapani.',
                'items' => [
                    ['product_id' => $products['PRD-001']->id, 'qty' => 15], // 15 x 45.000 = 675.000
                    ['product_id' => $products['PRD-002']->id, 'qty' => 4],  // 4 x 160.000 = 640.000
                    ['product_id' => $products['PRD-003']->id, 'qty' => 3],  // 3 x 85.000 = 255.000
                ], // Subtotal: 1.570.000 - 50.000 + 25.000 = 1.545.000
                'delivery' => [
                    'tanggal_pengiriman' => '2026-10-17',
                    'jam_pengiriman' => '09:00',
                    'nama_penerima' => 'Ibu Ratna Juwita',
                    'no_telepon_penerima' => '08156677889',
                    'alamat_lengkap' => 'Perumahan Antapani Regency Cluster C-3, Antapani, Kota Bandung',
                    'patokan_lokasi' => 'Depan taman bermain cluster',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.9189,107.6582',
                    'status_pengantaran' => 'terkirim',
                    'catatan_kurir' => 'Antar pagi jam 9 tepat.',
                ],
                'payments' => [
                    ['tanggal' => '2026-10-13', 'jenis' => 'dp', 'nominal' => 700000, 'metode' => 'Transfer BCA'],
                    ['tanggal' => '2026-10-16', 'jenis' => 'pelunasan', 'nominal' => 845000, 'metode' => 'Transfer BCA'],
                ],
            ],

            // Order 4 (Batch 12) - Dimas Anggara (Lunas Cash)
            [
                'nomor_order' => 'INV-20261014-0004',
                'open_po_id' => $po1->id,
                'customer_id' => $customers[3]->id, // Dimas
                'tanggal_pemesanan' => '2026-10-14',
                'diskon' => 0,
                'ongkos_kirim' => 0, // Pickup sendiri
                'status_pembayaran' => 'lunas',
                'status_order' => 'selesai',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Ambil sendiri di dapur jam 12.',
                'items' => [
                    ['product_id' => $products['PRD-001']->id, 'qty' => 6], // 6 x 45.000 = 270.000
                    ['product_id' => $products['PRD-005']->id, 'qty' => 2], // 2 x 65.000 = 130.000
                ],
                'delivery' => [
                    'tanggal_pengiriman' => '2026-10-17',
                    'jam_pengiriman' => '12:00',
                    'nama_penerima' => 'Dimas Anggara',
                    'no_telepon_penerima' => '08182233445',
                    'alamat_lengkap' => 'Jl. Sukajadi Timur No. 18, Sukasari, Kota Bandung (Self Pickup Dapur)',
                    'patokan_lokasi' => 'Ambil langsung ke dapur SORASA',
                    'link_google_maps' => null,
                    'status_pengantaran' => 'terkirim',
                    'catatan_kurir' => 'Pickup langsung oleh customer.',
                ],
                'payments' => [
                    ['tanggal' => '2026-10-17', 'jenis' => 'pelunasan', 'nominal' => 400000, 'metode' => 'Cash'],
                ],
            ],

            // Order 5 (Batch 12) - Hendra Tan (Lunas Transfer)
            [
                'nomor_order' => 'INV-20261014-0005',
                'open_po_id' => $po1->id,
                'customer_id' => $customers[5]->id, // Hendra Tan
                'tanggal_pemesanan' => '2026-10-14',
                'diskon' => 0,
                'ongkos_kirim' => 25000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'selesai',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Rendang dipacking per pack 500g.',
                'items' => [
                    ['product_id' => $products['PRD-002']->id, 'qty' => 3], // 3 x 160.000 = 480.000
                ],
                'delivery' => [
                    'tanggal_pengiriman' => '2026-10-17',
                    'jam_pengiriman' => '15:00',
                    'nama_penerima' => 'Hendra Tan',
                    'no_telepon_penerima' => '08561122334',
                    'alamat_lengkap' => 'Taman Kopo Indah II Blok E No. 77, Bandung Kulon',
                    'patokan_lokasi' => 'Dekat bundaran TKI II',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.9634,107.5712',
                    'status_pengantaran' => 'terkirim',
                    'catatan_kurir' => 'Terkirim aman.',
                ],
                'payments' => [
                    ['tanggal' => '2026-10-14', 'jenis' => 'pelunasan', 'nominal' => 505000, 'metode' => 'Transfer BCA'],
                ],
            ],

            // Order 6 (Batch 13) - dr. Felicia Cindy (VIP - Lunas Transfer)
            [
                'nomor_order' => 'INV-20261025-0006',
                'open_po_id' => $po2->id,
                'customer_id' => $customers[4]->id, // dr. Felicia
                'tanggal_pemesanan' => '2026-10-25',
                'diskon' => 30000, // Diskon VIP
                'ongkos_kirim' => 20000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'selesai',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Hampers untuk direksi RS Advent, tolong pita warna emas dan kartu ucapan.',
                'items' => [
                    ['product_id' => $products['PRD-006']->id, 'qty' => 5], // 5 x 235.000 = 1.175.000
                    ['product_id' => $products['PRD-004']->id, 'qty' => 3], // 3 x 75.000 = 225.000
                ], // Subtotal: 1.400.000 - 30.000 + 20.000 = 1.390.000
                'delivery' => [
                    'tanggal_pengiriman' => '2026-10-31',
                    'jam_pengiriman' => '10:00',
                    'nama_penerima' => 'dr. Felicia Cindy / Lobby Direksi RS',
                    'no_telepon_penerima' => '08129900112',
                    'alamat_lengkap' => 'Pasteur Indah Permai Blok D-9, Sukajadi, Kota Bandung',
                    'patokan_lokasi' => 'Pintu gerbang putih, depan taman lavender',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.8920,107.5990',
                    'status_pengantaran' => 'terkirim',
                    'catatan_kurir' => 'Diterima oleh dr. Felicia langsung.',
                ],
                'payments' => [
                    ['tanggal' => '2026-10-25', 'jenis' => 'dp', 'nominal' => 700000, 'metode' => 'Transfer BCA'],
                    ['tanggal' => '2026-10-30', 'jenis' => 'pelunasan', 'nominal' => 690000, 'metode' => 'Transfer BCA'],
                ],
            ],

            // Order 7 (Batch 13) - Kevin Sanjaya (Reseller - Lunas)
            [
                'nomor_order' => 'INV-20261026-0007',
                'open_po_id' => $po2->id,
                'customer_id' => $customers[7]->id, // Kevin
                'tanggal_pemesanan' => '2026-10-26',
                'diskon' => 25000,
                'ongkos_kirim' => 25000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'selesai',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Lasagna & Brownies untuk acara kantor akhir bulan.',
                'items' => [
                    ['product_id' => $products['PRD-004']->id, 'qty' => 8], // 8 x 75.000 = 600.000
                    ['product_id' => $products['PRD-005']->id, 'qty' => 7], // 7 x 65.000 = 455.000
                ], // Subtotal: 1.055.000 - 25.000 + 25.000 = 1.055.000
                'delivery' => [
                    'tanggal_pengiriman' => '2026-10-31',
                    'jam_pengiriman' => '13:00',
                    'nama_penerima' => 'Kevin Sanjaya',
                    'no_telepon_penerima' => '08138899001',
                    'alamat_lengkap' => 'Apartemen Mekarwangi Square Tower A Unit 1102, Cibaduyut',
                    'patokan_lokasi' => 'Lobby Tower A, titip resepsionis',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.9532,107.5921',
                    'status_pengantaran' => 'terkirim',
                    'catatan_kurir' => 'Titip resepsionis Tower A dengan barcode akses.',
                ],
                'payments' => [
                    ['tanggal' => '2026-10-26', 'jenis' => 'pelunasan', 'nominal' => 1055000, 'metode' => 'Transfer BCA'],
                ],
            ],

            // Order 8 (Batch 13) - Sarah Amelia (Lunas QRIS)
            [
                'nomor_order' => 'INV-20261027-0008',
                'open_po_id' => $po2->id,
                'customer_id' => $customers[6]->id, // Sarah
                'tanggal_pemesanan' => '2026-10-27',
                'diskon' => 0,
                'ongkos_kirim' => 20000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'selesai',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Pesan brownies dan lasagna buat kumpul keluarga.',
                'items' => [
                    ['product_id' => $products['PRD-004']->id, 'qty' => 3], // 3 x 75.000 = 225.000
                    ['product_id' => $products['PRD-005']->id, 'qty' => 3], // 3 x 65.000 = 195.000
                ],
                'delivery' => [
                    'tanggal_pengiriman' => '2026-10-31',
                    'jam_pengiriman' => '16:00',
                    'nama_penerima' => 'Sarah Amelia',
                    'no_telepon_penerima' => '08173344556',
                    'alamat_lengkap' => 'Buah Batu Square No. F-15, Bojongsoang, Bandung',
                    'patokan_lokasi' => 'Gerbang perumahan dekat pos 1',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.9682,107.6334',
                    'status_pengantaran' => 'terkirim',
                    'catatan_kurir' => 'Diterima Sarah Amelia.',
                ],
                'payments' => [
                    ['tanggal' => '2026-10-27', 'jenis' => 'pelunasan', 'nominal' => 440000, 'metode' => 'QRIS'],
                ],
            ],

            // Order 9 (Batch 13) - Maya Kusuma (Lunas)
            [
                'nomor_order' => 'INV-20261028-0009',
                'open_po_id' => $po2->id,
                'customer_id' => $customers[0]->id, // Maya
                'tanggal_pemesanan' => '2026-10-28',
                'diskon' => 0,
                'ongkos_kirim' => 20000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'selesai',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Kirim siang.',
                'items' => [
                    ['product_id' => $products['PRD-006']->id, 'qty' => 4], // 4 x 235.000 = 940.000
                ],
                'delivery' => [
                    'tanggal_pengiriman' => '2026-10-31',
                    'jam_pengiriman' => '11:30',
                    'nama_penerima' => 'Maya Kusuma',
                    'no_telepon_penerima' => '08122334455',
                    'alamat_lengkap' => 'Komplek Dago Regency Blok B No. 12, Coblong, Kota Bandung',
                    'patokan_lokasi' => 'Pagar abu-abu cluster Dago',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.8851,107.6135',
                    'status_pengantaran' => 'terkirim',
                    'catatan_kurir' => 'Selesai diantar.',
                ],
                'payments' => [
                    ['tanggal' => '2026-10-28', 'jenis' => 'pelunasan', 'nominal' => 960000, 'metode' => 'Transfer BCA'],
                ],
            ],

            // Order 10 (Batch 13) - Budi Prasetyo (Lunas)
            [
                'nomor_order' => 'INV-20261029-0010',
                'open_po_id' => $po2->id,
                'customer_id' => $customers[1]->id, // Budi
                'tanggal_pemesanan' => '2026-10-29',
                'diskon' => 0,
                'ongkos_kirim' => 15000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'selesai',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Rendang 2 pack.',
                'items' => [
                    ['product_id' => $products['PRD-002']->id, 'qty' => 2], // 2 x 160.000 = 320.000
                    ['product_id' => $products['PRD-003']->id, 'qty' => 2], // 2 x 85.000 = 170.000
                ],
                'delivery' => [
                    'tanggal_pengiriman' => '2026-10-31',
                    'jam_pengiriman' => '15:30',
                    'nama_penerima' => 'Budi Prasetyo',
                    'no_telepon_penerima' => '08139988776',
                    'alamat_lengkap' => 'Jl. Setiabudi Permai Kavling 45, Cidadap, Kota Bandung',
                    'patokan_lokasi' => 'Rumah tingkat dua samping minimarket',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.8621,107.5951',
                    'status_pengantaran' => 'terkirim',
                    'catatan_kurir' => 'Terkirim.',
                ],
                'payments' => [
                    ['tanggal' => '2026-10-29', 'jenis' => 'pelunasan', 'nominal' => 505000, 'metode' => 'Transfer BCA'],
                ],
            ],

            // ====================== BATCH 14 (CURRENT / ACTIVE BATCH) ======================

            // Order 11 (Batch 14) - Ibu Ratna Juwita (STATUS DP - PIUTANG Rp1.000.000)
            [
                'nomor_order' => 'INV-20261111-0011',
                'open_po_id' => $po3->id,
                'customer_id' => $customers[2]->id, // Ratna
                'tanggal_pemesanan' => '2026-11-11',
                'diskon' => 50000,
                'ongkos_kirim' => 30000,
                'status_pembayaran' => 'dp', // STATUS DP
                'status_order' => 'diproses',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'DP masuk Rp1.500.000. Pelunasan sebelum pengiriman Sabtu.',
                'items' => [
                    ['product_id' => $products['PRD-001']->id, 'qty' => 25], // 25 x 45.000 = 1.125.000
                    ['product_id' => $products['PRD-002']->id, 'qty' => 5],  // 5 x 160.000 = 800.000
                    ['product_id' => $products['PRD-006']->id, 'qty' => 2],  // 2 x 235.000 = 470.000
                    ['product_id' => $products['PRD-004']->id, 'qty' => 2],  // 2 x 75.000 = 150.000
                ], // Subtotal: 2.545.000 - 50.000 + 30.000 = 2.525.000
                'delivery' => [
                    'tanggal_pengiriman' => '2026-11-21',
                    'jam_pengiriman' => '08:30',
                    'nama_penerima' => 'Ibu Ratna Juwita',
                    'no_telepon_penerima' => '08156677889',
                    'alamat_lengkap' => 'Perumahan Antapani Regency Cluster C-3, Antapani, Kota Bandung',
                    'patokan_lokasi' => 'Depan taman bermain cluster',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.9189,107.6582',
                    'status_pengantaran' => 'dijadwalkan',
                    'catatan_kurir' => 'Antar pagi sebelum jam 9.',
                ],
                'payments' => [
                    ['tanggal' => '2026-11-11', 'jenis' => 'dp', 'nominal' => 1500000, 'metode' => 'Transfer BCA'], // Sisa Piutang: 1.025.000
                ],
            ],

            // Order 12 (Batch 14) - dr. Felicia Cindy (STATUS LUNAS)
            [
                'nomor_order' => 'INV-20261112-0012',
                'open_po_id' => $po3->id,
                'customer_id' => $customers[4]->id, // dr. Felicia
                'tanggal_pemesanan' => '2026-11-12',
                'diskon' => 25000,
                'ongkos_kirim' => 20000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'diproses',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Hampers Jumat berkah untuk kolega spesialis.',
                'items' => [
                    ['product_id' => $products['PRD-006']->id, 'qty' => 5], // 5 x 235.000 = 1.175.000
                    ['product_id' => $products['PRD-005']->id, 'qty' => 4], // 4 x 65.000 = 260.000
                ], // Subtotal: 1.435.000 - 25.000 + 20.000 = 1.430.000
                'delivery' => [
                    'tanggal_pengiriman' => '2026-11-21',
                    'jam_pengiriman' => '10:00',
                    'nama_penerima' => 'dr. Felicia Cindy',
                    'no_telepon_penerima' => '08129900112',
                    'alamat_lengkap' => 'Pasteur Indah Permai Blok D-9, Sukajadi, Kota Bandung',
                    'patokan_lokasi' => 'Gerbang putih samping taman lavender',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.8920,107.5990',
                    'status_pengantaran' => 'dijadwalkan',
                    'catatan_kurir' => 'Antar sebelum jam makan siang.',
                ],
                'payments' => [
                    ['tanggal' => '2026-11-12', 'jenis' => 'pelunasan', 'nominal' => 1430000, 'metode' => 'Transfer BCA'],
                ],
            ],

            // Order 13 (Batch 14) - Dimas Anggara (STATUS DP - PIUTANG Rp180.000)
            [
                'nomor_order' => 'INV-20261113-0013',
                'open_po_id' => $po3->id,
                'customer_id' => $customers[3]->id, // Dimas
                'tanggal_pemesanan' => '2026-11-13',
                'diskon' => 0,
                'ongkos_kirim' => 15000,
                'status_pembayaran' => 'dp',
                'status_order' => 'diproses',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'DP via QRIS Rp200.000, sisa dibayar pas barang sampai.',
                'items' => [
                    ['product_id' => $products['PRD-001']->id, 'qty' => 6], // 6 x 45.000 = 270.000
                    ['product_id' => $products['PRD-003']->id, 'qty' => 1], // 1 x 85.000 = 85.000
                    ['product_id' => $products['PRD-005']->id, 'qty' => 1], // 1 x 65.000 = 65.000
                ], // Subtotal: 420.000 + 15.000 = 435.000 - 200.000 dp = Piutang 235.000
                'delivery' => [
                    'tanggal_pengiriman' => '2026-11-21',
                    'jam_pengiriman' => '12:00',
                    'nama_penerima' => 'Dimas Anggara',
                    'no_telepon_penerima' => '08182233445',
                    'alamat_lengkap' => 'Jl. Sukajadi Timur No. 18, Sukasari, Kota Bandung',
                    'patokan_lokasi' => 'Depan laundry kiloan',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.8834,107.5940',
                    'status_pengantaran' => 'dijadwalkan',
                    'catatan_kurir' => 'Kurir bawa kembalian jika bayar sisa tagihan cash.',
                ],
                'payments' => [
                    ['tanggal' => '2026-11-13', 'jenis' => 'dp', 'nominal' => 200000, 'metode' => 'QRIS'],
                ],
            ],

            // Order 14 (Batch 14) - Hendra Tan (STATUS BELUM BAYAR - PIUTANG Rp505.000)
            [
                'nomor_order' => 'INV-20261114-0014',
                'open_po_id' => $po3->id,
                'customer_id' => $customers[5]->id, // Hendra Tan
                'tanggal_pemesanan' => '2026-11-14',
                'diskon' => 0,
                'ongkos_kirim' => 25000,
                'status_pembayaran' => 'belum_bayar', // BELUM BAYAR
                'status_order' => 'menunggu_konfirmasi',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Sudah booking pesanan via WhatsApp, menunggu bukti transfer.',
                'items' => [
                    ['product_id' => $products['PRD-002']->id, 'qty' => 2], // 2 x 160.000 = 320.000
                    ['product_id' => $products['PRD-003']->id, 'qty' => 2], // 2 x 85.000 = 170.000
                ], // Subtotal: 490.000 + 25.000 = 515.000
                'delivery' => [
                    'tanggal_pengiriman' => '2026-11-21',
                    'jam_pengiriman' => '15:00',
                    'nama_penerima' => 'Hendra Tan',
                    'no_telepon_penerima' => '08561122334',
                    'alamat_lengkap' => 'Taman Kopo Indah II Blok E No. 77, Bandung Kulon',
                    'patokan_lokasi' => 'Dekat bundaran TKI II',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.9634,107.5712',
                    'status_pengantaran' => 'menunggu',
                    'catatan_kurir' => 'Tunggu status konfirmasi pembayaran sebelum diproses antar.',
                ],
                'payments' => [], // Belum ada pembayaran
            ],

            // Order 15 (Batch 14) - Kevin Sanjaya (STATUS BELUM BAYAR - PIUTANG Rp450.000)
            [
                'nomor_order' => 'INV-20261115-0015',
                'open_po_id' => $po3->id,
                'customer_id' => $customers[7]->id, // Kevin
                'tanggal_pemesanan' => '2026-11-15',
                'diskon' => 15000,
                'ongkos_kirim' => 20000,
                'status_pembayaran' => 'belum_bayar',
                'status_order' => 'menunggu_konfirmasi',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Pesan lasagna 6 box untuk meeting Sabtu sore.',
                'items' => [
                    ['product_id' => $products['PRD-004']->id, 'qty' => 6], // 6 x 75.000 = 450.000
                ], // Subtotal: 450.000 - 15.000 + 20.000 = 455.000
                'delivery' => [
                    'tanggal_pengiriman' => '2026-11-21',
                    'jam_pengiriman' => '14:00',
                    'nama_penerima' => 'Kevin Sanjaya',
                    'no_telepon_penerima' => '08138899001',
                    'alamat_lengkap' => 'Apartemen Mekarwangi Square Tower A Unit 1102, Cibaduyut',
                    'patokan_lokasi' => 'Lobby Tower A',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.9532,107.5921',
                    'status_pengantaran' => 'menunggu',
                    'catatan_kurir' => 'Konfirmasi pembayaran sebelum jalan.',
                ],
                'payments' => [],
            ],

            // Order 16 (Batch 14) - Sarah Amelia (STATUS LUNAS QRIS)
            [
                'nomor_order' => 'INV-20261115-0016',
                'open_po_id' => $po3->id,
                'customer_id' => $customers[6]->id, // Sarah
                'tanggal_pemesanan' => '2026-11-15',
                'diskon' => 0,
                'ongkos_kirim' => 20000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'diproses',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Brownies 3 box & Lasagna 2 box.',
                'items' => [
                    ['product_id' => $products['PRD-004']->id, 'qty' => 2], // 2 x 75.000 = 150.000
                    ['product_id' => $products['PRD-005']->id, 'qty' => 3], // 3 x 65.000 = 195.000
                    ['product_id' => $products['PRD-001']->id, 'qty' => 4], // 4 x 45.000 = 180.000
                ], // Subtotal: 525.000 + 20.000 = 545.000
                'delivery' => [
                    'tanggal_pengiriman' => '2026-11-21',
                    'jam_pengiriman' => '11:00',
                    'nama_penerima' => 'Sarah Amelia',
                    'no_telepon_penerima' => '08173344556',
                    'alamat_lengkap' => 'Buah Batu Square No. F-15, Bojongsoang, Bandung',
                    'patokan_lokasi' => 'Dekat pos satpam gerbang utama',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.9682,107.6334',
                    'status_pengantaran' => 'dijadwalkan',
                    'catatan_kurir' => 'Antar siang jam 11.',
                ],
                'payments' => [
                    ['tanggal' => '2026-11-15', 'jenis' => 'pelunasan', 'nominal' => 545000, 'metode' => 'QRIS'],
                ],
            ],

            // Order 17 (Batch 14) - Maya Kusuma (STATUS LUNAS)
            [
                'nomor_order' => 'INV-20261116-0017',
                'open_po_id' => $po3->id,
                'customer_id' => $customers[0]->id, // Maya
                'tanggal_pemesanan' => '2026-11-16',
                'diskon' => 20000,
                'ongkos_kirim' => 20000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'diproses',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Nasi liwet 10 porsi untuk kumpul arisan.',
                'items' => [
                    ['product_id' => $products['PRD-001']->id, 'qty' => 10], // 10 x 45.000 = 450.000
                    ['product_id' => $products['PRD-002']->id, 'qty' => 2],  // 2 x 160.000 = 320.000
                ], // Subtotal: 770.000 - 20.000 + 20.000 = 770.000
                'delivery' => [
                    'tanggal_pengiriman' => '2026-11-21',
                    'jam_pengiriman' => '11:30',
                    'nama_penerima' => 'Maya Kusuma',
                    'no_telepon_penerima' => '08122334455',
                    'alamat_lengkap' => 'Komplek Dago Regency Blok B No. 12, Coblong, Kota Bandung',
                    'patokan_lokasi' => 'Pagar abu-abu cluster Dago',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.8851,107.6135',
                    'status_pengantaran' => 'dijadwalkan',
                    'catatan_kurir' => 'Antar sebelum dzuhur.',
                ],
                'payments' => [
                    ['tanggal' => '2026-11-16', 'jenis' => 'pelunasan', 'nominal' => 770000, 'metode' => 'Transfer BCA'],
                ],
            ],

            // Order 18 (Batch 14) - Budi Prasetyo (STATUS LUNAS)
            [
                'nomor_order' => 'INV-20261116-0018',
                'open_po_id' => $po3->id,
                'customer_id' => $customers[1]->id, // Budi
                'tanggal_pemesanan' => '2026-11-16',
                'diskon' => 0,
                'ongkos_kirim' => 15000,
                'status_pembayaran' => 'lunas',
                'status_order' => 'diproses',
                'order_source' => 'admin',
                'keterangan_pesanan' => 'Ayam ungkep 2 ekor + Lasagna 2 box.',
                'items' => [
                    ['product_id' => $products['PRD-003']->id, 'qty' => 2], // 2 x 85.000 = 170.000
                    ['product_id' => $products['PRD-004']->id, 'qty' => 2], // 2 x 75.000 = 150.000
                ], // Subtotal: 320.000 + 15.000 = 335.000
                'delivery' => [
                    'tanggal_pengiriman' => '2026-11-21',
                    'jam_pengiriman' => '13:30',
                    'nama_penerima' => 'Budi Prasetyo',
                    'no_telepon_penerima' => '08139988776',
                    'alamat_lengkap' => 'Jl. Setiabudi Permai Kavling 45, Cidadap, Kota Bandung',
                    'patokan_lokasi' => 'Rumah tingkat dua samping minimarket',
                    'link_google_maps' => 'https://maps.google.com/?q=-6.8621,107.5951',
                    'status_pengantaran' => 'dijadwalkan',
                    'catatan_kurir' => 'Terkirim sore.',
                ],
                'payments' => [
                    ['tanggal' => '2026-11-16', 'jenis' => 'pelunasan', 'nominal' => 335000, 'metode' => 'QRIS'],
                ],
            ],
        ];

        foreach ($salesList as $s) {
            // Calculate subtotal from items
            $subtotal = 0;
            $itemsToCreate = [];

            foreach ($s['items'] as $itemData) {
                $pModel = Product::query()->findOrFail($itemData['product_id']);
                $itemSubtotal = $pModel->harga_jual * $itemData['qty'];
                $subtotal += $itemSubtotal;

                $itemsToCreate[] = [
                    'product_id' => $pModel->id,
                    'nama_produk_snapshot' => $pModel->nama_produk,
                    'jumlah_paket' => $itemData['qty'],
                    'harga_jual' => $pModel->harga_jual,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $totalTagihan = $subtotal - $s['diskon'] + $s['ongkos_kirim'];

            $sale = Sale::query()->create([
                'nomor_order' => $s['nomor_order'],
                'open_po_id' => $s['open_po_id'],
                'customer_id' => $s['customer_id'],
                'tanggal_pemesanan' => $s['tanggal_pemesanan'],
                'subtotal' => $subtotal,
                'diskon' => $s['diskon'],
                'ongkos_kirim' => $s['ongkos_kirim'],
                'total_tagihan' => $totalTagihan,
                'status_pembayaran' => $s['status_pembayaran'],
                'status_order' => $s['status_order'],
                'order_source' => $s['order_source'],
                'keterangan_pesanan' => $s['keterangan_pesanan'],
            ]);

            foreach ($itemsToCreate as $it) {
                $sale->items()->create($it);
            }

            // Create Delivery
            if (isset($s['delivery'])) {
                $sale->delivery()->create($s['delivery']);
            }

            // Create Payments
            if (isset($s['payments'])) {
                foreach ($s['payments'] as $pay) {
                    $sale->payments()->create([
                        'tanggal_bayar' => $pay['tanggal'],
                        'jenis_pembayaran' => $pay['jenis'],
                        'nominal_bayar' => $pay['nominal'],
                        'metode_pembayaran' => $pay['metode'],
                        'bukti_bayar' => null,
                        'status_verifikasi' => 'verified',
                        'catatan' => 'Pembayaran tercatat oleh kasir admin.',
                    ]);
                }
            }
        }
    }
}
