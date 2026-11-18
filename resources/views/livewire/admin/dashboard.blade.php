<div class="space-y-6">
    <!-- Page Header & Filter Toolbar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Dashboard Operasional & Analitik</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau performa omzet, biaya belanja bahan, surplus usaha, dan efisiensi pesanan Open PO</p>
        </div>
        <div class="flex items-center gap-2">
            <a
                href="{{ route('admin.reports') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-2xs transition-colors"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Laporan Laba Rugi</span>
            </a>
            <a
                href="{{ route('admin.sales.pos') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-2xs hover:shadow-xs transition-all active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Kasir POS</span>
            </a>
        </div>
    </div>

    <!-- Active Open PO Notice Banner -->
    @if($activeOpenPo)
        <div class="p-4 rounded-2xl bg-gradient-to-r from-amber-500/10 via-amber-50 to-amber-50/50 border border-amber-200/90 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <x-badge variant="amber" :dot="true">SEDANG BUKA (OPEN PO)</x-badge>
                        <span class="text-xs font-mono font-bold text-amber-900">[{{ $activeOpenPo->kode_po }}]</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mt-0.5">{{ $activeOpenPo->nama_po }}</h3>
                    <p class="text-xs text-slate-600 mt-0.5">
                        Buka: <span class="font-medium text-slate-800">{{ $activeOpenPo->tanggal_buka->format('d M') }} s/d {{ $activeOpenPo->tanggal_tutup->format('d M Y') }}</span> • Jadwal Kirim: <span class="font-bold text-amber-900">{{ $activeOpenPo->tanggal_pengiriman->format('d F Y') }}</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                <a
                    href="{{ route('admin.sales.pos') }}"
                    class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-2xs transition-colors"
                >
                    + Input Pesanan
                </a>
                <a
                    href="{{ route('admin.open-po') }}"
                    class="px-3 py-1.5 bg-white border border-amber-300 hover:bg-amber-50 text-amber-900 text-xs font-semibold rounded-xl transition-colors"
                >
                    Lihat Rekap Dapur
                </a>
            </div>
        </div>
    @endif

    <!-- Global Reactive Filter Toolbar -->
    <div class="bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 flex-1">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider shrink-0 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter:
            </span>

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

    <!-- KPI Summary Grid (4 Kartu Inti) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Penjualan (Omzet) -->
        <x-card class="relative overflow-hidden border-slate-200/90 shadow-2xs">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Omzet Penjualan</p>
                    <p class="text-2xl font-black text-slate-900 mt-1.5 tabular-nums">
                        @rupiah($totalOmzet)
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="font-medium text-slate-700">{{ $totalOrders }} pesanan masuk</span>
                <span class="text-amber-700 font-bold">Omzet Kotor</span>
            </div>
        </x-card>

        <!-- 2. Belanja Bahan Baku (Habis Pakai) -->
        <x-card class="relative overflow-hidden border-slate-200/90 shadow-2xs">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pembelian (Bahan)</p>
                    <p class="text-2xl font-black text-rose-600 mt-1.5 tabular-nums">
                        @rupiah($totalPembelian)
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Habis pakai (Dapur)</span>
                <span class="text-rose-700 font-bold">Biaya Langsung</span>
            </div>
        </x-card>

        <!-- 3. Biaya Lain / Operasional -->
        <x-card class="relative overflow-hidden border-slate-200/90 shadow-2xs">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Biaya Lain (Operasional)</p>
                    <p class="text-2xl font-black text-purple-700 mt-1.5 tabular-nums">
                        @rupiah($totalBiayaLain)
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 border border-purple-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Gas, kemasan, tenaga kerja</span>
                <span class="text-purple-700 font-bold">Non-Bahan</span>
            </div>
        </x-card>

        <!-- 4. Surplus Usaha Sederhana -->
        <x-card @class([
            'relative overflow-hidden border-2 shadow-2xs',
            'border-emerald-300 bg-emerald-50/20' => $surplus >= 0,
            'border-rose-300 bg-rose-50/20' => $surplus < 0,
        ])>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider {{ $surplus >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                        Surplus Usaha Sederhana
                    </p>
                    <p class="text-2xl font-black mt-1.5 tabular-nums {{ $surplus >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                        <x-money :value="$surplus" :sign="true" />
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl {{ $surplus >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }} flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs {{ $surplus >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                <span class="font-bold">Omzet — Pembelian — Biaya</span>
                <span class="font-extrabold">{{ $surplus >= 0 ? "+{$surplusMargin}% Margin" : 'DEFISIT' }}</span>
            </div>
        </x-card>
    </div>

    <!-- Secondary Row: Arus Kas Masuk vs Piutang Belum Lunas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-emerald-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Kas Diterima (Lunas & DP)</span>
                <div class="text-xl font-black text-emerald-700 mt-1 tabular-nums">
                    @rupiah($totalPaid)
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5">Sudah masuk rekening/kas usaha</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-rose-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Total Tagihan Belum Lunas (Piutang)</span>
                <div class="text-xl font-black text-rose-700 mt-1 tabular-nums">
                    @rupiah($totalPiutang)
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5">Sisa tagihan yang harus ditagih ke pemesan</p>
            </div>
            <a
                href="{{ route('admin.payments') }}"
                class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-colors"
            >
                Kelola Piutang →
            </a>
        </div>
    </div>

    <!-- ApexCharts Analytics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 1. Perbandingan Finansial Bar Chart -->
        <x-card class="lg:col-span-2" title="Perbandingan Finansial Usaha" description="Perbandingan Omzet Penjualan vs Pembelian Bahan vs Biaya Operasional vs Surplus Bersih">
            <div
                x-data="financialBarChart(@js($chartFinancial))"
                wire:ignore
                class="w-full mt-2"
            >
                <div x-ref="chartContainer" class="w-full"></div>
            </div>
        </x-card>

        <!-- 2. Tren Omzet & Pesanan Chart -->
        <x-card title="Tren Penjualan & Pesanan" description="{{ $selectedOpenPoId ? 'Grafik pergerakan omzet harian pada batch ini' : 'Grafik omzet & jumlah order antar Batch Open PO' }}">
            <div
                x-data="trendAreaChart(@js($chartTrend))"
                wire:ignore
                class="w-full mt-2"
            >
                <div x-ref="trendContainer" class="w-full"></div>
            </div>
        </x-card>
    </div>

    <!-- Bottom Row: Produk Terlaris & Status Pesanan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tabel Menu Terlaris (Top Selling) -->
        <x-card class="lg:col-span-2" noPadding="true">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">5 Menu Terlaris (Top Selling)</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Berdasarkan total porsi paket terjual dan kontribusi omzet</p>
                </div>
                <a href="{{ route('admin.products') }}" class="text-xs font-bold text-amber-700 hover:text-amber-800 hover:underline">
                    Kelola Produk →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3">Peringkat</th>
                            <th class="px-5 py-3">Nama Menu</th>
                            <th class="px-5 py-3 text-right">Paket Terjual</th>
                            <th class="px-5 py-3 text-right">Kontribusi Omzet</th>
                            <th class="px-5 py-3 text-right">% Omzet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($topProducts as $idx => $prod)
                            @php
                                $percent = $totalOmzet > 0 ? round(((float)$prod->total_omzet / $totalOmzet) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="w-6 h-6 rounded-full inline-flex items-center justify-center font-bold text-xs {{ $idx === 0 ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $idx + 1 }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-900">{{ $prod->nama_produk_snapshot }}</span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-right font-bold text-slate-800 tabular-nums">
                                    {{ $prod->total_qty }} paket
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-right font-black text-amber-950 tabular-nums">
                                    @rupiah($prod->total_omzet)
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200 tabular-nums">
                                        {{ $percent }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                    Belum ada transaksi produk pada filter terpilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <!-- Status Alur Pesanan -->
        <x-card title="Alur Status Pesanan" description="Monitoring tahapan pesanan aktif di dapur">
            <div class="space-y-3 mt-1">
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                        <span class="text-xs font-semibold text-slate-700">Menunggu Konfirmasi</span>
                    </div>
                    <span class="text-sm font-bold text-slate-900 tabular-nums">{{ $ordersByStatus['menunggu_konfirmasi'] }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50/60 border border-amber-100">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span class="text-xs font-semibold text-amber-900">Diproses Produksi Dapur</span>
                    </div>
                    <span class="text-sm font-black text-amber-900 tabular-nums">{{ $ordersByStatus['diproses'] }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50/60 border border-blue-100">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-semibold text-blue-900">Siap Dikirim</span>
                    </div>
                    <span class="text-sm font-black text-blue-900 tabular-nums">{{ $ordersByStatus['siap_kirim'] }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/60 border border-emerald-100">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-semibold text-emerald-900">Pesanan Selesai</span>
                    </div>
                    <span class="text-sm font-black text-emerald-900 tabular-nums">{{ $ordersByStatus['selesai'] }}</span>
                </div>

                <div class="pt-2 text-center">
                    <a href="{{ route('admin.sales.index') }}" class="text-xs font-bold text-amber-700 hover:text-amber-800 hover:underline">
                        Buka Daftar Semua Transaksi →
                    </a>
                </div>
            </div>
        </x-card>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('financialBarChart', (initialData) => ({
            chart: null,
            init() {
                const options = {
                    series: [{
                        name: 'Nominal (Rp)',
                        data: initialData.data
                    }],
                    chart: {
                        type: 'bar',
                        height: 310,
                        toolbar: { show: false },
                        fontFamily: 'inherit'
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            columnWidth: '45%',
                            distributed: true,
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
                    colors: initialData.colors,
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            if (val >= 1000000) {
                                return 'Rp ' + (val / 1000000).toFixed(1) + ' jt';
                            }
                            if (val >= 1000) {
                                return 'Rp ' + (val / 1000).toFixed(0) + ' rb';
                            }
                            return 'Rp ' + Number(val).toLocaleString('id-ID');
                        },
                        offsetY: -20,
                        style: {
                            fontSize: '11px',
                            fontWeight: 'bold',
                            colors: ['#475569']
                        }
                    },
                    legend: { show: false },
                    xaxis: {
                        categories: initialData.categories,
                        labels: {
                            style: {
                                fontSize: '11px',
                                fontWeight: 600,
                                colors: '#64748b'
                            }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (val) {
                                return 'Rp ' + (val / 1000000).toFixed(1) + 'M';
                            },
                            style: {
                                fontSize: '10px',
                                colors: '#94a3b8'
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return 'Rp ' + Number(val).toLocaleString('id-ID');
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4
                    }
                };

                this.chart = new window.ApexCharts(this.$refs.chartContainer, options);
                this.chart.render();

                this.$watch('$wire.chartFinancial', (newData) => {
                    if (this.chart && newData) {
                        this.chart.updateOptions({
                            xaxis: { categories: newData.categories },
                            colors: newData.colors
                        });
                        this.chart.updateSeries([{
                            name: 'Nominal (Rp)',
                            data: newData.data
                        }]);
                    }
                });
            },
            destroy() {
                if (this.chart) {
                    this.chart.destroy();
                }
            }
        }));

        Alpine.data('trendAreaChart', (initialData) => ({
            chart: null,
            init() {
                const options = {
                    series: [
                        {
                            name: 'Omzet (Rp)',
                            type: 'area',
                            data: initialData.seriesOmzet
                        },
                        {
                            name: 'Pesanan',
                            type: 'column',
                            data: initialData.seriesCount
                        }
                    ],
                    chart: {
                        height: 310,
                        type: 'line',
                        toolbar: { show: false },
                        fontFamily: 'inherit'
                    },
                    stroke: {
                        width: [3, 0],
                        curve: 'smooth'
                    },
                    fill: {
                        type: ['gradient', 'solid'],
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    colors: ['#d97706', '#94a3b8'],
                    xaxis: {
                        categories: initialData.categories,
                        labels: {
                            style: {
                                fontSize: '10px',
                                fontWeight: 500,
                                colors: '#64748b'
                            }
                        }
                    },
                    yaxis: [
                        {
                            title: { text: 'Omzet', style: { fontSize: '10px', color: '#d97706' } },
                            labels: {
                                formatter: function (val) {
                                    return 'Rp ' + (val / 1000000).toFixed(1) + 'M';
                                },
                                style: { fontSize: '10px', colors: '#94a3b8' }
                            }
                        },
                        {
                            opposite: true,
                            title: { text: 'Pesanan', style: { fontSize: '10px', color: '#64748b' } },
                            labels: {
                                formatter: function (val) {
                                    return Math.round(val);
                                },
                                style: { fontSize: '10px', colors: '#94a3b8' }
                            }
                        }
                    ],
                    tooltip: {
                        y: {
                            formatter: function (val, { seriesIndex }) {
                                if (seriesIndex === 0) {
                                    return 'Rp ' + Number(val).toLocaleString('id-ID');
                                }
                                return val + ' pesanan';
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4
                    }
                };

                this.chart = new window.ApexCharts(this.$refs.trendContainer, options);
                this.chart.render();

                this.$watch('$wire.chartTrend', (newData) => {
                    if (this.chart && newData) {
                        this.chart.updateOptions({
                            xaxis: { categories: newData.categories }
                        });
                        this.chart.updateSeries([
                            { name: 'Omzet (Rp)', data: newData.seriesOmzet },
                            { name: 'Pesanan', data: newData.seriesCount }
                        ]);
                    }
                });
            },
            destroy() {
                if (this.chart) {
                    this.chart.destroy();
                }
            }
        }));
    });
</script>
@endpush
