<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Dashboard Operasional</h1>
            <p class="text-xs text-slate-500 mt-1">Ringkasan performa finansial dan transaksi Open PO SORASA</p>
        </div>
        <div class="flex items-center gap-2">
            <a
                href="{{ route('admin.sales.pos') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold shadow-xs transition-colors"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Buka Kasir POS</span>
            </a>
        </div>
    </div>

    <!-- KPI Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Penjualan (Omzet) -->
        <x-card class="relative overflow-hidden border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Omzet</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1.5">
                        <x-money :value="$omzet" />
                    </p>
                </div>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>{{ $totalOrders }} transaksi pesanan</span>
                <span class="text-emerald-600 font-semibold">100% Tercatat</span>
            </div>
        </x-card>

        <!-- 2. Total Pembelian Bahan -->
        <x-card class="relative overflow-hidden border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Belanja Bahan</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1.5">
                        <x-money :value="$pembelian" />
                    </p>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-100 text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Habis pakai (Tanpa stok)</span>
                <span class="text-slate-600 font-medium">Biaya Masak</span>
            </div>
        </x-card>

        <!-- 3. Biaya Lain / Operasional -->
        <x-card class="relative overflow-hidden border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Biaya Operasional</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1.5">
                        <x-money :value="$biayaLain" />
                    </p>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-100 text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Gas, kemasan, tenaga kerja</span>
                <span class="text-slate-600 font-medium">Non-Bahan</span>
            </div>
        </x-card>

        <!-- 4. Surplus Usaha Sederhana -->
        <x-card @class([
            'relative overflow-hidden border-2',
            'border-emerald-300 bg-emerald-50/20' => $surplus >= 0,
            'border-rose-300 bg-rose-50/20' => $surplus < 0,
        ])>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider {{ $surplus >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                        Surplus Usaha
                    </p>
                    <p class="text-2xl font-extrabold mt-1.5 {{ $surplus >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                        <x-money :value="$surplus" :sign="true" />
                    </p>
                </div>
                <div class="p-2.5 rounded-xl {{ $surplus >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-emerald-100/80 flex items-center justify-between text-xs {{ $surplus >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                <span class="font-medium">Omzet - Belanja - Biaya</span>
                <span class="font-bold">{{ $surplus >= 0 ? 'SURPLUS' : 'DEFISIT' }}</span>
            </div>
        </x-card>
    </div>

    <!-- Secondary Row: Piutang & Status Batch Aktif -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Piutang Card -->
        <x-card title="Status Kas & Tagihan Piutang" description="Rekap pembayaran pesanan pelanggan">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 uppercase">Sudah Diterima Kas</span>
                        <div class="text-lg font-bold text-slate-900 mt-0.5">
                            <x-money :value="$totalTerbayar" />
                        </div>
                    </div>
                    <x-badge variant="emerald" :dot="true">Terverifikasi</x-badge>
                </div>

                <div class="flex items-center justify-between p-3.5 rounded-xl bg-rose-50/60 border border-rose-100">
                    <div>
                        <span class="text-xs font-semibold text-rose-700 uppercase">Total Piutang Belum Lunas</span>
                        <div class="text-lg font-bold text-rose-800 mt-0.5">
                            <x-money :value="$totalPiutang" />
                        </div>
                    </div>
                    <a href="{{ route('admin.payments') }}" class="text-xs font-semibold text-rose-700 hover:text-rose-900 underline">
                        Lihat Piutang →
                    </a>
                </div>
            </div>
        </x-card>

        <!-- Batch Aktif Card -->
        <x-card title="Batch Open PO Aktif" description="Informasi produksi batch yang sedang berjalan" class="lg:col-span-2">
            @if($activeOpenPo)
                <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <x-badge variant="amber" :dot="true">SEDANG BUKA</x-badge>
                            <span class="text-xs font-mono font-medium text-amber-900">{{ $activeOpenPo->kode_po }}</span>
                        </div>
                        <h4 class="text-base font-bold text-slate-900">{{ $activeOpenPo->nama_po }}</h4>
                        <p class="text-xs text-slate-600">
                            Periode Buka: {{ $activeOpenPo->tanggal_buka->format('d M') }} s/d {{ $activeOpenPo->tanggal_tutup->format('d M Y') }}
                        </p>
                    </div>

                    <div class="flex sm:flex-col items-center sm:items-end justify-between border-t sm:border-t-0 pt-3 sm:pt-0 border-amber-200/60">
                        <span class="text-xs text-slate-500">Jadwal Pengiriman:</span>
                        <span class="text-sm font-bold text-amber-900">{{ $activeOpenPo->tanggal_pengiriman->format('d F Y') }}</span>
                        <a href="{{ route('admin.open-po') }}" class="mt-2 text-xs font-semibold text-amber-800 hover:text-amber-950">
                            Detail Batch →
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-slate-400">
                    <p class="text-xs">Tidak ada batch Open PO berstatus buka saat ini.</p>
                    <a href="{{ route('admin.open-po') }}" class="mt-2 inline-block text-xs font-semibold text-amber-700 hover:underline">
                        + Buat Batch Open PO Baru
                    </a>
                </div>
            @endif
        </x-card>
    </div>
</div>
