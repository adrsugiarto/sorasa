<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Pembayaran & Tracking Piutang</h1>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Pantau pesanan belum lunas (DP/Belum Bayar), catat pembayaran masuk, dan riwayat verifikasi</p>
        </div>
    </div>

    <!-- KPI Summary Card -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        <div class="bg-white dark:bg-gray-900 p-4 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-rose-600 dark:text-rose-400">Total Piutang Belum Lunas</p>
                <h3 class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1 tabular-nums">
                    @if($activeTab === 'piutang')
                        @rupiah($totalPiutangAll)
                    @else
                        <span class="text-sm font-normal text-slate-400">Lihat tab piutang</span>
                    @endif
                </h3>
                <p class="text-[11px] text-slate-400 dark:text-gray-500 mt-0.5">Sisa tagihan yang masih harus ditagih ke pemesan</p>
            </div>
            <div class="w-10 h-10 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 border border-rose-200/60 dark:border-rose-900/60">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 p-4 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Total Pembayaran Masuk</p>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">
                    @if($activeTab === 'riwayat')
                        @rupiah($totalPaidAll)
                    @else
                        <span class="text-sm font-normal text-slate-400">Lihat tab riwayat</span>
                    @endif
                </h3>
                <p class="text-[11px] text-slate-400 dark:text-gray-500 mt-0.5">Akumulasi uang yang telah lunas & terverifikasi</p>
            </div>
            <div class="w-10 h-10 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-200/60 dark:border-emerald-900/60">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabs and Filter Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-gray-900 p-3 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs">
        <!-- Tabs -->
        <div class="flex items-center gap-1 p-1 bg-slate-100 dark:bg-gray-800 rounded-md">
            <button
                type="button"
                wire:click="setTab('piutang')"
                class="px-3 py-1.5 text-xs font-medium rounded-md transition-all cursor-pointer {{ $activeTab === 'piutang' ? 'bg-white dark:bg-gray-900 text-rose-600 dark:text-rose-400 shadow-xs' : 'text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white' }}"
            >
                Daftar Piutang Aktif
            </button>
            <button
                type="button"
                wire:click="setTab('riwayat')"
                class="px-3 py-1.5 text-xs font-medium rounded-md transition-all cursor-pointer {{ $activeTab === 'riwayat' ? 'bg-white dark:bg-gray-900 text-emerald-600 dark:text-emerald-400 shadow-xs' : 'text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white' }}"
            >
                Riwayat Pembayaran Masuk
            </button>
        </div>

        <!-- Search Bar -->
        <div class="relative max-w-xs w-full">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="{{ $activeTab === 'piutang' ? 'Cari nomor order atau customer...' : 'Cari order, customer, metode...' }}"
                class="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800 dark:text-white placeholder-slate-400 font-medium"
            >
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    @if(session('success'))
        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-md text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Content Based On Active Tab -->
    @if($activeTab === 'piutang')
        <!-- TAB 1: DAFTAR PIUTANG AKTIF -->
        <x-card noPadding="true">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-500 dark:text-gray-400 border-b border-slate-200 dark:border-gray-800 uppercase font-semibold text-[11px] tracking-wider">
                        <tr>
                            <th class="px-3.5 py-2.5">No. Order & Batch PO</th>
                            <th class="px-3.5 py-2.5">Customer & Kontak</th>
                            <th class="px-3.5 py-2.5">Status Bayar</th>
                            <th class="px-3.5 py-2.5 text-right">Total Tagihan</th>
                            <th class="px-3.5 py-2.5 text-right">Sudah Dibayar</th>
                            <th class="px-3.5 py-2.5 text-right">Sisa Piutang</th>
                            <th class="px-3.5 py-2.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-[13px] leading-5">
                        @forelse($unpaidSales as $sale)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40 transition-colors">
                                <!-- Order -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap">
                                    <div class="font-mono font-medium text-slate-900 dark:text-white tabular-nums">{{ $sale->nomor_order }}</div>
                                    <div class="text-[11px] text-slate-400 dark:text-gray-500 mt-0.5">
                                        {{ $sale->tanggal_pemesanan->format('d M Y') }}
                                        @if($sale->openPo)
                                            • <span class="text-blue-600 dark:text-blue-400 font-medium">{{ $sale->openPo->nama_po }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Customer -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap">
                                    <div class="font-medium text-slate-900 dark:text-white">{{ $sale->customer->nama ?? 'Umum' }}</div>
                                    @if($sale->customer?->no_whatsapp)
                                        <a
                                            href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sale->customer->no_whatsapp) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1 text-[11px] text-emerald-600 dark:text-emerald-400 hover:underline mt-0.5 font-mono tabular-nums"
                                        >
                                            {{ $sale->customer->no_whatsapp }}
                                        </a>
                                    @endif
                                </td>

                                <!-- Status Bayar -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap">
                                    <x-badge :variant="$sale->status_pembayaran_badge">
                                        {{ $sale->status_pembayaran_label }}
                                    </x-badge>
                                </td>

                                <!-- Total Tagihan -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap text-right font-medium text-slate-700 dark:text-gray-300 tabular-nums">
                                    @rupiah($sale->total_tagihan)
                                </td>

                                <!-- Sudah Dibayar -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap text-right font-medium text-emerald-600 dark:text-emerald-400 tabular-nums">
                                    @rupiah($sale->total_paid)
                                </td>

                                <!-- Sisa Piutang -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap text-right font-bold text-rose-600 dark:text-rose-400 tabular-nums">
                                    @rupiah($sale->remaining_balance)
                                </td>

                                <!-- Aksi -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap text-center">
                                    <button
                                        type="button"
                                        wire:click="openPaymentModal({{ $sale->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md font-medium text-xs shadow-xs transition-colors cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Catat Bayar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3.5 py-8 text-center text-slate-400 dark:text-gray-500">
                                    <svg class="w-10 h-10 mx-auto text-emerald-400 dark:text-emerald-600 mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="font-semibold text-slate-700 dark:text-gray-300 text-xs">Semua Piutang Lunas!</p>
                                    <p class="text-[11px] text-slate-400 dark:text-gray-500 mt-0.5">Tidak ada pesanan dengan status Belum Bayar atau DP yang tersisa</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($unpaidSales->hasPages())
                <div class="px-3.5 py-2.5 border-t border-slate-200 dark:border-gray-800">
                    {{ $unpaidSales->links() }}
                </div>
            @endif
        </x-card>
    @else
        <!-- TAB 2: RIWAYAT PEMBAYARAN MASUK -->
        <x-card noPadding="true">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-500 dark:text-gray-400 border-b border-slate-200 dark:border-gray-800 uppercase font-semibold text-[11px] tracking-wider">
                        <tr>
                            <th class="px-3.5 py-2.5">Tanggal Bayar</th>
                            <th class="px-3.5 py-2.5">No. Order & Customer</th>
                            <th class="px-3.5 py-2.5">Jenis Pembayaran</th>
                            <th class="px-3.5 py-2.5">Metode Bayar</th>
                            <th class="px-3.5 py-2.5 text-right">Nominal Bayar</th>
                            <th class="px-3.5 py-2.5">Bukti Transfer</th>
                            <th class="px-3.5 py-2.5">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-[13px] leading-5">
                        @forelse($paymentsHistory as $payment)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40 transition-colors">
                                <!-- Tanggal -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap">
                                    <div class="font-medium text-slate-900 dark:text-white tabular-nums">{{ $payment->tanggal_bayar->format('d M Y') }}</div>
                                    <span class="text-[10px] text-slate-400 dark:text-gray-500 block tabular-nums">
                                        {{ $payment->created_at->format('H:i') }} WIB
                                    </span>
                                </td>

                                <!-- Order & Customer -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap">
                                    <div class="font-mono font-medium text-slate-900 dark:text-white tabular-nums">{{ $payment->sale->nomor_order ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-gray-400 mt-0.5">
                                        {{ $payment->sale->customer->nama ?? 'Umum' }}
                                    </div>
                                </td>

                                <!-- Jenis -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap">
                                    @php
                                        $jenisBadge = match($payment->jenis_pembayaran) {
                                            'pelunasan' => 'success',
                                            'dp' => 'warning',
                                            default => 'info',
                                        };
                                        $jenisLabel = match($payment->jenis_pembayaran) {
                                            'pelunasan' => 'Pelunasan',
                                            'dp' => 'Uang Muka (DP)',
                                            default => 'Cicilan',
                                        };
                                    @endphp
                                    <x-badge :variant="$jenisBadge">{{ $jenisLabel }}</x-badge>
                                </td>

                                <!-- Metode -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap font-medium text-slate-700 dark:text-gray-300 uppercase text-xs">
                                    {{ $payment->metode_pembayaran }}
                                </td>

                                <!-- Nominal -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap text-right font-semibold text-emerald-600 dark:text-emerald-400 tabular-nums">
                                    @rupiah($payment->nominal_bayar)
                                </td>

                                <!-- Bukti Nota -->
                                <td class="px-3.5 py-2.5 whitespace-nowrap">
                                    @if($payment->bukti_bayar)
                                        <button
                                            type="button"
                                            wire:click="openProofModal('{{ asset('storage/' . $payment->bukti_bayar) }}')"
                                            class="inline-flex items-center gap-1 text-[11px] font-medium text-blue-600 dark:text-blue-400 hover:underline cursor-pointer"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>Lihat Nota</span>
                                        </button>
                                    @else
                                        <span class="text-[11px] text-slate-400 dark:text-gray-600 italic">Tidak ada foto</span>
                                    @endif
                                </td>

                                <!-- Catatan -->
                                <td class="px-3.5 py-2.5 text-slate-500 dark:text-gray-400 max-w-xs truncate text-xs">
                                    {{ $payment->catatan ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3.5 py-8 text-center text-slate-400 dark:text-gray-500">
                                    Belum ada catatan riwayat pembayaran masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($paymentsHistory->hasPages())
                <div class="px-3.5 py-2.5 border-t border-slate-200 dark:border-gray-800">
                    {{ $paymentsHistory->links() }}
                </div>
            @endif
        </x-card>
    @endif

    <!-- MODAL CATAT PEMBAYARAN / PELUNASAN -->
    <div
        x-data="{ show: @entangle('showPaymentModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
        style="display: none;"
        @keydown.escape.window="show = false; $wire.closePaymentModal()"
    >
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/40 dark:bg-black/70 backdrop-blur-xs transition-opacity"
            @click="show = false; $wire.closePaymentModal()"
        ></div>

        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-lg shadow-xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all z-10 text-slate-800 dark:text-slate-200"
        >
            @if($selectedSale)
                <div class="px-5 py-3.5 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Catat Pembayaran Masuk
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-gray-400 font-mono tabular-nums">
                            Order #{{ $selectedSale->nomor_order }} — {{ $selectedSale->customer->nama ?? 'Umum' }}
                        </p>
                    </div>
                    <button type="button" @click="show = false; $wire.closePaymentModal()" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-800 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Info Ringkasan Tagihan Order -->
                <div class="bg-slate-50 dark:bg-gray-800/60 p-4 border-b border-slate-100 dark:border-gray-800 grid grid-cols-3 gap-2 text-center text-xs">
                    <div>
                        <span class="text-[10px] text-slate-400 dark:text-gray-400 uppercase font-semibold">Total Tagihan</span>
                        <div class="font-bold text-slate-900 dark:text-white mt-0.5 tabular-nums">@rupiah($selectedSale->total_tagihan)</div>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 dark:text-gray-400 uppercase font-semibold">Sudah Masuk</span>
                        <div class="font-bold text-emerald-600 dark:text-emerald-400 mt-0.5 tabular-nums">@rupiah($selectedSale->total_paid)</div>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 dark:text-gray-400 uppercase font-semibold">Sisa Piutang</span>
                        <div class="font-bold text-rose-600 dark:text-rose-400 mt-0.5 tabular-nums">@rupiah($selectedSale->remaining_balance)</div>
                    </div>
                </div>

                <form wire:submit="savePayment" class="p-5 space-y-3.5 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Tanggal Bayar -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Tanggal Bayar *</label>
                            <input
                                wire:model="tanggal_bayar"
                                type="date"
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                                required
                            >
                            @error('tanggal_bayar') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jenis Pembayaran -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Jenis Pembayaran *</label>
                            <select
                                wire:model="jenis_pembayaran"
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                                required
                            >
                                <option value="pelunasan">Pelunasan</option>
                                <option value="dp">Uang Muka (DP)</option>
                                <option value="cicilan">Cicilan</option>
                            </select>
                            @error('jenis_pembayaran') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Nominal Bayar -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider">Nominal Pembayaran (Rp) *</label>
                            <button
                                type="button"
                                wire:click="fillFullPayment"
                                class="text-[11px] text-blue-600 dark:text-blue-400 hover:text-blue-700 font-medium hover:underline cursor-pointer tabular-nums"
                            >
                                Lunaskan (@rupiah($selectedSale->remaining_balance))
                            </button>
                        </div>
                        <input
                            wire:model="nominal_bayar"
                            type="number"
                            step="any"
                            min="1"
                            class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-mono font-bold text-slate-900 dark:text-white tabular-nums"
                            required
                        >
                        @error('nominal_bayar') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Metode Pembayaran *</label>
                        <select
                            wire:model="metode_pembayaran"
                            class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                            required
                        >
                            @foreach($paymentMethods as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                        @error('metode_pembayaran') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Bukti Transfer -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Bukti Bayar / Nota Transfer (Opsional)</label>
                        <input
                            wire:model="bukti_bayar"
                            type="file"
                            accept="image/*"
                            class="w-full text-xs text-slate-500 dark:text-gray-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-100 dark:file:bg-gray-800 file:text-slate-700 dark:file:text-gray-300 hover:file:bg-slate-200 dark:hover:file:bg-gray-700"
                        >
                        <div wire:loading wire:target="bukti_bayar" class="text-[11px] text-blue-600 mt-1 font-medium">
                            Mengunggah foto...
                        </div>
                        @if ($bukti_bayar)
                            <div class="mt-2">
                                <img src="{{ $bukti_bayar->temporaryUrl() }}" class="h-16 w-auto rounded-md border border-slate-200 dark:border-gray-700 object-cover shadow-xs">
                            </div>
                        @endif
                        @error('bukti_bayar') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Catatan Tambahan (Opsional)</label>
                        <input
                            wire:model="catatan"
                            type="text"
                            placeholder="Misal: pelunasan via rekening BCA a.n Siti"
                            class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                        >
                        @error('catatan') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-gray-800">
                        <button
                            type="button"
                            @click="show = false; $wire.closePaymentModal()"
                            class="px-3 py-1.5 text-xs font-medium text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md hover:bg-slate-50 dark:hover:bg-gray-750 cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-3.5 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-xs transition-colors disabled:opacity-50 cursor-pointer"
                        >
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- LIGHTBOX MODAL BUKTI TRANSFER -->
    <div
        x-data="{ show: @entangle('showProofModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
        style="display: none;"
        @keydown.escape.window="show = false; $wire.closeProofModal()"
    >
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-xs transition-opacity"
            @click="show = false; $wire.closeProofModal()"
        ></div>

        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            class="bg-white dark:bg-gray-900 rounded-lg max-w-xl w-full p-4 shadow-xl border border-slate-200 dark:border-gray-800 relative text-slate-800 dark:text-slate-200 z-10"
        >
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-gray-800">
                <h3 class="text-xs font-semibold text-slate-900 dark:text-white">Bukti Pembayaran / Transfer</h3>
                <button
                    type="button"
                    @click="show = false; $wire.closeProofModal()"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 p-1 rounded-md cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-3 flex justify-center">
                @if($proofUrl)
                    <img src="{{ $proofUrl }}" alt="Bukti Transfer" class="max-h-[70vh] rounded-md object-contain border border-slate-200 dark:border-gray-700 shadow-inner">
                @endif
            </div>
        </div>
    </div>
</div>
