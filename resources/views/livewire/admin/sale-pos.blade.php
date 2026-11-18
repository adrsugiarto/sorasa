<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Kasir POS — Entri Pesanan Open PO</h1>
            <p class="text-xs text-slate-500 mt-1">Pilih menu makanan, masukkan data pemesan, jadwal pengantaran, dan catat pembayaran awal</p>
        </div>
        <div class="flex items-center gap-2">
            <a
                href="{{ route('admin.sales.index') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-semibold shadow-2xs transition-colors"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                <span>Daftar Pesanan</span>
            </a>
        </div>
    </div>

    <!-- POS Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- LEFT: Catalog Section (7 Cols) -->
        <div class="lg:col-span-7 space-y-4">
            <!-- Filter Bar -->
            <div class="bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                <div class="relative">
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Cari makanan, paket hampers, atau snack..."
                        class="w-full pl-9 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-800 placeholder-slate-400"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Category Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
                    <button
                        type="button"
                        wire:click="$set('selectedCategory', '')"
                        @class([
                            'px-3 py-1 rounded-lg font-semibold whitespace-nowrap transition-colors cursor-pointer',
                            'bg-amber-600 text-white shadow-2xs' => $selectedCategory === '',
                            'bg-slate-100 text-slate-600 hover:bg-slate-200' => $selectedCategory !== '',
                        ])
                    >
                        Semua Menu
                    </button>
                    @foreach($categories as $cat)
                        <button
                            type="button"
                            wire:click="$set('selectedCategory', '{{ $cat }}')"
                            @class([
                                'px-3 py-1 rounded-lg font-semibold whitespace-nowrap transition-colors cursor-pointer',
                                'bg-amber-600 text-white shadow-2xs' => $selectedCategory === $cat,
                                'bg-slate-100 text-slate-600 hover:bg-slate-200' => $selectedCategory !== $cat,
                            ])
                        >
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Product Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 max-h-[68vh] overflow-y-auto pr-1">
                @forelse($products as $product)
                    <div
                        wire:click="addToCart({{ $product->id }})"
                        class="p-4 rounded-xl border border-slate-200/90 bg-white hover:border-amber-400 hover:shadow-xs transition-all cursor-pointer flex flex-col justify-between group relative"
                    >
                        @if(isset($cart[$product->id]))
                            <span class="absolute top-2.5 right-2.5 w-6 h-6 rounded-full bg-amber-600 text-white font-extrabold text-xs flex items-center justify-center shadow-2xs">
                                {{ $cart[$product->id]['qty'] }}
                            </span>
                        @endif

                        <div>
                            <span class="text-[10px] font-semibold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200/50">
                                {{ $product->kategori }}
                            </span>
                            <h3 class="text-xs font-bold text-slate-900 group-hover:text-amber-900 transition-colors mt-2">
                                {{ $product->nama_produk }}
                            </h3>
                            <p class="text-[11px] text-slate-500 line-clamp-2 mt-1 leading-relaxed">
                                {{ $product->deskripsi }}
                            </p>
                        </div>

                        <div class="mt-3.5 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-sm font-extrabold text-slate-900">
                                <x-money :value="$product->harga_jual" />
                            </span>
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-lg group-hover:bg-amber-600 group-hover:text-white transition-colors">
                                + Keranjang
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-white rounded-xl border border-slate-200 text-slate-400 text-xs">
                        Tidak ada menu yang sesuai dengan filter pencarian.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT: Order Checkout & Cart (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
            <x-card class="space-y-5">
                <!-- Batch Open PO Selector -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Pilih Periode Open PO *
                    </label>
                    <select
                        wire:model.live="open_po_id"
                        class="w-full px-3 py-2 text-xs bg-amber-50/50 border border-amber-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-amber-950 font-bold"
                    >
                        <option value="">-- Tanpa Batch PO --</option>
                        @foreach($openPos as $po)
                            <option value="{{ $po->id }}">
                                {{ $po->nama_po }} (Kirim: {{ $po->tanggal_pengiriman->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Customer Selector with Quick Add -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Pelanggan (Pemesan) *
                        </label>
                        <button
                            type="button"
                            wire:click="openQuickCustomerModal"
                            class="text-xs font-semibold text-amber-700 hover:text-amber-900 hover:underline cursor-pointer"
                        >
                            + Tambah Baru
                        </button>
                    </div>

                    <select
                        wire:model.live="customer_id"
                        class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900 font-semibold"
                    >
                        <option value="">-- Pilih Customer Terdaftar --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->nama }} ({{ $c->no_whatsapp }}) — [{{ $c->kategori_pelanggan }}]
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Shopping Cart Items List -->
                <div>
                    <div class="flex items-center justify-between mb-2 pb-1 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Keranjang Menu:</span>
                        @if(count($cart) > 0)
                            <button
                                type="button"
                                wire:click="clearCart"
                                class="text-[11px] font-semibold text-rose-600 hover:underline cursor-pointer"
                            >
                                Kosongkan
                            </button>
                        @endif
                    </div>

                    @error('cart') <p class="text-xs text-rose-600 mb-2">{{ $message }}</p> @enderror

                    @if(count($cart) > 0)
                        <div class="space-y-2.5 max-h-56 overflow-y-auto pr-1 divide-y divide-slate-100">
                            @foreach($cart as $item)
                                <div class="pt-2 flex items-center justify-between gap-2 text-xs">
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-slate-900 truncate">{{ $item['nama_produk'] }}</div>
                                        <span class="text-[11px] text-slate-400 font-mono">
                                            <x-money :value="$item['harga_jual']" /> / paket
                                        </span>
                                    </div>

                                    <!-- Qty Control -->
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button
                                            type="button"
                                            wire:click="decrementQty({{ $item['product_id'] }})"
                                            class="w-6 h-6 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center cursor-pointer"
                                        >
                                            -
                                        </button>
                                        <span class="w-7 text-center font-bold text-slate-900 font-mono">{{ $item['qty'] }}</span>
                                        <button
                                            type="button"
                                            wire:click="incrementQty({{ $item['product_id'] }})"
                                            class="w-6 h-6 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center cursor-pointer"
                                        >
                                            +
                                        </button>
                                    </div>

                                    <div class="w-24 text-right font-extrabold text-slate-900 tabular-nums">
                                        <x-money :value="$item['subtotal']" />
                                    </div>

                                    <button
                                        type="button"
                                        wire:click="removeFromCart({{ $item['product_id'] }})"
                                        class="p-1 text-slate-300 hover:text-rose-600"
                                        title="Hapus dari keranjang"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200 text-slate-400 text-xs">
                            Keranjang masih kosong. Klik menu di katalog kiri untuk menambahkan.
                        </div>
                    @endif
                </div>

                <!-- Financial Adjustment Inputs -->
                <div class="space-y-3 pt-3 border-t border-slate-100 text-xs">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-600 mb-1">Potongan Diskon (Rp)</label>
                            <input
                                wire:model.live.debounce.300ms="diskon"
                                wire:change="syncPaymentNominal"
                                type="number"
                                min="0"
                                step="1000"
                                placeholder="0"
                                class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-right font-mono font-medium focus:bg-white"
                            >
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-600 mb-1">Ongkos Kirim (Rp)</label>
                            <input
                                wire:model.live.debounce.300ms="ongkos_kirim"
                                wire:change="syncPaymentNominal"
                                type="number"
                                min="0"
                                step="1000"
                                placeholder="0"
                                class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-right font-mono font-medium focus:bg-white"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Catatan Pesanan (Opsional)</label>
                        <input
                            wire:model="keterangan_pesanan"
                            type="text"
                            placeholder="Contoh: Sambal pisah, pita hampers warna emas..."
                            class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white"
                        >
                    </div>

                    <!-- Total Breakdown Banner -->
                    <div class="p-4 rounded-xl bg-slate-900 text-white space-y-2">
                        <div class="flex items-center justify-between text-xs text-slate-300">
                            <span>Subtotal Menu:</span>
                            <span class="font-mono"><x-money :value="$this->subtotal" /></span>
                        </div>
                        @if((float)$diskon > 0)
                            <div class="flex items-center justify-between text-xs text-rose-300">
                                <span>Diskon:</span>
                                <span class="font-mono">- <x-money :value="$diskon" /></span>
                            </div>
                        @endif
                        @if((float)$ongkos_kirim > 0)
                            <div class="flex items-center justify-between text-xs text-slate-300">
                                <span>Ongkos Kirim:</span>
                                <span class="font-mono">+ <x-money :value="$ongkos_kirim" /></span>
                            </div>
                        @endif
                        <div class="pt-2 border-t border-slate-700 flex items-center justify-between text-sm font-bold">
                            <span class="text-amber-400">TOTAL TAGIHAN:</span>
                            <span class="text-lg font-extrabold text-white">
                                <x-money :value="$this->totalTagihan" />
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Delivery & Courier Section (Accordion / Collapse with Alpine) -->
                <div x-data="{ open: true }" class="border border-slate-200 rounded-xl overflow-hidden">
                    <button
                        type="button"
                        x-on:click="open = !open"
                        class="w-full px-4 py-2.5 bg-slate-50 hover:bg-slate-100 flex items-center justify-between text-xs font-bold text-slate-800 transition-colors"
                    >
                        <span>Informasi Pengantaran & Alamat Kirim</span>
                        <svg class="w-4 h-4 text-slate-400 transform transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" class="p-4 space-y-3 bg-white text-xs border-t border-slate-100">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-600 mb-1">Tanggal Kirim *</label>
                                <input
                                    wire:model="tanggal_pengiriman"
                                    type="date"
                                    required
                                    class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-slate-800"
                                >
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-600 mb-1">Jam Kirim (Opsional)</label>
                                <input
                                    wire:model="jam_pengiriman"
                                    type="text"
                                    placeholder="Contoh: 11:00 Siang"
                                    class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-slate-800"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-600 mb-1">Nama Penerima *</label>
                                <input
                                    wire:model="nama_penerima"
                                    type="text"
                                    required
                                    class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-slate-800 font-semibold"
                                >
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-600 mb-1">No. Telepon Penerima *</label>
                                <input
                                    wire:model="no_telepon_penerima"
                                    type="text"
                                    required
                                    class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-slate-800 font-mono"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-600 mb-1">Alamat Lengkap *</label>
                            <textarea
                                wire:model="alamat_lengkap"
                                rows="2"
                                required
                                class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-slate-800"
                            ></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-600 mb-1">Patokan Lokasi</label>
                                <input
                                    wire:model="patokan_lokasi"
                                    type="text"
                                    placeholder="Contoh: Pagar abu depan pos"
                                    class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-slate-800"
                                >
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-600 mb-1">Link Google Maps</label>
                                <input
                                    wire:model="link_google_maps"
                                    type="text"
                                    placeholder="https://maps.app.goo.gl/..."
                                    class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-slate-800"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Options Section -->
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3 text-xs">
                    <span class="block font-bold text-slate-800 uppercase tracking-wider">
                        Pembayaran Awal Order:
                    </span>

                    <div class="grid grid-cols-3 gap-2">
                        <label @class([
                            'p-2.5 rounded-xl border text-center cursor-pointer transition-colors flex flex-col items-center justify-center',
                            'bg-amber-600 text-white border-amber-600 font-bold' => $pembayaran_awal === 'belum_bayar',
                            'bg-white text-slate-700 border-slate-200 hover:bg-slate-100' => $pembayaran_awal !== 'belum_bayar',
                        ])>
                            <input wire:model.live="pembayaran_awal" type="radio" value="belum_bayar" class="sr-only">
                            <span class="text-xs">Belum Bayar</span>
                            <span class="text-[10px] opacity-80">(Piutang Penuh)</span>
                        </label>

                        <label @class([
                            'p-2.5 rounded-xl border text-center cursor-pointer transition-colors flex flex-col items-center justify-center',
                            'bg-amber-600 text-white border-amber-600 font-bold' => $pembayaran_awal === 'dp',
                            'bg-white text-slate-700 border-slate-200 hover:bg-slate-100' => $pembayaran_awal !== 'dp',
                        ])>
                            <input wire:model.live="pembayaran_awal" type="radio" value="dp" class="sr-only">
                            <span class="text-xs">DP (Uang Muka)</span>
                            <span class="text-[10px] opacity-80">(Bayar Sebagian)</span>
                        </label>

                        <label @class([
                            'p-2.5 rounded-xl border text-center cursor-pointer transition-colors flex flex-col items-center justify-center',
                            'bg-emerald-600 text-white border-emerald-600 font-bold' => $pembayaran_awal === 'lunas',
                            'bg-white text-slate-700 border-slate-200 hover:bg-slate-100' => $pembayaran_awal !== 'lunas',
                        ])>
                            <input wire:model.live="pembayaran_awal" type="radio" value="lunas" class="sr-only">
                            <span class="text-xs">Lunas Langsung</span>
                            <span class="text-[10px] opacity-80">(Bayar Penuh)</span>
                        </label>
                    </div>

                    @if($pembayaran_awal !== 'belum_bayar')
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Nominal Bayar (Rp) *</label>
                                <input
                                    wire:model="nominal_bayar"
                                    type="number"
                                    min="1"
                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-right font-mono font-bold text-slate-900"
                                >
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Metode Bayar *</label>
                                <select
                                    wire:model="metode_pembayaran"
                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 font-semibold"
                                >
                                    <option value="Transfer BCA">Transfer BCA</option>
                                    <option value="QRIS">QRIS All Payment</option>
                                    <option value="Cash">Cash (Tunai)</option>
                                    <option value="Transfer Mandiri">Transfer Mandiri</option>
                                    <option value="E-Wallet">E-Wallet (GoPay/OVO/ShopeePay)</option>
                                </select>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Checkout Button -->
                <div>
                    <button
                        type="button"
                        wire:click="checkout"
                        wire:loading.attr="disabled"
                        @disabled(count($cart) === 0 || ! $customer_id)
                        class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-extrabold text-sm rounded-xl shadow-xs transition-colors flex items-center justify-center gap-2 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove>PROSES PESANAN (CHECKOUT)</span>
                        <span wire:loading class="inline-flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan Pesanan...
                        </span>
                    </button>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Quick Add Customer Modal -->
    @if($showQuickCustomerModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" wire:click="closeQuickCustomerModal"></div>

            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all z-10">
                <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Cepat Pelanggan Baru</h3>
                    <button type="button" wire:click="closeQuickCustomerModal" class="p-1 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="saveQuickCustomer" class="p-5 space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Pelanggan *</label>
                        <input
                            wire:model="quick_nama"
                            type="text"
                            required
                            placeholder="Nama Lengkap"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900 font-semibold"
                        >
                        @error('quick_nama') <p class="text-[11px] text-rose-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">No. WhatsApp *</label>
                            <input
                                wire:model="quick_no_whatsapp"
                                type="text"
                                required
                                placeholder="081234567890"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg font-mono text-slate-900"
                            >
                            @error('quick_no_whatsapp') <p class="text-[11px] text-rose-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kategori *</label>
                            <select
                                wire:model="quick_kategori"
                                required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900"
                            >
                                <option value="Reguler">Reguler</option>
                                <option value="Reseller">Reseller</option>
                                <option value="VIP">VIP</option>
                                <option value="Keluarga/Teman">Keluarga/Teman</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Alamat Lengkap Pengantaran *</label>
                        <textarea
                            wire:model="quick_alamat"
                            rows="2"
                            required
                            placeholder="Alamat pengiriman pesanan..."
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900"
                        ></textarea>
                        @error('quick_alamat') <p class="text-[11px] text-rose-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button
                            type="button"
                            wire:click="closeQuickCustomerModal"
                            class="px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg font-semibold"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-semibold shadow-2xs"
                        >
                            Simpan & Pilih
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Success Order Modal -->
    @if($showSuccessModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all z-10 text-center p-6 space-y-4">
                <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900">Pesanan Berhasil Disimpan!</h3>
                    <p class="text-xs text-slate-500 mt-1">Nomor Pesanan Faktur:</p>
                    <div class="text-base font-extrabold text-amber-950 font-mono mt-1 bg-amber-50 py-1.5 px-3 rounded-lg inline-block border border-amber-200">
                        {{ $lastOrderNumber }}
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                    <a
                        href="{{ route('admin.sales.index') }}"
                        class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl"
                    >
                        Buka Daftar & Cetak Struk Nota ↗
                    </a>
                    <button
                        type="button"
                        wire:click="resetPos"
                        class="w-full py-2.5 px-4 bg-amber-50 hover:bg-amber-100 text-amber-900 text-xs font-semibold rounded-xl border border-amber-200"
                    >
                        + Buat Transaksi Baru Lagi
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
