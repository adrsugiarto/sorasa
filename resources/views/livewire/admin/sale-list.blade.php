<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Daftar Pesanan Penjualan</h1>
            <p class="text-xs text-slate-500 mt-1">Seluruh riwayat transaksi order, cetak nota struk, dan pantau status pemrosesan</p>
        </div>
        <a
            href="{{ route('admin.sales.pos') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-colors cursor-pointer"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ Order Baru (Kasir POS)</span>
        </a>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2.5 flex-1">
            <div class="relative">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Cari nomor INV atau nama..."
                    class="w-full pl-9 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-800 placeholder-slate-400"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select
                wire:model.live="selectedOpenPoId"
                class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-700 font-medium"
            >
                <option value="">Semua Batch PO</option>
                @foreach($openPos as $po)
                    <option value="{{ $po->id }}">{{ $po->nama_po }}</option>
                @endforeach
            </select>

            <select
                wire:model.live="selectedStatusPembayaran"
                class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-700 font-medium"
            >
                <option value="">Semua Status Bayar</option>
                <option value="lunas">Lunas</option>
                <option value="dp">DP (Uang Muka)</option>
                <option value="belum_bayar">Belum Bayar</option>
            </select>

            <select
                wire:model.live="selectedStatusOrder"
                class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-700 font-medium"
            >
                <option value="">Semua Status Order</option>
                <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                <option value="diproses">Diproses (Dapur)</option>
                <option value="siap_kirim">Siap Kirim</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
        </div>

        <div class="px-3.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-right shrink-0">
            <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold block">Total Omzet Terfilter:</span>
            <span class="text-sm font-extrabold text-slate-900">
                <x-money :value="$totalOmzetFiltered" />
            </span>
        </div>
    </div>

    <!-- Sales Table -->
    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Faktur Order</th>
                        <th class="px-5 py-3.5">Pelanggan</th>
                        <th class="px-5 py-3.5">Batch PO</th>
                        <th class="px-5 py-3.5">Item Pesanan</th>
                        <th class="px-5 py-3.5 text-right">Total Tagihan</th>
                        <th class="px-5 py-3.5">Status Bayar</th>
                        <th class="px-5 py-3.5">Status Order</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                                <div>{{ $sale->nomor_order }}</div>
                                <span class="text-[11px] text-slate-400 font-normal">{{ $sale->tanggal_pemesanan->format('d M Y') }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $sale->customer->nama ?? '-' }}</div>
                                <a href="{{ $sale->customer?->wa_link }}" target="_blank" class="text-[11px] font-mono text-emerald-700 hover:underline">
                                    {{ $sale->customer?->no_whatsapp }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $sale->openPo->nama_po ?? '-' }}
                            </td>
                            <td class="px-5 py-4 max-w-xs">
                                <div class="text-[11px] space-y-0.5">
                                    @foreach($sale->items as $item)
                                        <div class="truncate">
                                            <span class="font-bold text-slate-900">{{ $item->jumlah_paket }}x</span> {{ $item->nama_produk_snapshot }}
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap font-extrabold text-slate-900">
                                <x-money :value="$sale->total_tagihan" />
                                @if($sale->remaining_balance > 0)
                                    <span class="block text-[10px] text-rose-600 font-semibold">
                                        Sisa: <x-money :value="$sale->remaining_balance" />
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($sale->status_pembayaran === 'lunas')
                                    <x-badge variant="emerald" :dot="true">LUNAS</x-badge>
                                @elseif($sale->status_pembayaran === 'dp')
                                    <x-badge variant="amber" :dot="true">DP</x-badge>
                                @else
                                    <x-badge variant="rose" :dot="true">BELUM BAYAR</x-badge>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <select
                                    wire:change="updateOrderStatus({{ $sale->id }}, $event.target.value)"
                                    class="text-[11px] font-semibold rounded-lg px-2 py-1 bg-slate-50 border border-slate-200 text-slate-800 cursor-pointer"
                                >
                                    <option value="menunggu_konfirmasi" @selected($sale->status_order === 'menunggu_konfirmasi')>Menunggu Konfirmasi</option>
                                    <option value="diproses" @selected($sale->status_order === 'diproses')>Diproses</option>
                                    <option value="siap_kirim" @selected($sale->status_order === 'siap_kirim')>Siap Kirim</option>
                                    <option value="selesai" @selected($sale->status_order === 'selesai')>Selesai</option>
                                    <option value="dibatalkan" @selected($sale->status_order === 'dibatalkan')>Dibatalkan</option>
                                </select>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <button
                                    type="button"
                                    wire:click="viewReceipt({{ $sale->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors cursor-pointer"
                                    title="Lihat / Cetak Struk Nota"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    <span>Nota Struk</span>
                                </button>
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

    <!-- Modal Nota Struk Pesanan Bersih (Printable) -->
    @if($showReceiptModal && $selectedSale)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" wire:click="closeReceiptModal"></div>

            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all z-10">
                <!-- Action Header (Hidden when printing) -->
                <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between print:hidden">
                    <span class="text-xs font-bold text-slate-700">Pratinjau Nota Pesanan</span>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            onclick="window.print()"
                            class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg shadow-2xs cursor-pointer flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Cetak Struk</span>
                        </button>
                        <button type="button" wire:click="closeReceiptModal" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Printable Receipt Content -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto print:max-h-none print:overflow-visible text-slate-800 text-xs">
                    <!-- Store Header -->
                    <div class="text-center pb-4 border-b border-dashed border-slate-300">
                        <h2 class="text-base font-extrabold tracking-tight text-slate-900">
                            {{ $storeSettings['store_name'] ?? 'SORASA Kitchen & Pastry' }}
                        </h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $storeSettings['store_tagline'] ?? 'Spesialis Open PO Kuliner' }}</p>
                        <p class="text-[11px] text-slate-500">{{ $storeSettings['store_address'] ?? 'Bandung' }}</p>
                        <p class="text-[11px] font-mono font-medium text-slate-700">WhatsApp: {{ $storeSettings['store_phone'] ?? '081234567890' }}</p>
                    </div>

                    <!-- Invoice & Customer Info -->
                    <div class="space-y-1 py-1 border-b border-dashed border-slate-300">
                        <div class="flex justify-between">
                            <span class="text-slate-500">No. Nota:</span>
                            <span class="font-bold font-mono text-slate-900">{{ $selectedSale->nomor_order }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tanggal Order:</span>
                            <span class="font-medium text-slate-900">{{ $selectedSale->tanggal_pemesanan->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Batch PO:</span>
                            <span class="font-medium text-slate-900">{{ $selectedSale->openPo->nama_po ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Pelanggan:</span>
                            <span class="font-bold text-slate-900">{{ $selectedSale->customer->nama ?? '-' }}</span>
                        </div>
                        @if($selectedSale->delivery)
                            <div class="flex justify-between">
                                <span class="text-slate-500">Jadwal Kirim:</span>
                                <span class="font-bold text-amber-900">
                                    {{ $selectedSale->delivery->tanggal_pengiriman->format('d M Y') }}
                                    @if($selectedSale->delivery->jam_pengiriman)
                                        ({{ $selectedSale->delivery->jam_pengiriman }})
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Alamat Kirim:</span>
                                <span class="text-slate-700 text-right max-w-xs">{{ $selectedSale->delivery->alamat_lengkap }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Line Items -->
                    <div class="space-y-2 py-2 border-b border-dashed border-slate-300">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="text-slate-400 font-semibold border-b border-slate-200">
                                    <th class="text-left pb-1.5">Menu Makanan</th>
                                    <th class="text-center pb-1.5 w-12">Qty</th>
                                    <th class="text-right pb-1.5 w-24">Harga</th>
                                    <th class="text-right pb-1.5 w-24">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($selectedSale->items as $item)
                                    <tr>
                                        <td class="py-1.5 font-semibold text-slate-900">{{ $item->nama_produk_snapshot }}</td>
                                        <td class="py-1.5 text-center font-mono">{{ $item->jumlah_paket }}</td>
                                        <td class="py-1.5 text-right font-mono text-slate-600"><x-money :value="$item->harga_jual" /></td>
                                        <td class="py-1.5 text-right font-mono font-bold text-slate-900"><x-money :value="$item->subtotal" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Calculations -->
                    <div class="space-y-1.5 py-2 border-b border-dashed border-slate-300 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Subtotal Menu:</span>
                            <span class="font-mono font-medium text-slate-900"><x-money :value="$selectedSale->subtotal" /></span>
                        </div>
                        @if((float)$selectedSale->diskon > 0)
                            <div class="flex justify-between text-rose-700">
                                <span>Potongan Diskon:</span>
                                <span class="font-mono">- <x-money :value="$selectedSale->diskon" /></span>
                            </div>
                        @endif
                        @if((float)$selectedSale->ongkos_kirim > 0)
                            <div class="flex justify-between">
                                <span class="text-slate-500">Ongkos Kirim:</span>
                                <span class="font-mono font-medium text-slate-900">+ <x-money :value="$selectedSale->ongkos_kirim" /></span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm font-extrabold pt-1.5 border-t border-slate-200">
                            <span>TOTAL TAGIHAN:</span>
                            <span class="font-mono text-slate-950"><x-money :value="$selectedSale->total_tagihan" /></span>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="space-y-1.5 py-2 border-b border-dashed border-slate-300 text-xs">
                        <div class="flex justify-between font-semibold">
                            <span>Total Pembayaran Masuk:</span>
                            <span class="font-mono text-emerald-800"><x-money :value="$selectedSale->total_paid" /></span>
                        </div>
                        <div class="flex justify-between font-extrabold {{ $selectedSale->remaining_balance > 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                            <span>SISA TAGIHAN (PIUTANG):</span>
                            <span class="font-mono"><x-money :value="$selectedSale->remaining_balance" /></span>
                        </div>
                    </div>

                    <!-- Bank Payment Info -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center space-y-1">
                        <p class="text-[11px] font-bold text-slate-800 uppercase tracking-wider">Rekening Pembayaran Resmi:</p>
                        <p class="text-xs font-mono font-bold text-amber-950">
                            {{ $storeSettings['bank_name'] ?? 'BCA' }} — {{ $storeSettings['bank_account_number'] ?? '8420198273' }}
                        </p>
                        <p class="text-[11px] text-slate-600">a.n. {{ $storeSettings['bank_account_name'] ?? 'SORASA KULINER NUSANTARA' }}</p>
                    </div>

                    <div class="text-center text-[10px] text-slate-400 pt-2">
                        <p>Terima kasih atas pesanan Anda di {{ $storeSettings['store_name'] ?? 'SORASA' }}!</p>
                        <p class="mt-0.5">Simpan nota ini sebagai bukti pemesanan yang sah.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
