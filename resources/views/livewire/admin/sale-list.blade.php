<div>
    <!-- Main Screen Content (Hidden when printing receipt) -->
    <div id="sales-page-content" class="space-y-5 print:hidden no-print">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Daftar Pesanan Penjualan</h1>
                <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">Seluruh riwayat transaksi order, cetak nota struk, dan pantau status pemrosesan</p>
            </div>
            <a
                href="{{ route('admin.sales.pos') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-xs transition-all active:scale-95 cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Order Baru (Kasir POS)</span>
            </a>
        </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 bg-white dark:bg-gray-900 p-4 rounded-xl border border-slate-200 dark:border-gray-800 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 flex-1">
            <div class="relative">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Cari nomor INV atau nama..."
                    class="w-full pl-10 pr-3.5 py-2 text-sm bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800 dark:text-white placeholder-slate-400 font-medium"
                >
                <svg class="w-4.5 h-4.5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select
                wire:model.live="selectedOpenPoId"
                class="px-3 py-2 text-sm bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300 font-medium"
            >
                <option value="">Semua Batch PO</option>
                @foreach($openPos as $po)
                    <option value="{{ $po->id }}">{{ $po->nama_po }}</option>
                @endforeach
            </select>

            <select
                wire:model.live="selectedStatusPembayaran"
                class="px-3 py-2 text-sm bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300 font-medium"
            >
                <option value="">Semua Status Bayar</option>
                <option value="lunas">Lunas</option>
                <option value="dp">DP (Uang Muka)</option>
                <option value="belum_bayar">Belum Bayar</option>
            </select>

            <select
                wire:model.live="selectedStatusOrder"
                class="px-3 py-2 text-sm bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300 font-medium"
            >
                <option value="">Semua Status Order</option>
                <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                <option value="diproses">Diproses (Dapur)</option>
                <option value="siap_kirim">Siap Kirim</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
        </div>

        <div class="px-4 py-2 rounded-lg bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-right shrink-0">
            <span class="text-xs uppercase tracking-wider text-slate-400 dark:text-gray-500 font-bold block">Total Omzet Terfilter:</span>
            <span class="text-base font-bold text-slate-900 dark:text-white tabular-nums">
                <x-money :value="$totalOmzetFiltered" />
            </span>
        </div>
    </div>

    <!-- Sales Table -->
    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-600 dark:text-gray-400 border-b border-slate-200 dark:border-gray-800 uppercase font-bold text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Faktur Order</th>
                        <th class="px-4 py-3.5">Pelanggan</th>
                        <th class="px-4 py-3.5">Batch PO</th>
                        <th class="px-4 py-3.5">Item Pesanan</th>
                        <th class="px-4 py-3.5 text-right">Total Tagihan</th>
                        <th class="px-4 py-3.5">Status Bayar</th>
                        <th class="px-4 py-3.5">Status Order & Kurir</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-sm leading-6">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="px-4 py-3.5 font-mono font-medium text-slate-900 dark:text-white whitespace-nowrap tabular-nums">
                                <div class="font-bold text-sm">{{ $sale->nomor_order }}</div>
                                <span class="text-xs text-slate-400 dark:text-gray-500 font-normal font-sans">{{ $sale->tanggal_pemesanan->format('d M Y') }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-slate-900 dark:text-white">{{ $sale->customer->nama ?? '-' }}</div>
                                <a href="{{ $sale->customer?->wa_link }}" target="_blank" class="text-xs font-mono text-emerald-600 hover:underline tabular-nums">
                                    {{ $sale->customer?->no_whatsapp }}
                                </a>
                            </td>
                            <td class="px-4 py-3.5 text-slate-600 dark:text-gray-400">
                                {{ $sale->openPo->nama_po ?? '-' }}
                            </td>
                            <td class="px-4 py-3.5 max-w-xs">
                                <div class="text-xs sm:text-sm space-y-1">
                                    @foreach($sale->items as $item)
                                        <div class="truncate">
                                            <span class="font-semibold text-slate-900 dark:text-white tabular-nums">{{ $item->jumlah_paket }}x</span> {{ $item->nama_produk_snapshot }}
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap font-bold text-slate-900 dark:text-white tabular-nums">
                                <div class="text-sm sm:text-base"><x-money :value="$sale->total_tagihan" /></div>
                                @if($sale->remaining_balance > 0)
                                    <button
                                        type="button"
                                        wire:click="openPaymentModal({{ $sale->id }})"
                                        class="block text-xs text-rose-600 dark:text-rose-400 font-bold tabular-nums ml-auto hover:underline cursor-pointer mt-0.5"
                                        title="Klik untuk Catat Pembayaran"
                                    >
                                        Sisa: <x-money :value="$sale->remaining_balance" />
                                    </button>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($sale->status_pembayaran === 'lunas')
                                    <x-badge variant="emerald" :dot="true">LUNAS</x-badge>
                                @elseif($sale->status_pembayaran === 'dp')
                                    <button type="button" wire:click="openPaymentModal({{ $sale->id }})" class="cursor-pointer" title="Klik untuk Catat Pembayaran">
                                        <x-badge variant="blue" :dot="true">DP</x-badge>
                                    </button>
                                @else
                                    <button type="button" wire:click="openPaymentModal({{ $sale->id }})" class="cursor-pointer" title="Klik untuk Catat Pembayaran">
                                        <x-badge variant="rose" :dot="true">BELUM BAYAR</x-badge>
                                    </button>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="space-y-1.5 min-w-[210px]">
                                    <div>
                                        <select
                                            wire:change="updateOrderStatus({{ $sale->id }}, $event.target.value)"
                                            class="text-xs sm:text-sm font-semibold py-1.5 px-2.5 rounded-lg border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-slate-800 dark:text-gray-200 cursor-pointer w-full shadow-2xs"
                                        >
                                            <option value="menunggu_konfirmasi" @selected($sale->status_order === 'menunggu_konfirmasi')>Menunggu Konfirmasi</option>
                                            <option value="diproses" @selected($sale->status_order === 'diproses')>Diproses</option>
                                            <option value="siap_kirim" @selected($sale->status_order === 'siap_kirim')>Siap Kirim</option>
                                            <option value="selesai" @selected($sale->status_order === 'selesai')>Selesai</option>
                                            <option value="dibatalkan" @selected($sale->status_order === 'dibatalkan')>Dibatalkan</option>
                                        </select>
                                    </div>

                                    @if($sale->delivery)
                                        <div class="flex items-center gap-1.5" x-data="{ copied: false }">
                                            <select
                                                wire:change="updateDeliveryStatus({{ $sale->id }}, $event.target.value)"
                                                @class([
                                                    'text-xs font-semibold rounded-lg px-2.5 py-1.5 border cursor-pointer transition-colors flex-1 shadow-2xs',
                                                    'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800' => $sale->delivery->status_pengantaran === 'terkirim',
                                                    'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800' => $sale->delivery->status_pengantaran === 'dalam_pengiriman',
                                                    'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800' => $sale->delivery->status_pengantaran === 'dijadwalkan',
                                                    'bg-slate-50 text-slate-700 border-slate-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700' => $sale->delivery->status_pengantaran === 'menunggu',
                                                ])
                                                title="Status Pengantaran Kurir"
                                            >
                                                <option value="menunggu" @selected($sale->delivery->status_pengantaran === 'menunggu')>Kurir: Menunggu</option>
                                                <option value="dijadwalkan" @selected($sale->delivery->status_pengantaran === 'dijadwalkan')>Kurir: Dijadwalkan</option>
                                                <option value="dalam_pengiriman" @selected($sale->delivery->status_pengantaran === 'dalam_pengiriman')>Kurir: Dalam Pengiriman</option>
                                                <option value="terkirim" @selected($sale->delivery->status_pengantaran === 'terkirim')>Kurir: Terkirim</option>
                                            </select>

                                            <!-- Tombol Info Kurir di bawah status order -->
                                            <button
                                                type="button"
                                                x-on:click="navigator.clipboard.writeText(`{{ addslashes($sale->delivery->format_kurir) }}`); copied = true; setTimeout(() => copied = false, 2500)"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer shadow-2xs border shrink-0"
                                                :class="copied ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700'"
                                                title="Salin rincian pesanan untuk WhatsApp kurir"
                                            >
                                                <template x-if="!copied">
                                                    <svg class="w-3.5 h-3.5 text-slate-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                    </svg>
                                                </template>
                                                <template x-if="copied">
                                                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </template>
                                                <span x-text="copied ? 'Tersalin' : 'Info Kurir'"></span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="flex flex-col gap-1.5 items-end justify-center">
                                    @if($sale->remaining_balance > 0)
                                        <button
                                            type="button"
                                            wire:click="openPaymentModal({{ $sale->id }})"
                                            class="w-full max-w-[115px] inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-2xs transition-colors cursor-pointer"
                                            title="Catat Pembayaran Masuk"
                                        >
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <span>Catat Bayar</span>
                                        </button>
                                    @endif

                                    <button
                                        type="button"
                                        wire:click="viewReceipt({{ $sale->id }})"
                                        class="w-full max-w-[115px] inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-slate-50 dark:bg-gray-800 hover:bg-slate-100 dark:hover:bg-gray-750 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-700 font-semibold text-xs transition-colors cursor-pointer shadow-2xs"
                                        title="Lihat / Cetak Struk Nota"
                                    >
                                        <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        <span>Nota Struk</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                Tidak ada data pesanan penjualan yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Pagination -->
    <div>
        {{ $sales->links() }}
    </div>
    </div> <!-- /#sales-page-content (Hidden on print) -->

    <!-- Modal Nota Struk Pesanan Bersih (Printable) -->
    <div
        id="receipt-modal-wrapper"
        x-data="{ show: @entangle('showReceiptModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center print:static print:inset-auto print:p-0 print:block print:overflow-visible print:z-auto"
        style="display: none;"
        @keydown.escape.window="show = false; $wire.closeReceiptModal()"
    >
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/60 dark:bg-black/75 backdrop-blur-xs transition-opacity print:hidden no-print"
            @click="show = false; $wire.closeReceiptModal()"
        ></div>

        <div
            id="receipt-card-panel"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-lg shadow-2xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all z-10 text-slate-800 dark:text-slate-200 print:static print:transform-none print:shadow-none print:border print:border-slate-300 print:rounded-xl print:max-w-[440px] print:mx-auto print:bg-white print:text-slate-900 print:overflow-visible"
        >
            @if($selectedSale)
                <!-- Action Header (Hidden when printing) -->
                <div class="px-5 py-3.5 bg-slate-50 dark:bg-gray-800/80 border-b border-slate-200 dark:border-gray-700 flex items-center justify-between print:hidden no-print">
                    <span class="text-xs font-semibold text-slate-800 dark:text-white">Pratinjau Nota Pesanan</span>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            onclick="window.print()"
                            class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-xs cursor-pointer flex items-center gap-1.5 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Cetak Struk</span>
                        </button>
                        <button type="button" @click="show = false; $wire.closeReceiptModal()" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-750 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Printable Receipt Content -->
                <div id="receipt-content" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto print:max-h-none print:overflow-visible print:p-6 print:bg-white text-slate-800 dark:text-gray-200 text-xs">
                    <!-- Store Header -->
                    <div class="text-center pb-4 border-b border-dashed border-slate-300 dark:border-gray-700">
                        <h2 class="text-base font-extrabold tracking-tight text-slate-900 dark:text-white">
                            {{ $storeSettings['store_name'] ?? 'SORASA Kitchen & Pastry' }}
                        </h2>
                        <p class="text-[11px] text-slate-500 dark:text-gray-400 mt-0.5">{{ $storeSettings['store_tagline'] ?? 'Spesialis Open PO Kuliner' }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-gray-400">{{ $storeSettings['store_address'] ?? 'Bandung' }}</p>
                        <p class="text-[11px] font-mono font-medium text-slate-700 dark:text-gray-300">WhatsApp: {{ $storeSettings['store_phone'] ?? '081234567890' }}</p>
                    </div>

                    <!-- Invoice & Customer Info -->
                    <div class="space-y-1 py-1 border-b border-dashed border-slate-300 dark:border-gray-700">
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-gray-400">No. Nota:</span>
                            <span class="font-bold font-mono text-slate-900 dark:text-white">{{ $selectedSale->nomor_order }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-gray-400">Tanggal Order:</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ $selectedSale->tanggal_pemesanan->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-gray-400">Batch PO:</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ $selectedSale->openPo->nama_po ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-gray-400">Pelanggan:</span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $selectedSale->customer->nama ?? '-' }}</span>
                        </div>
                        @if($selectedSale->delivery)
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-gray-400">Jadwal Kirim:</span>
                                <span class="font-bold text-blue-700 dark:text-blue-400">
                                    {{ $selectedSale->delivery->tanggal_pengiriman->format('d M Y') }}
                                    @if($selectedSale->delivery->jam_pengiriman)
                                        ({{ $selectedSale->delivery->jam_pengiriman }})
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-gray-400">Alamat Kirim:</span>
                                <span class="text-slate-700 dark:text-gray-300 text-right max-w-xs">{{ $selectedSale->delivery->alamat_lengkap }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Line Items -->
                    <div class="space-y-2 py-2 border-b border-dashed border-slate-300 dark:border-gray-700">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="text-slate-400 dark:text-gray-400 font-semibold border-b border-slate-200 dark:border-gray-700">
                                    <th class="text-left pb-1.5">Menu Makanan</th>
                                    <th class="text-center pb-1.5 w-12">Qty</th>
                                    <th class="text-right pb-1.5 w-24">Harga</th>
                                    <th class="text-right pb-1.5 w-24">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-gray-800">
                                @foreach($selectedSale->items as $item)
                                    <tr>
                                        <td class="py-1.5 font-semibold text-slate-900 dark:text-white">{{ $item->nama_produk_snapshot }}</td>
                                        <td class="py-1.5 text-center font-mono tabular-nums">{{ $item->jumlah_paket }}</td>
                                        <td class="py-1.5 text-right font-mono text-slate-600 dark:text-gray-400 tabular-nums"><x-money :value="$item->harga_jual" /></td>
                                        <td class="py-1.5 text-right font-mono font-bold text-slate-900 dark:text-white tabular-nums"><x-money :value="$item->subtotal" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Calculations -->
                    <div class="space-y-1.5 py-2 border-b border-dashed border-slate-300 dark:border-gray-700 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-gray-400">Subtotal Menu:</span>
                            <span class="font-mono font-medium text-slate-900 dark:text-white tabular-nums"><x-money :value="$selectedSale->subtotal" /></span>
                        </div>
                        @if((float)$selectedSale->diskon > 0)
                            <div class="flex justify-between text-rose-600 dark:text-rose-400">
                                <span>Potongan Diskon:</span>
                                <span class="font-mono tabular-nums">- <x-money :value="$selectedSale->diskon" /></span>
                            </div>
                        @endif
                        @if((float)$selectedSale->ongkos_kirim > 0)
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-gray-400">Ongkos Kirim:</span>
                                <span class="font-mono font-medium text-slate-900 dark:text-white tabular-nums">+ <x-money :value="$selectedSale->ongkos_kirim" /></span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm font-extrabold pt-1.5 border-t border-slate-200 dark:border-gray-700">
                            <span>TOTAL TAGIHAN:</span>
                            <span class="font-mono text-slate-950 dark:text-white tabular-nums"><x-money :value="$selectedSale->total_tagihan" /></span>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="space-y-1.5 py-2 border-b border-dashed border-slate-300 dark:border-gray-700 text-xs">
                        <div class="flex justify-between font-semibold">
                            <span class="text-slate-600 dark:text-gray-300">Total Pembayaran Masuk:</span>
                            <span class="font-mono text-emerald-600 dark:text-emerald-400 tabular-nums"><x-money :value="$selectedSale->total_paid" /></span>
                        </div>
                        <div class="flex justify-between font-extrabold {{ $selectedSale->remaining_balance > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                            <span>SISA TAGIHAN:</span>
                            <span class="font-mono tabular-nums"><x-money :value="$selectedSale->remaining_balance" /></span>
                        </div>
                    </div>

                    <!-- Bank Payment Info -->
                    <div class="p-3 bg-slate-50 dark:bg-gray-800/80 rounded-lg border border-slate-200 dark:border-gray-700 text-center space-y-2">
                        <p class="text-[11px] font-bold text-slate-800 dark:text-gray-200 uppercase tracking-wider">Rekening Pembayaran Resmi:</p>
                        @if(!empty($bankAccounts))
                            <div class="space-y-1.5 divide-y divide-slate-100 dark:divide-gray-750">
                                @foreach($bankAccounts as $acc)
                                    <div class="pt-1 first:pt-0">
                                        <p class="text-xs font-mono font-bold text-slate-900 dark:text-white tabular-nums">
                                            {{ $acc['bank_name'] }} — {{ $acc['account_number'] }}
                                            @if(!empty($acc['is_primary']))
                                                <span class="text-[9px] uppercase px-1.5 py-0.2 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 rounded font-bold font-sans">Utama</span>
                                            @endif
                                        </p>
                                        <p class="text-[11px] text-slate-600 dark:text-gray-400">a.n. {{ $acc['account_name'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs font-mono font-bold text-slate-900 dark:text-white tabular-nums">
                                {{ $storeSettings['bank_name'] ?? 'BCA' }} — {{ $storeSettings['bank_account_number'] ?? '8420198273' }}
                            </p>
                            <p class="text-[11px] text-slate-600 dark:text-gray-400">a.n. {{ $storeSettings['bank_account_name'] ?? 'SORASA KULINER NUSANTARA' }}</p>
                        @endif
                    </div>

                    <div class="text-center text-[10px] text-slate-400 dark:text-gray-500 pt-2">
                        <p>Terima kasih atas pesanan Anda di {{ $storeSettings['store_name'] ?? 'SORASA' }}!</p>
                        <p class="mt-0.5">Simpan nota ini sebagai bukti pemesanan yang sah.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL CATAT PEMBAYARAN MASUK -->
    <div
        x-data="{ show: @entangle('showPaymentModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center print:hidden no-print"
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
            @if($paymentSale)
                <div class="px-5 py-3.5 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Catat Pembayaran Masuk
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-gray-400 font-mono tabular-nums">
                            Order #{{ $paymentSale->nomor_order }} — {{ $paymentSale->customer->nama ?? 'Umum' }}
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
                        <div class="font-bold text-slate-900 dark:text-white mt-0.5 tabular-nums">@rupiah($paymentSale->total_tagihan)</div>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 dark:text-gray-400 uppercase font-semibold">Sudah Masuk</span>
                        <div class="font-bold text-emerald-600 dark:text-emerald-400 mt-0.5 tabular-nums">@rupiah($paymentSale->total_paid)</div>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 dark:text-gray-400 uppercase font-semibold">Sisa Piutang</span>
                        <div class="font-bold text-rose-600 dark:text-rose-400 mt-0.5 tabular-nums">@rupiah($paymentSale->remaining_balance)</div>
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
                                Lunaskan (@rupiah($paymentSale->remaining_balance))
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
                            class="w-full text-xs text-slate-500 dark:text-gray-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-100 dark:file:bg-gray-800 file:text-slate-700 dark:file:text-gray-300 hover:file:bg-slate-200 dark:hover:file:bg-gray-750 cursor-pointer"
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
                            <span wire:loading.remove wire:target="savePayment">Simpan Pembayaran</span>
                            <span wire:loading wire:target="savePayment">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- Print Stylesheet: Ensures clean, single-page receipt output without website screenshot clutter -->
    <style>
        @media print {
            @page {
                size: portrait;
                margin: 8mm auto;
            }

            html, body {
                background: #ffffff !important;
                background-color: #ffffff !important;
                color: #0f172a !important;
                margin: 0 !important;
                padding: 0 !important;
                height: auto !important;
                min-height: auto !important;
                overflow: visible !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            aside,
            header,
            nav,
            .no-print,
            .print\:hidden,
            #sales-page-content {
                display: none !important;
            }

            .sidebar-expanded,
            div[class*="min-h-screen"],
            div[class*="flex-1"],
            main {
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
                height: auto !important;
                overflow: visible !important;
            }

            #receipt-modal-wrapper {
                position: static !important;
                display: block !important;
                padding: 0 !important;
                margin: 0 auto !important;
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                min-height: auto !important;
                overflow: visible !important;
                inset: auto !important;
                z-index: auto !important;
                background: transparent !important;
            }

            #receipt-card-panel {
                position: static !important;
                display: block !important;
                width: 100% !important;
                max-width: 440px !important;
                margin: 0 auto !important;
                padding: 0 !important;
                border: 1px solid #cbd5e1 !important;
                border-radius: 12px !important;
                box-shadow: none !important;
                background: #ffffff !important;
                color: #0f172a !important;
                overflow: visible !important;
                transform: none !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            #receipt-content {
                max-height: none !important;
                height: auto !important;
                overflow: visible !important;
                padding: 24px !important;
                background: #ffffff !important;
                color: #0f172a !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            #receipt-content * {
                color: inherit;
            }

            #receipt-content .text-slate-900,
            #receipt-content .text-slate-950,
            #receipt-content .text-slate-800 {
                color: #0f172a !important;
            }

            #receipt-content .text-slate-500,
            #receipt-content .text-slate-600 {
                color: #475467 !important;
            }

            #receipt-content .text-emerald-800,
            #receipt-content .text-emerald-700,
            #receipt-content .text-emerald-600 {
                color: #059669 !important;
            }

            #receipt-content .text-rose-700,
            #receipt-content .text-rose-600 {
                color: #e11d48 !important;
            }

            #receipt-content .text-blue-700,
            #receipt-content .text-blue-600 {
                color: #2563eb !important;
            }

            #receipt-content .bg-slate-50 {
                background-color: #f8fafc !important;
                border-color: #e2e8f0 !important;
            }

            #receipt-content tr,
            #receipt-content div {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>
</div>
