<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Laporan Laba Rugi Sederhana</h1>
            <p class="text-xs text-slate-500 mt-1">Kalkulasi: Omzet Penjualan — Pembelian Bahan — Biaya Operasional Lain</p>
        </div>
        <button type="button" onclick="window.print()" class="px-3.5 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-semibold shadow-2xs">
            Cetak Laporan
        </button>
    </div>

    <x-card class="max-w-3xl mx-auto space-y-6">
        <div class="text-center pb-4 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-900">SORASA Kitchen & Pastry</h2>
            <p class="text-xs text-slate-500">Laporan Keuangan & Rekap Surplus Usaha Open PO</p>
        </div>

        <div class="space-y-4 text-sm">
            <!-- Omzet -->
            <div class="flex items-center justify-between py-2 border-b border-slate-100">
                <span class="font-semibold text-slate-900">1. Pendapatan Penjualan (Omzet)</span>
                <span class="font-bold text-slate-900"><x-money :value="$omzet" /></span>
            </div>

            <!-- Pembelian -->
            <div class="flex items-center justify-between py-2 border-b border-slate-100 pl-4">
                <span class="text-slate-600">2. Belanja Bahan Baku (Habis Pakai)</span>
                <span class="font-medium text-rose-700">- <x-money :value="$pembelian" /></span>
            </div>

            <!-- Biaya Lain -->
            <div class="flex items-center justify-between py-2 border-b border-slate-100 pl-4">
                <span class="text-slate-600">3. Biaya Operasional Lain (Gas, Kemasan, Tenaga, dll)</span>
                <span class="font-medium text-rose-700">- <x-money :value="$biayaLain" /></span>
            </div>

            <!-- Surplus Result -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-950 font-bold text-base mt-4">
                <span>Surplus Usaha Bersih</span>
                <span class="text-xl text-emerald-700"><x-money :value="$surplus" :sign="true" /></span>
            </div>
        </div>
    </x-card>
</div>
