<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Pembayaran & Tracking Piutang</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau pesanan belum lunas (DP/Belum Bayar), catat pembayaran masuk, dan riwayat verifikasi</p>
        </div>
    </div>

    <!-- KPI Summary Card -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-rose-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">Total Piutang Belum Lunas</p>
                <h3 class="text-2xl font-black text-rose-700 mt-1 tabular-nums">
                    @if($activeTab === 'piutang')
                        @rupiah($totalPiutangAll)
                    @else
                        <span class="text-base font-normal text-slate-400">Lihat tab piutang</span>
                    @endif
                </h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Sisa tagihan yang masih harus ditagih ke pemesan</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-emerald-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Total Pembayaran Masuk</p>
                <h3 class="text-2xl font-black text-emerald-700 mt-1 tabular-nums">
                    @if($activeTab === 'riwayat')
                        @rupiah($totalPaidAll)
                    @else
                        <span class="text-base font-normal text-slate-400">Lihat tab riwayat</span>
                    @endif
                </h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Akumulasi uang yang telah lunas & terverifikasi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabs and Filter Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3 rounded-2xl border border-slate-200/90 shadow-2xs">
        <!-- Tabs -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl">
            <button
                type="button"
                wire:click="setTab('piutang')"
                class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'piutang' ? 'bg-white text-rose-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
            >
                Daftar Piutang Aktif
            </button>
            <button
                type="button"
                wire:click="setTab('riwayat')"
                class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'riwayat' ? 'bg-white text-emerald-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
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
                class="w-full pl-9 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-800 placeholder-slate-400"
            >
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center justify-between">
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
                    <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">No. Order & Batch PO</th>
                            <th class="px-5 py-3.5">Customer & Kontak</th>
                            <th class="px-5 py-3.5">Status Bayar</th>
                            <th class="px-5 py-3.5 text-right">Total Tagihan</th>
                            <th class="px-5 py-3.5 text-right">Sudah Dibayar</th>
                            <th class="px-5 py-3.5 text-right">Sisa Piutang</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($unpaidSales as $sale)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- Order -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ $sale->nomor_order }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        {{ $sale->tanggal_pemesanan->format('d M Y') }}
                                        @if($sale->openPo)
                                            • <span class="text-amber-700 font-medium">{{ $sale->openPo->nama_po }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Customer -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ $sale->customer->nama ?? 'Umum' }}</div>
                                    @if($sale->customer?->no_whatsapp)
                                        <a
                                            href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sale->customer->no_whatsapp) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1 text-[11px] text-emerald-600 hover:text-emerald-700 font-medium mt-0.5 hover:underline"
                                        >
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                            </svg>
                                            {{ $sale->customer->no_whatsapp }}
                                        </a>
                                    @endif
                                </td>

                                <!-- Status Bayar -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <x-badge :variant="$sale->status_pembayaran_badge">
                                        {{ $sale->status_pembayaran_label }}
                                    </x-badge>
                                </td>

                                <!-- Total Tagihan -->
                                <td class="px-5 py-4 whitespace-nowrap text-right font-semibold text-slate-800 tabular-nums">
                                    @rupiah($sale->total_tagihan)
                                </td>

                                <!-- Sudah Dibayar -->
                                <td class="px-5 py-4 whitespace-nowrap text-right font-medium text-emerald-600 tabular-nums">
                                    @rupiah($sale->total_paid)
                                </td>

                                <!-- Sisa Piutang -->
                                <td class="px-5 py-4 whitespace-nowrap text-right font-black text-rose-600 tabular-nums">
                                    @rupiah($sale->remaining_balance)
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <button
                                        type="button"
                                        wire:click="openPaymentModal({{ $sale->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 text-white rounded-xl font-bold text-xs hover:bg-emerald-700 shadow-2xs hover:shadow-xs transition-all active:scale-95"
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
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto text-emerald-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="font-bold text-slate-700 text-sm">Semua Piutang Lunas!</p>
                                    <p class="text-xs text-slate-400 mt-1">Tidak ada pesanan dengan status Belum Bayar atau DP yang tersisa</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($unpaidSales->hasPages())
                <div class="px-5 py-3 border-t border-slate-200">
                    {{ $unpaidSales->links() }}
                </div>
            @endif
        </x-card>
    @else
        <!-- TAB 2: RIWAYAT PEMBAYARAN MASUK -->
        <x-card noPadding="true">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal Bayar</th>
                            <th class="px-5 py-3.5">No. Order & Customer</th>
                            <th class="px-5 py-3.5">Jenis Pembayaran</th>
                            <th class="px-5 py-3.5">Metode Bayar</th>
                            <th class="px-5 py-3.5 text-right">Nominal Bayar</th>
                            <th class="px-5 py-3.5">Bukti Transfer</th>
                            <th class="px-5 py-3.5">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($paymentsHistory as $payment)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- Tanggal -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ $payment->tanggal_bayar->format('d M Y') }}</div>
                                    <span class="text-[10px] text-slate-400 font-medium block">
                                        {{ $payment->created_at->format('H:i') }} WIB
                                    </span>
                                </td>

                                <!-- Order & Customer -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-bold text-amber-950">{{ $payment->sale->nomor_order ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-500 font-medium mt-0.5">
                                        {{ $payment->sale->customer->nama ?? 'Umum' }}
                                    </div>
                                </td>

                                <!-- Jenis -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @php
                                        $jenisBadge = match($payment->jenis_pembayaran) {
                                            'pelunasan' => 'success',
                                            'dp' => 'warning',
                                            default => 'info'
                                        };
                                        $jenisLabel = match($payment->jenis_pembayaran) {
                                            'pelunasan' => 'Pelunasan',
                                            'dp' => 'Uang Muka (DP)',
                                            'cicilan' => 'Cicilan',
                                            default => ucfirst($payment->jenis_pembayaran)
                                        };
                                    @endphp
                                    <x-badge :variant="$jenisBadge">
                                        {{ $jenisLabel }}
                                    </x-badge>
                                </td>

                                <!-- Metode -->
                                <td class="px-5 py-4 whitespace-nowrap font-medium text-slate-700">
                                    {{ $payment->metode_pembayaran }}
                                </td>

                                <!-- Nominal -->
                                <td class="px-5 py-4 whitespace-nowrap text-right font-black text-emerald-700 tabular-nums">
                                    @rupiah($payment->nominal_bayar)
                                </td>

                                <!-- Bukti -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($payment->bukti_bayar)
                                        <button
                                            type="button"
                                            wire:click="viewProof('{{ $payment->bukti_bayar }}')"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded-lg text-[11px] font-semibold border border-amber-200 transition-colors"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Foto
                                        </button>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Tanpa foto</span>
                                    @endif
                                </td>

                                <!-- Catatan -->
                                <td class="px-5 py-4 text-xs text-slate-500 max-w-xs truncate">
                                    {{ $payment->catatan ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                    <p class="font-bold text-slate-600 text-sm">Belum ada riwayat pembayaran</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($paymentsHistory->hasPages())
                <div class="px-5 py-3 border-t border-slate-200">
                    {{ $paymentsHistory->links() }}
                </div>
            @endif
        </x-card>
    @endif

    <!-- MODAL CATAT PEMBAYARAN -->
    @if($showPaymentModal && $selectedSale)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in-95">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Catat Pembayaran Masuk</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Order {{ $selectedSale->nomor_order }}</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closePaymentModal"
                        class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Info Ringkasan Tagihan Order -->
                <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Customer:</span>
                        <span class="font-bold text-slate-800">{{ $selectedSale->customer->nama ?? 'Umum' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Total Tagihan Order:</span>
                        <span class="font-bold text-slate-800 tabular-nums">@rupiah($selectedSale->total_tagihan)</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Sudah Dibayar:</span>
                        <span class="font-bold text-emerald-600 tabular-nums">@rupiah($selectedSale->total_paid)</span>
                    </div>
                    <div class="flex justify-between items-center text-xs pt-2 border-t border-slate-200">
                        <span class="font-bold text-slate-700">Sisa Tagihan (Piutang):</span>
                        <span class="font-black text-rose-600 text-sm tabular-nums">@rupiah($selectedSale->remaining_balance)</span>
                    </div>
                </div>

                <form wire:submit="savePayment" class="mt-4 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Tanggal Bayar -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Bayar *</label>
                            <input
                                wire:model="tanggal_bayar"
                                type="date"
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium"
                                required
                            >
                            @error('tanggal_bayar') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jenis Pembayaran -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Pembayaran *</label>
                            <select
                                wire:model="jenis_pembayaran"
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium"
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
                            <label class="block text-xs font-semibold text-slate-700">Nominal Pembayaran (Rp) *</label>
                            <button
                                type="button"
                                wire:click="fillFullPayment"
                                class="text-[11px] text-amber-700 hover:text-amber-800 font-bold hover:underline"
                            >
                                Lunaskan (@rupiah($selectedSale->remaining_balance))
                            </button>
                        </div>
                        <input
                            wire:model="nominal_bayar"
                            type="number"
                            step="any"
                            min="1"
                            class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-black text-emerald-700 tabular-nums"
                            required
                        >
                        @error('nominal_bayar') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Pembayaran *</label>
                        <select
                            wire:model="metode_pembayaran"
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium"
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
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Bukti Bayar / Nota Transfer (Opsional)</label>
                        <input
                            wire:model="bukti_bayar"
                            type="file"
                            accept="image/*"
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100"
                        >
                        <div wire:loading wire:target="bukti_bayar" class="text-[11px] text-amber-600 mt-1 font-medium">
                            Mengunggah foto...
                        </div>
                        @if ($bukti_bayar)
                            <div class="mt-2">
                                <img src="{{ $bukti_bayar->temporaryUrl() }}" class="h-20 w-auto rounded-lg border border-slate-200 object-cover shadow-2xs">
                            </div>
                        @endif
                        @error('bukti_bayar') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <input
                            wire:model="catatan"
                            type="text"
                            placeholder="Misal: pelunasan via rekening BCA a.n Siti"
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600"
                        >
                        @error('catatan') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            wire:click="closePaymentModal"
                            class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-2xs hover:shadow-xs transition-all disabled:opacity-50"
                        >
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- LIGHTBOX MODAL BUKTI TRANSFER -->
    @if($showProofModal && $proofUrl)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-xl w-full p-4 shadow-2xl relative">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-800">Bukti Pembayaran / Transfer</h3>
                    <button
                        type="button"
                        wire:click="closeProofModal"
                        class="text-slate-400 hover:text-slate-600 p-1 rounded-lg"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-3 flex justify-center">
                    <img src="{{ $proofUrl }}" alt="Bukti Transfer" class="max-h-[70vh] rounded-xl object-contain border border-slate-100 shadow-inner">
                </div>
            </div>
        </div>
    @endif
</div>
