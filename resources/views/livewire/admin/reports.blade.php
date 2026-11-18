<div class="space-y-6">
    <!-- Screen Header & Actions (Hidden on Print) -->
    <div class="no-print flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Laporan Laba Rugi Sederhana</h1>
            <p class="text-xs text-slate-500 mt-1">Kalkulasi tegas: <span class="font-bold text-slate-700">Omzet Penjualan — Pembelian Bahan Baku — Biaya Operasional Lain</span></p>
        </div>
        <div class="flex items-center gap-2">
            <button
                type="button"
                wire:click="exportCsv"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-2xs transition-colors"
            >
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export CSV (Excel)</span>
            </button>
            <button
                type="button"
                onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-2xs hover:shadow-xs transition-all active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Filter Bar (Hidden on Print) -->
    <div class="no-print bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 flex-1">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider shrink-0">Filter Laporan:</span>

            <!-- Filter Batch Open PO -->
            <select
                wire:model.live="selectedOpenPoId"
                class="px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-800 font-medium"
            >
                <option value="">Semua Periode Batch PO</option>
                @foreach($openPos as $po)
                    <option value="{{ $po->id }}">[{{ $po->kode_po }}] {{ $po->nama_po }}</option>
                @endforeach
            </select>

            <!-- Filter Preset Periode -->
            <select
                wire:model.live="period"
                class="px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-800 font-medium"
            >
                <option value="all">Semua Waktu</option>
                <option value="this_month">Bulan Ini</option>
                <option value="last_month">Bulan Lalu</option>
                <option value="custom">Rentang Tanggal Kustom</option>
            </select>

            <!-- Custom Date Range -->
            @if($period === 'custom')
                <div class="flex items-center gap-1.5">
                    <input
                        wire:model.live="startDate"
                        type="date"
                        class="px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-800"
                    >
                    <span class="text-xs text-slate-400">s/d</span>
                    <input
                        wire:model.live="endDate"
                        type="date"
                        class="px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-800"
                    >
                </div>
            @endif
        </div>

        @if($selectedOpenPoId || $period !== 'all' || $startDate || $endDate)
            <button
                type="button"
                wire:click="resetFilters"
                class="text-xs text-rose-600 hover:text-rose-700 font-medium hover:underline shrink-0"
            >
                Reset Filter
            </button>
        @endif
    </div>

    <!-- MAIN STATEMENT DOCUMENT (Print Ready) -->
    <div class="print-container bg-white p-6 sm:p-10 rounded-2xl border border-slate-200 shadow-2xs max-w-4xl mx-auto space-y-8">
        <!-- Document Kop Header -->
        <div class="border-b-2 border-slate-900 pb-5 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-600 text-white font-bold flex items-center justify-center text-sm shadow-2xs">
                        S
                    </div>
                    <span class="text-xl font-black tracking-tight text-slate-900">SORASA</span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-1">Sistem Manajemen Operasional & POS Open PO Makanan</p>
                <p class="text-[11px] text-slate-400">Dapur Kuliner & Pastry — Kebayoran Baru, Jakarta Selatan</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 rounded-lg text-xs font-bold uppercase tracking-wider border border-slate-200">
                    Laporan Laba Rugi Sederhana
                </span>
                <p class="text-xs font-bold text-slate-800 mt-2">Periode: <span class="text-amber-800">{{ $filterLabel }}</span></p>
                <p class="text-[11px] text-slate-400 mt-0.5">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} WIB</p>
            </div>
        </div>

        <!-- KPI Ringkasan Eksekutif -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
            <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/80">
                <p class="text-[11px] font-semibold text-amber-800 uppercase">Omzet Penjualan</p>
                <p class="text-lg font-black text-amber-950 mt-1 tabular-nums">@rupiah($totalOmzet)</p>
            </div>
            <div class="p-3.5 rounded-xl bg-rose-50/70 border border-rose-200/80">
                <p class="text-[11px] font-semibold text-rose-800 uppercase">Belanja Bahan</p>
                <p class="text-lg font-black text-rose-950 mt-1 tabular-nums">@rupiah($totalPembelian)</p>
            </div>
            <div class="p-3.5 rounded-xl bg-purple-50/70 border border-purple-200/80">
                <p class="text-[11px] font-semibold text-purple-800 uppercase">Biaya Operasional</p>
                <p class="text-lg font-black text-purple-950 mt-1 tabular-nums">@rupiah($totalBiayaLain)</p>
            </div>
            <div class="p-3.5 rounded-xl {{ $surplus >= 0 ? 'bg-emerald-50/80 border-emerald-300 text-emerald-950' : 'bg-rose-50 border-rose-300 text-rose-950' }} border-2">
                <p class="text-[11px] font-bold uppercase {{ $surplus >= 0 ? 'text-emerald-800' : 'text-rose-800' }}">Surplus Bersih</p>
                <p class="text-lg font-black mt-1 tabular-nums {{ $surplus >= 0 ? 'text-emerald-700' : 'text-rose-700' }}"><x-money :value="$surplus" :sign="true" /></p>
            </div>
        </div>

        <!-- 1. PENDAPATAN PENJUALAN (OMZET) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-amber-600 text-white flex items-center justify-center text-[10px]">1</span>
                    Pendapatan Penjualan (Omzet)
                </h3>
                <span class="text-xs font-medium text-slate-500">{{ $orderCount }} transaksi pesanan</span>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1 text-slate-600">
                    <span class="pl-3">• Subtotal Penjualan Menu Makanan (Bruto)</span>
                    <span class="font-bold text-slate-800 tabular-nums">@rupiah($totalSubtotal)</span>
                </div>
                <div class="flex justify-between py-1 text-slate-600">
                    <span class="pl-3">• Pendapatan Ongkos Kirim (+)</span>
                    <span class="font-medium text-slate-700 tabular-nums">+ @rupiah($totalOngkir)</span>
                </div>
                <div class="flex justify-between py-1 text-slate-600">
                    <span class="pl-3">• Potongan Diskon Pesanan (-)</span>
                    <span class="font-medium text-rose-600 tabular-nums">- @rupiah($totalDiskon)</span>
                </div>
                <div class="flex justify-between py-2.5 px-3 rounded-xl bg-amber-50/60 border border-amber-200/80 font-bold text-slate-900 mt-1">
                    <span class="uppercase tracking-wide text-amber-900">Total Omzet Bersih Penjualan</span>
                    <span class="text-sm font-black text-amber-950 tabular-nums">@rupiah($totalOmzet)</span>
                </div>
            </div>

            <!-- Rincian Produk Terjual (Detail Tambahan) -->
            @if($menuBreakdown->isNotEmpty())
                <div class="mt-2 pl-3">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kontribusi Menu Terjual:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
                        @foreach($menuBreakdown as $mb)
                            <div class="flex justify-between py-1 px-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                <span class="font-medium text-slate-700 truncate mr-2">{{ $mb->nama_produk_snapshot }} ({{ $mb->total_qty }}x)</span>
                                <span class="font-bold text-slate-900 shrink-0 tabular-nums">@rupiah($mb->total_nominal)</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- 2. PEMBELIAN BAHAN BAKU (COGS / HABIS PAKAI) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-rose-600 text-white flex items-center justify-center text-[10px]">2</span>
                    Pembelian Bahan Baku (Habis Pakai Langsung)
                </h3>
                <span class="text-xs font-medium text-slate-500">{{ $purchasesList->count() }} transaksi belanja</span>
            </div>
            <div class="space-y-2 text-xs">
                @forelse($purchasesList->take(6) as $pur)
                    <div class="flex justify-between py-1 text-slate-600">
                        <span class="pl-3">• {{ $pur->nama_barang }} ({{ $pur->jumlah }} {{ $pur->satuan }} @ @rupiah($pur->harga_satuan)) — <span class="text-[11px] text-slate-400">{{ $pur->nama_toko_supplier }}</span></span>
                        <span class="font-semibold text-slate-800 tabular-nums">@rupiah($pur->total_pembelian)</span>
                    </div>
                @empty
                    <p class="text-slate-400 italic pl-3">Tidak ada catatan belanja bahan pada periode ini.</p>
                @endforelse

                @if($purchasesList->count() > 6)
                    <p class="text-[11px] text-slate-400 pl-3 italic">+ {{ $purchasesList->count() - 6 }} item belanja lainnya tercatat di sistem</p>
                @endif

                <div class="flex justify-between py-2.5 px-3 rounded-xl bg-rose-50/60 border border-rose-200/80 font-bold text-slate-900 mt-1">
                    <span class="uppercase tracking-wide text-rose-900">Total Pembelian Bahan Baku</span>
                    <span class="text-sm font-black text-rose-700 tabular-nums">- @rupiah($totalPembelian)</span>
                </div>
            </div>
        </div>

        <!-- 3. BIAYA OPERASIONAL LAIN -->
        <div class="space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-purple-600 text-white flex items-center justify-center text-[10px]">3</span>
                    Biaya Operasional Dapur & Kemasan Lain
                </h3>
                <span class="text-xs font-medium text-slate-500">{{ $expensesList->count() }} pengeluaran</span>
            </div>
            <div class="space-y-2 text-xs">
                @forelse($expensesByCategory as $category => $nom)
                    <div class="flex justify-between py-1 text-slate-600">
                        <span class="pl-3">• Kategori: <span class="font-semibold text-slate-800">{{ $category }}</span></span>
                        <span class="font-semibold text-slate-800 tabular-nums">@rupiah($nom)</span>
                    </div>
                @empty
                    <p class="text-slate-400 italic pl-3">Tidak ada pengeluaran operasional lain pada periode ini.</p>
                @endforelse

                <div class="flex justify-between py-2.5 px-3 rounded-xl bg-purple-50/60 border border-purple-200/80 font-bold text-slate-900 mt-1">
                    <span class="uppercase tracking-wide text-purple-900">Total Biaya Operasional Lain</span>
                    <span class="text-sm font-black text-purple-800 tabular-nums">- @rupiah($totalBiayaLain)</span>
                </div>
            </div>
        </div>

        <!-- 4. SURPLUS USAHA BERSIH -->
        <div class="p-5 rounded-2xl {{ $surplus >= 0 ? 'bg-emerald-50 border-2 border-emerald-300' : 'bg-rose-50 border-2 border-rose-300' }} shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-wider {{ $surplus >= 0 ? 'text-emerald-800' : 'text-rose-800' }}">
                        4. Surplus Usaha Sederhana (Hasil Akhir Bersih)
                    </span>
                    <p class="text-xs {{ $surplus >= 0 ? 'text-emerald-700' : 'text-rose-700' }} mt-0.5">
                        Rumus: Omzet Bersih (@rupiah($totalOmzet)) — Belanja Bahan (@rupiah($totalPembelian)) — Biaya Lain (@rupiah($totalBiayaLain))
                    </p>
                </div>
                <div class="text-left sm:text-right">
                    <div class="text-2xl font-black tabular-nums {{ $surplus >= 0 ? 'text-emerald-800' : 'text-rose-800' }}">
                        <x-money :value="$surplus" :sign="true" />
                    </div>
                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold {{ $surplus >= 0 ? 'bg-emerald-200/80 text-emerald-950' : 'bg-rose-200 text-rose-950' }} mt-0.5">
                        {{ $surplus >= 0 ? "+{$surplusMargin}% Margin Keuntungan" : 'DEFISIT OPERASIONAL' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 5. STATUS ARUS KAS & TRACKING PIUTANG -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
            <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">5. Catatan Arus Kas & Piutang Pelanggan:</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                <div class="flex justify-between items-center p-2 rounded-lg bg-white border border-slate-100">
                    <span class="text-slate-600">Total Kas Masuk Terverifikasi:</span>
                    <span class="font-bold text-emerald-700 tabular-nums">@rupiah($totalPaid)</span>
                </div>
                <div class="flex justify-between items-center p-2 rounded-lg bg-white border border-slate-100">
                    <span class="text-slate-600">Sisa Piutang Belum Tertagih:</span>
                    <span class="font-bold text-rose-700 tabular-nums">@rupiah($totalPiutang)</span>
                </div>
            </div>
        </div>

        <!-- Tanda Tangan / Otorisasi Laporan (Siap Cetak) -->
        <div class="pt-8 grid grid-cols-2 gap-8 text-center text-xs text-slate-600 border-t border-slate-200">
            <div>
                <p class="font-medium text-slate-500">Dibuat & Diverifikasi Oleh,</p>
                <div class="h-16"></div>
                <p class="font-bold text-slate-900 underline">( Admin / Kasir SORASA )</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Operasional Dapur</p>
            </div>
            <div>
                <p class="font-medium text-slate-500">Mengetahui & Menyetujui,</p>
                <div class="h-16"></div>
                <p class="font-bold text-slate-900 underline">( Pemilik Usaha / Owner )</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Owner SORASA Kitchen</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide all layout navigation, header, and buttons */
    aside,
    header,
    .no-print,
    nav {
        display: none !important;
    }

    body {
        background-color: #ffffff !important;
        font-size: 11pt !important;
        color: #0f172a !important;
    }

    main {
        padding: 0 !important;
        margin: 0 !important;
    }

    .print-container {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
    }

    /* Prevent awkward page breaks */
    .print-container > div {
        page-break-inside: avoid;
    }
}
</style>
