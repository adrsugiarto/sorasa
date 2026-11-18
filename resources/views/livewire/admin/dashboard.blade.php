<div class="space-y-5">
    <!-- Page Header & Filter Toolbar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Dashboard Operasional & Analitik</h1>
            <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">Pantau performa omzet, biaya belanja bahan, surplus usaha, dan efisiensi pesanan Open PO</p>
        </div>
        <div class="flex items-center gap-2">
            <a
                href="{{ route('admin.reports') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold shadow-xs transition-colors dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-750"
            >
                <svg class="w-4 h-4 text-slate-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Laporan Laba Rugi</span>
            </a>
        </div>
    </div>

    <!-- Global Reactive Filter Toolbar -->
    <div class="bg-white dark:bg-gray-900 p-4 rounded-xl border border-slate-200 dark:border-gray-800 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3.5">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
            <span class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider shrink-0 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter:
            </span>

            <!-- Filter Batch Open PO -->
            <select
                wire:model.live="selectedOpenPoId"
                class="px-3.5 py-2 text-sm bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800 dark:text-white font-medium"
            >
                <option value="">Semua Periode Batch PO</option>
                @foreach($openPos as $po)
                    <option value="{{ $po->id }}">[{{ $po->kode_po }}] {{ $po->nama_po }}</option>
                @endforeach
            </select>

            <!-- Filter Preset Periode -->
            <select
                wire:model.live="period"
                class="px-3.5 py-2 text-sm bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800 dark:text-white font-medium"
            >
                <option value="all">Semua Waktu</option>
                <option value="this_month">Bulan Ini</option>
                <option value="last_month">Bulan Lalu</option>
                <option value="custom">Rentang Tanggal Kustom</option>
            </select>

            <!-- Custom Date Range -->
            @if($period === 'custom')
                <div class="flex items-center gap-2">
                    <input
                        wire:model.live="startDate"
                        type="date"
                        class="px-3 py-2 text-sm bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg focus:bg-white dark:focus:bg-gray-900 text-slate-800 dark:text-white"
                    >
                    <span class="text-xs text-slate-400">s/d</span>
                    <input
                        wire:model.live="endDate"
                        type="date"
                        class="px-3 py-2 text-sm bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg focus:bg-white dark:focus:bg-gray-900 text-slate-800 dark:text-white"
                    >
                </div>
            @endif
        </div>

        @if($selectedOpenPoId || $period !== 'all' || $startDate || $endDate)
            <button
                type="button"
                wire:click="resetFilters"
                class="text-xs text-rose-600 hover:text-rose-700 font-semibold hover:underline shrink-0"
            >
                Reset Filter
            </button>
        @endif
    </div>

    <!-- KPI Summary Grid (4 Kartu Inti Rapat - Stripe / Linear Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Penjualan (Omzet) -->
        <div class="p-5 rounded-xl border border-slate-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs flex flex-col justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Total Omzet Penjualan</p>
                <p class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white mt-1.5 tabular-nums">
                    @rupiah($totalOmzet)
                </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-gray-800 flex items-center justify-between text-xs sm:text-sm text-slate-500 dark:text-gray-400">
                <span class="font-medium text-slate-700 dark:text-slate-300 tabular-nums">{{ $totalOrders }} pesanan masuk</span>
                <span class="text-slate-400">Omzet kotor</span>
            </div>
        </div>

        <!-- 2. Belanja Bahan Baku (Habis Pakai) -->
        <div class="p-5 rounded-xl border border-slate-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs flex flex-col justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Total Pembelian (Bahan)</p>
                <p class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white mt-1.5 tabular-nums">
                    @rupiah($totalPembelian)
                </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-gray-800 flex items-center justify-between text-xs sm:text-sm text-slate-500 dark:text-gray-400">
                <span class="text-slate-700 dark:text-slate-300">Habis pakai dapur</span>
                <span class="text-slate-400">Biaya langsung</span>
            </div>
        </div>

        <!-- 3. Biaya Lain / Operasional -->
        <div class="p-5 rounded-xl border border-slate-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs flex flex-col justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Biaya Lain (Operasional)</p>
                <p class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white mt-1.5 tabular-nums">
                    @rupiah($totalBiayaLain)
                </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-gray-800 flex items-center justify-between text-xs sm:text-sm text-slate-500 dark:text-gray-400">
                <span class="text-slate-700 dark:text-slate-300">Kemasan & utilitas</span>
                <span class="text-slate-400">Non-bahan</span>
            </div>
        </div>

        <!-- 4. Surplus Usaha Sederhana -->
        <div class="p-5 rounded-xl border border-slate-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs flex flex-col justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Surplus Usaha Sederhana</p>
                <p class="text-3xl font-extrabold tracking-tight mt-1.5 tabular-nums {{ $surplus >= 0 ? 'text-slate-900 dark:text-white' : 'text-rose-600' }}">
                    <x-money :value="$surplus" :sign="true" />
                </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-gray-800 flex items-center justify-between text-xs sm:text-sm">
                <span class="text-slate-500 dark:text-gray-400">Omzet — Biaya Total</span>
                <span class="font-bold tabular-nums {{ $surplus >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600' }}">
                    {{ $surplus >= 0 ? "+{$surplusMargin}% Margin" : 'Defisit' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Secondary Row: Arus Kas Masuk vs Piutang Belum Lunas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-slate-200 dark:border-gray-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Total Kas Diterima (Lunas & DP)</span>
                <div class="text-2xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">
                    @rupiah($totalPaid)
                </div>
                <p class="text-xs text-slate-400 mt-1">Sudah masuk rekening/kas usaha</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                Terverifikasi
            </span>
        </div>

        <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-slate-200 dark:border-gray-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Total Tagihan Belum Lunas (Piutang)</span>
                <div class="text-2xl font-bold text-rose-600 mt-1 tabular-nums">
                    @rupiah($totalPiutang)
                </div>
                <p class="text-xs text-slate-400 mt-1">Sisa tagihan yang harus ditagih ke pemesan</p>
            </div>
            <a
                href="{{ route('admin.payments') }}"
                class="px-3.5 py-2 bg-slate-50 hover:bg-slate-100 dark:bg-gray-800 dark:hover:bg-gray-750 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-700 rounded-lg text-xs sm:text-sm font-semibold transition-colors"
            >
                Kelola Piutang →
            </a>
        </div>
    </div>

    <!-- ApexCharts Analytics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- 1. Perbandingan Finansial Bar Chart -->
        <x-card class="lg:col-span-2" title="Perbandingan Finansial Usaha" description="Perbandingan Omzet Penjualan vs Pembelian Bahan vs Biaya Operasional vs Surplus">
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Tabel Menu Terlaris (Top Selling) -->
        <x-card class="lg:col-span-2" noPadding="true">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">5 Menu Terlaris (Top Selling)</h3>
                    <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Berdasarkan total porsi paket terjual dan kontribusi omzet</p>
                </div>
                <a href="{{ route('admin.products') }}" class="text-xs sm:text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                    Kelola Produk →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-600 dark:text-gray-400 border-b border-slate-200 dark:border-gray-800 uppercase font-bold text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3.5">Peringkat</th>
                            <th class="px-4 py-3.5">Nama Menu</th>
                            <th class="px-4 py-3.5 text-right">Paket Terjual</th>
                            <th class="px-4 py-3.5 text-right">Kontribusi Omzet</th>
                            <th class="px-4 py-3.5 text-right">% Omzet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-sm leading-6">
                        @forelse($topProducts as $idx => $prod)
                            @php
                                $percent = $totalOmzet > 0 ? round(((float)$prod->total_omzet / $totalOmzet) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40 transition-colors">
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="w-6 h-6 rounded-md inline-flex items-center justify-center font-bold text-xs {{ $idx === 0 ? 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/40 dark:text-blue-300' : 'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-gray-400' }}">
                                        {{ $idx + 1 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $prod->nama_produk_snapshot }}</span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-right font-medium text-slate-800 dark:text-gray-200 tabular-nums">
                                    {{ $prod->total_qty }} paket
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-right font-bold text-slate-900 dark:text-white tabular-nums">
                                    @rupiah($prod->total_omzet)
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-gray-800 dark:text-gray-300 tabular-nums">
                                        {{ $percent }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">
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
            <div class="space-y-2.5 mt-1">
                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-gray-800/50 border border-slate-100 dark:border-gray-800">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                        <span class="text-sm font-medium text-slate-700 dark:text-gray-300">Menunggu Konfirmasi</span>
                    </div>
                    <span class="text-sm font-bold text-slate-900 dark:text-white tabular-nums">{{ $ordersByStatus['menunggu_konfirmasi'] }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-gray-800/50 border border-slate-100 dark:border-gray-800">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="text-sm font-medium text-slate-700 dark:text-gray-300">Diproses Produksi Dapur</span>
                    </div>
                    <span class="text-sm font-bold text-slate-900 dark:text-white tabular-nums">{{ $ordersByStatus['diproses'] }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-gray-800/50 border border-slate-100 dark:border-gray-800">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                        <span class="text-sm font-medium text-slate-700 dark:text-gray-300">Siap Dikirim</span>
                    </div>
                    <span class="text-sm font-bold text-slate-900 dark:text-white tabular-nums">{{ $ordersByStatus['siap_kirim'] }}</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-gray-800/50 border border-slate-100 dark:border-gray-800">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-sm font-medium text-slate-700 dark:text-gray-300">Pesanan Selesai</span>
                    </div>
                    <span class="text-sm font-bold text-slate-900 dark:text-white tabular-nums">{{ $ordersByStatus['selesai'] }}</span>
                </div>

                <div class="pt-2 text-center">
                    <a href="{{ route('admin.sales.index') }}" class="text-xs sm:text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">
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
                const isDark = document.documentElement.classList.contains('dark');
                const options = {
                    series: [{
                        name: 'Nominal (Rp)',
                        data: initialData.data
                    }],
                    chart: {
                        type: 'bar',
                        height: 310,
                        toolbar: { show: false },
                        fontFamily: 'inherit',
                        background: 'transparent'
                    },
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
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
                            fontWeight: '600',
                            colors: [isDark ? '#cbd5e1' : '#475569']
                        }
                    },
                    legend: { show: false },
                    xaxis: {
                        categories: initialData.categories,
                        labels: {
                            style: {
                                fontSize: '11px',
                                fontWeight: 500,
                                colors: isDark ? '#94a3b8' : '#64748b'
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
                                colors: isDark ? '#94a3b8' : '#94a3b8'
                            }
                        }
                    },
                    tooltip: {
                        theme: isDark ? 'dark' : 'light',
                        y: {
                            formatter: function (val) {
                                return 'Rp ' + Number(val).toLocaleString('id-ID');
                            }
                        }
                    },
                    grid: {
                        borderColor: isDark ? '#1f2937' : '#f1f5f9',
                        strokeDashArray: 4
                    }
                };

                this.chart = new window.ApexCharts(this.$refs.chartContainer, options);
                this.chart.render();

                window.addEventListener('charts-updated', (event) => {
                    if (this.chart && event.detail && event.detail.financial) {
                        const newData = event.detail.financial;
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

                window.addEventListener('theme-changed', (event) => {
                    if (this.chart) {
                        const dark = event.detail === 'dark';
                        this.chart.updateOptions({
                            theme: { mode: dark ? 'dark' : 'light' },
                            grid: { borderColor: dark ? '#1f2937' : '#f1f5f9' },
                            xaxis: { labels: { style: { colors: dark ? '#94a3b8' : '#64748b' } } },
                            dataLabels: { style: { colors: [dark ? '#cbd5e1' : '#475569'] } },
                            tooltip: { theme: dark ? 'dark' : 'light' }
                        });
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
                const isDark = document.documentElement.classList.contains('dark');
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
                        fontFamily: 'inherit',
                        background: 'transparent'
                    },
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    stroke: {
                        width: [2.5, 0],
                        curve: 'smooth'
                    },
                    fill: {
                        type: ['gradient', 'solid'],
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.35,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    colors: ['#2563eb', '#94a3b8'],
                    xaxis: {
                        categories: initialData.categories,
                        labels: {
                            style: {
                                fontSize: '10px',
                                fontWeight: 500,
                                colors: isDark ? '#94a3b8' : '#64748b'
                            }
                        }
                    },
                    yaxis: [
                        {
                            title: { text: 'Omzet', style: { fontSize: '10px', color: '#2563eb' } },
                            labels: {
                                formatter: function (val) {
                                    return 'Rp ' + (val / 1000000).toFixed(1) + 'M';
                                },
                                style: { fontSize: '10px', colors: '#94a3b8' }
                            }
                        },
                        {
                            opposite: true,
                            title: { text: 'Pesanan', style: { fontSize: '10px', color: isDark ? '#94a3b8' : '#64748b' } },
                            labels: {
                                formatter: function (val) {
                                    return Math.round(val);
                                },
                                style: { fontSize: '10px', colors: '#94a3b8' }
                            }
                        }
                    ],
                    tooltip: {
                        theme: isDark ? 'dark' : 'light',
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
                        borderColor: isDark ? '#1f2937' : '#f1f5f9',
                        strokeDashArray: 4
                    }
                };

                this.chart = new window.ApexCharts(this.$refs.trendContainer, options);
                this.chart.render();

                window.addEventListener('charts-updated', (event) => {
                    if (this.chart && event.detail && event.detail.trend) {
                        const newData = event.detail.trend;
                        this.chart.updateOptions({
                            xaxis: { categories: newData.categories }
                        });
                        this.chart.updateSeries([
                            { name: 'Omzet (Rp)', data: newData.seriesOmzet },
                            { name: 'Pesanan', data: newData.seriesCount }
                        ]);
                    }
                });

                window.addEventListener('theme-changed', (event) => {
                    if (this.chart) {
                        const dark = event.detail === 'dark';
                        this.chart.updateOptions({
                            theme: { mode: dark ? 'dark' : 'light' },
                            grid: { borderColor: dark ? '#1f2937' : '#f1f5f9' },
                            xaxis: { labels: { style: { colors: dark ? '#94a3b8' : '#64748b' } } },
                            yaxis: [
                                { labels: { style: { colors: '#94a3b8' } } },
                                { labels: { style: { colors: '#94a3b8' } }, title: { style: { color: dark ? '#94a3b8' : '#64748b' } } }
                            ],
                            tooltip: { theme: dark ? 'dark' : 'light' }
                        });
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
