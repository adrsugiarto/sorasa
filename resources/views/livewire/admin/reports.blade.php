<div class="space-y-5">
    <!-- Screen Header & Actions (Hidden on Print) -->
    <div class="no-print flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Laporan Laba Rugi Sederhana</h1>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Kalkulasi: <span class="font-medium text-slate-700 dark:text-gray-300">Omzet Penjualan — Pembelian Bahan Baku — Biaya Operasional Lain</span></p>
        </div>
        <div class="flex items-center gap-2">
            <button
                type="button"
                wire:click="exportCsv"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-750 text-slate-700 dark:text-gray-300 text-xs font-medium shadow-xs transition-colors cursor-pointer"
            >
                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export CSV (Excel)</span>
            </button>
            <button
                type="button"
                onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium shadow-xs transition-colors cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Filter Bar (Hidden on Print) -->
    <div class="no-print bg-white dark:bg-gray-900 p-3 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 flex-1">
            <span class="text-[11px] font-semibold text-slate-500 dark:text-gray-400 uppercase tracking-wider shrink-0">Filter Laporan:</span>

            <!-- Filter Batch Open PO -->
            <select
                wire:model.live="selectedOpenPoId"
                class="px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800 dark:text-white font-medium"
            >
                <option value="">Semua Periode Batch PO</option>
                @foreach($openPos as $po)
                    <option value="{{ $po->id }}">[{{ $po->kode_po }}] {{ $po->nama_po }}</option>
                @endforeach
            </select>

            <!-- Filter Preset Periode -->
            <select
                wire:model.live="period"
                class="px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800 dark:text-white font-medium"
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
                        class="px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 text-slate-800 dark:text-white"
                    >
                    <span class="text-xs text-slate-400">s/d</span>
                    <input
                        wire:model.live="endDate"
                        type="date"
                        class="px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 text-slate-800 dark:text-white"
                    >
                </div>
            @endif
        </div>

        @if($selectedOpenPoId || $period !== 'all' || $startDate || $endDate)
            <button
                type="button"
                wire:click="resetFilters"
                class="text-xs text-rose-600 hover:text-rose-700 font-medium hover:underline shrink-0 cursor-pointer"
            >
                Reset Filter
            </button>
        @endif
    </div>

    <!-- MAIN STATEMENT DOCUMENT (Print Ready & Dark Mode Friendly) -->
    <div class="print-container bg-white dark:bg-gray-900 p-6 sm:p-8 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs max-w-4xl mx-auto space-y-6 text-slate-800 dark:text-slate-200 print:bg-white print:text-slate-900 print:border-none print:shadow-none">
        <!-- Document Kop Header -->
        <div class="border-b-2 border-slate-900 dark:border-gray-700 print:border-slate-900 pb-4 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-md bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-xs">
                        S
                    </div>
                    <span class="text-lg font-bold tracking-tight text-slate-900 dark:text-white print:text-slate-900">SORASA</span>
                </div>
                <p class="text-xs text-slate-500 dark:text-gray-400 print:text-slate-500 font-medium mt-1">Sistem Manajemen Operasional & POS Open PO Makanan</p>
                <p class="text-[11px] text-slate-400 dark:text-gray-500 print:text-slate-400">Dapur Kuliner & Pastry — Bandung, Jawa Barat</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="inline-block px-2.5 py-0.5 bg-slate-100 dark:bg-gray-800 print:bg-slate-100 text-slate-800 dark:text-gray-200 print:text-slate-800 rounded-md text-[11px] font-semibold uppercase tracking-wider border border-slate-200 dark:border-gray-700 print:border-slate-200">
                    Laporan Laba Rugi Sederhana
                </span>
                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 print:text-slate-800 mt-1.5">Periode: <span class="text-blue-600 dark:text-blue-400 print:text-blue-600">{{ $filterLabel }}</span></p>
                <p class="text-[11px] text-slate-400 dark:text-gray-500 print:text-slate-400 mt-0.5">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} WIB</p>
            </div>
        </div>

        <!-- KPI Ringkasan Eksekutif -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
            <div class="p-3 rounded-md bg-blue-50/60 dark:bg-blue-950/30 print:bg-blue-50 border border-blue-200 dark:border-blue-900/60 print:border-blue-200">
                <p class="text-[11px] font-semibold text-blue-700 dark:text-blue-300 print:text-blue-700 uppercase">Omzet Penjualan</p>
                <p class="text-base font-bold text-slate-900 dark:text-white print:text-slate-900 mt-1 tabular-nums">@rupiah($totalOmzet)</p>
            </div>
            <div class="p-3 rounded-md bg-rose-50/60 dark:bg-rose-950/30 print:bg-rose-50 border border-rose-200 dark:border-rose-900/60 print:border-rose-200">
                <p class="text-[11px] font-semibold text-rose-700 dark:text-rose-300 print:text-rose-700 uppercase">Belanja Bahan</p>
                <p class="text-base font-bold text-slate-900 dark:text-white print:text-slate-900 mt-1 tabular-nums">@rupiah($totalPembelian)</p>
            </div>
            <div class="p-3 rounded-md bg-slate-50 dark:bg-gray-800/60 print:bg-slate-50 border border-slate-200 dark:border-gray-700 print:border-slate-200">
                <p class="text-[11px] font-semibold text-slate-600 dark:text-gray-400 print:text-slate-600 uppercase">Biaya Operasional</p>
                <p class="text-base font-bold text-slate-900 dark:text-white print:text-slate-900 mt-1 tabular-nums">@rupiah($totalBiayaLain)</p>
            </div>
            <div class="p-3 rounded-md {{ $surplus >= 0 ? 'bg-emerald-50/70 dark:bg-emerald-950/30 print:bg-emerald-50 border-emerald-300 dark:border-emerald-800/80' : 'bg-rose-50/70 dark:bg-rose-950/30 print:bg-rose-50 border-rose-300 dark:border-rose-800/80' }} border">
                <p class="text-[11px] font-semibold uppercase {{ $surplus >= 0 ? 'text-emerald-700 dark:text-emerald-300 print:text-emerald-700' : 'text-rose-700 dark:text-rose-300 print:text-rose-700' }}">Surplus Bersih</p>
                <p class="text-base font-bold mt-1 tabular-nums {{ $surplus >= 0 ? 'text-emerald-600 dark:text-emerald-400 print:text-emerald-600' : 'text-rose-600 dark:text-rose-400 print:text-rose-600' }}"><x-money :value="$surplus" :sign="true" /></p>
            </div>
        </div>

        <!-- 1. PENDAPATAN PENJUALAN (OMZET) -->
        <div class="space-y-2.5">
            <div class="flex items-center justify-between pb-1.5 border-b border-slate-200 dark:border-gray-800 print:border-slate-200">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-900 dark:text-white print:text-slate-900 flex items-center gap-1.5">
                    <span class="w-4 h-4 rounded bg-blue-600 text-white flex items-center justify-center text-[10px]">1</span>
                    Pendapatan Penjualan (Omzet)
                </h3>
                <span class="text-xs text-slate-500 dark:text-gray-400 print:text-slate-500 tabular-nums">{{ $orderCount }} transaksi pesanan</span>
            </div>
            <div class="space-y-1 text-xs">
                <div class="flex justify-between py-1 text-slate-600 dark:text-gray-400 print:text-slate-600">
                    <span class="pl-3">• Subtotal Penjualan Menu Makanan (Bruto)</span>
                    <span class="font-medium text-slate-800 dark:text-gray-200 print:text-slate-800 tabular-nums">@rupiah($totalSubtotal)</span>
                </div>
                <div class="flex justify-between py-1 text-slate-600 dark:text-gray-400 print:text-slate-600">
                    <span class="pl-3">• Pendapatan Ongkos Kirim (+)</span>
                    <span class="font-medium text-slate-700 dark:text-gray-300 print:text-slate-700 tabular-nums">+ @rupiah($totalOngkir)</span>
                </div>
                <div class="flex justify-between py-1 text-slate-600 dark:text-gray-400 print:text-slate-600">
                    <span class="pl-3">• Potongan Diskon Pesanan (-)</span>
                    <span class="font-medium text-rose-600 dark:text-rose-400 print:text-rose-600 tabular-nums">- @rupiah($totalDiskon)</span>
                </div>
                <div class="flex justify-between py-2 px-3 rounded-md bg-blue-50/60 dark:bg-blue-950/30 print:bg-blue-50 border border-blue-200 dark:border-blue-900/60 print:border-blue-200 font-semibold text-slate-900 dark:text-white print:text-slate-900 mt-1">
                    <span class="uppercase tracking-wide text-blue-900 dark:text-blue-300 print:text-blue-900 text-xs">Total Omzet Bersih Penjualan</span>
                    <span class="text-xs font-bold text-blue-700 dark:text-blue-300 print:text-blue-700 tabular-nums">@rupiah($totalOmzet)</span>
                </div>
            </div>

            <!-- Rincian Produk Terjual -->
            @if($menuBreakdown->isNotEmpty())
                <div class="mt-2 pl-3">
                    <p class="text-[11px] font-semibold text-slate-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Kontribusi Menu Terjual:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
                        @foreach($menuBreakdown as $mb)
                            <div class="flex justify-between py-1 px-2.5 rounded-md bg-slate-50 dark:bg-gray-800/60 print:bg-slate-50 border border-slate-100 dark:border-gray-800 print:border-slate-100">
                                <span class="font-medium text-slate-700 dark:text-gray-300 print:text-slate-700 truncate mr-2">{{ $mb->nama_produk_snapshot }} ({{ $mb->total_qty }}x)</span>
                                <span class="font-semibold text-slate-900 dark:text-white print:text-slate-900 shrink-0 tabular-nums">@rupiah($mb->total_nominal)</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- 2. PEMBELIAN BAHAN BAKU -->
        <div class="space-y-2.5">
            <div class="flex items-center justify-between pb-1.5 border-b border-slate-200 dark:border-gray-800 print:border-slate-200">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-900 dark:text-white print:text-slate-900 flex items-center gap-1.5">
                    <span class="w-4 h-4 rounded bg-rose-600 text-white flex items-center justify-center text-[10px]">2</span>
                    Pembelian Bahan Baku (Habis Pakai Langsung)
                </h3>
                <span class="text-xs text-slate-500 dark:text-gray-400 print:text-slate-500 tabular-nums">{{ $purchasesList->count() }} transaksi belanja</span>
            </div>
            <div class="space-y-1 text-xs">
                @forelse($purchasesList->take(6) as $pur)
                    <div class="flex justify-between py-1 text-slate-600 dark:text-gray-400 print:text-slate-600">
                        <span class="pl-3">• {{ $pur->nama_barang }} ({{ $pur->jumlah }} {{ $pur->satuan }} @ @rupiah($pur->harga_satuan)) — <span class="text-[11px] text-slate-400 dark:text-gray-500">{{ $pur->nama_toko_supplier }}</span></span>
                        <span class="font-medium text-slate-800 dark:text-gray-200 print:text-slate-800 tabular-nums">@rupiah($pur->total_pembelian)</span>
                    </div>
                @empty
                    <p class="text-slate-400 dark:text-gray-500 italic pl-3">Tidak ada catatan belanja bahan pada periode ini.</p>
                @endforelse

                @if($purchasesList->count() > 6)
                    <p class="text-[11px] text-slate-400 dark:text-gray-500 pl-3 italic">+ {{ $purchasesList->count() - 6 }} item belanja lainnya tercatat di sistem</p>
                @endif

                <div class="flex justify-between py-2 px-3 rounded-md bg-rose-50/60 dark:bg-rose-950/30 print:bg-rose-50 border border-rose-200 dark:border-rose-900/60 print:border-rose-200 font-semibold text-slate-900 dark:text-white print:text-slate-900 mt-1">
                    <span class="uppercase tracking-wide text-rose-900 dark:text-rose-300 print:text-rose-900 text-xs">Total Pembelian Bahan Baku</span>
                    <span class="text-xs font-bold text-rose-600 dark:text-rose-400 print:text-rose-600 tabular-nums">- @rupiah($totalPembelian)</span>
                </div>
            </div>
        </div>

        <!-- 3. BIAYA OPERASIONAL LAIN -->
        <div class="space-y-2.5">
            <div class="flex items-center justify-between pb-1.5 border-b border-slate-200 dark:border-gray-800 print:border-slate-200">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-900 dark:text-white print:text-slate-900 flex items-center gap-1.5">
                    <span class="w-4 h-4 rounded bg-slate-700 text-white flex items-center justify-center text-[10px]">3</span>
                    Biaya Operasional Dapur & Kemasan Lain
                </h3>
                <span class="text-xs text-slate-500 dark:text-gray-400 print:text-slate-500 tabular-nums">{{ $expensesList->count() }} pengeluaran</span>
            </div>
            <div class="space-y-1 text-xs">
                @forelse($expensesByCategory as $category => $nom)
                    <div class="flex justify-between py-1 text-slate-600 dark:text-gray-400 print:text-slate-600">
                        <span class="pl-3">• Kategori: <span class="font-medium text-slate-800 dark:text-slate-200 print:text-slate-800">{{ $category }}</span></span>
                        <span class="font-medium text-slate-800 dark:text-slate-200 print:text-slate-800 tabular-nums">@rupiah($nom)</span>
                    </div>
                @empty
                    <p class="text-slate-400 dark:text-gray-500 italic pl-3">Tidak ada pengeluaran operasional lain pada periode ini.</p>
                @endforelse

                <div class="flex justify-between py-2 px-3 rounded-md bg-slate-50 dark:bg-gray-800/60 print:bg-slate-50 border border-slate-200 dark:border-gray-700 print:border-slate-200 font-semibold text-slate-900 dark:text-white print:text-slate-900 mt-1">
                    <span class="uppercase tracking-wide text-slate-700 dark:text-gray-300 print:text-slate-700 text-xs">Total Biaya Operasional Lain</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 print:text-slate-800 tabular-nums">- @rupiah($totalBiayaLain)</span>
                </div>
            </div>
        </div>

        <!-- 4. SURPLUS USAHA BERSIH -->
        <div class="p-4 rounded-md {{ $surplus >= 0 ? 'bg-emerald-50/70 dark:bg-emerald-950/30 print:bg-emerald-50 border border-emerald-300 dark:border-emerald-800/80' : 'bg-rose-50/70 dark:bg-rose-950/30 print:bg-rose-50 border border-rose-300 dark:border-rose-800/80' }} shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider {{ $surplus >= 0 ? 'text-emerald-800 dark:text-emerald-300' : 'text-rose-800 dark:text-rose-300' }}">
                        4. Surplus Usaha Sederhana (Hasil Akhir Bersih)
                    </span>
                    <p class="text-[11px] {{ $surplus >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }} mt-0.5">
                        Rumus: Omzet Bersih (@rupiah($totalOmzet)) — Belanja Bahan (@rupiah($totalPembelian)) — Biaya Lain (@rupiah($totalBiayaLain))
                    </p>
                </div>
                <div class="text-left sm:text-right">
                    <div class="text-xl font-bold tabular-nums {{ $surplus >= 0 ? 'text-emerald-700 dark:text-emerald-300 print:text-emerald-700' : 'text-rose-700 dark:text-rose-300 print:text-rose-700' }}">
                        <x-money :value="$surplus" :sign="true" />
                    </div>
                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold {{ $surplus >= 0 ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200' : 'bg-rose-100 dark:bg-rose-900/40 text-rose-800 dark:text-rose-200' }} mt-0.5">
                        {{ $surplus >= 0 ? "+{$surplusMargin}% Margin Keuntungan" : 'DEFISIT OPERASIONAL' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 5. STATUS ARUS KAS & TRACKING PIUTANG -->
        <div class="p-3.5 rounded-md bg-slate-50 dark:bg-gray-800/60 print:bg-slate-50 border border-slate-200 dark:border-gray-700 print:border-slate-200 space-y-2 text-xs">
            <h4 class="font-semibold text-slate-800 dark:text-slate-200 print:text-slate-800 uppercase tracking-wider text-[11px]">5. Catatan Arus Kas & Piutang Pelanggan:</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                <div class="flex justify-between items-center p-2 rounded-md bg-white dark:bg-gray-900 print:bg-white border border-slate-200 dark:border-gray-800 print:border-slate-200">
                    <span class="text-slate-600 dark:text-gray-400 print:text-slate-600">Total Kas Masuk Terverifikasi:</span>
                    <span class="font-semibold text-emerald-600 dark:text-emerald-400 print:text-emerald-600 tabular-nums">@rupiah($totalPaid)</span>
                </div>
                <div class="flex justify-between items-center p-2 rounded-md bg-white dark:bg-gray-900 print:bg-white border border-slate-200 dark:border-gray-800 print:border-slate-200">
                    <span class="text-slate-600 dark:text-gray-400 print:text-slate-600">Sisa Piutang Belum Tertagih:</span>
                    <span class="font-semibold text-rose-600 dark:text-rose-400 print:text-rose-600 tabular-nums">@rupiah($totalPiutang)</span>
                </div>
            </div>
        </div>

        <!-- Tanda Tangan / Otorisasi Laporan (Siap Cetak) -->
        <div class="pt-6 grid grid-cols-2 gap-8 text-center text-xs text-slate-600 dark:text-gray-400 print:text-slate-600 border-t border-slate-200 dark:border-gray-800 print:border-slate-200">
            <div>
                <p class="font-medium text-slate-500 dark:text-gray-400">Dibuat & Diverifikasi Oleh,</p>
                <div class="h-14"></div>
                <p class="font-semibold text-slate-900 dark:text-white print:text-slate-900 underline">( Admin / Kasir SORASA )</p>
                <p class="text-[10px] text-slate-400 dark:text-gray-500 mt-0.5">Operasional Dapur</p>
            </div>
            <div>
                <p class="font-medium text-slate-500 dark:text-gray-400">Mengetahui & Menyetujui,</p>
                <div class="h-14"></div>
                <p class="font-semibold text-slate-900 dark:text-white print:text-slate-900 underline">( Pemilik Usaha / Owner )</p>
                <p class="text-[10px] text-slate-400 dark:text-gray-500 mt-0.5">Owner SORASA Kitchen</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
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

    .print-container > div {
        page-break-inside: avoid;
    }
}
</style>
