<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Kasir POS — Entri Pesanan Open PO</h1>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Pilih menu makanan, masukkan data pemesan, jadwal pengantaran, dan catat pembayaran awal</p>
        </div>
        <div class="flex items-center gap-2">
            <a
                href="{{ route('admin.sales.index') }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium shadow-xs transition-colors dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-750"
            >
                <svg class="w-3.5 h-3.5 text-slate-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                <span>Daftar Pesanan</span>
            </a>
        </div>
    </div>

    <!-- POS Split Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">
        <!-- LEFT: Catalog Section (7 Cols) -->
        <div class="xl:col-span-7 space-y-3.5">
            <!-- Filter Bar -->
            <div class="bg-white dark:bg-gray-900 p-3 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs space-y-2.5">
                <div class="relative">
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Cari makanan, paket hampers, atau snack..."
                        class="w-full pl-9 pr-3.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white placeholder-slate-400 font-medium"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Category Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5 text-xs">
                    <button
                        type="button"
                        wire:click="$set('selectedCategory', '')"
                        @class([
                            'px-2.5 py-1 rounded-md font-medium whitespace-nowrap transition-colors cursor-pointer text-xs',
                            'bg-blue-600 text-white shadow-xs' => $selectedCategory === '',
                            'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-gray-300 hover:bg-slate-200/80' => $selectedCategory !== '',
                        ])
                    >
                        Semua Menu
                    </button>
                    @foreach($categories as $cat)
                        <button
                            type="button"
                            wire:click="$set('selectedCategory', '{{ $cat }}')"
                            @class([
                                'px-2.5 py-1 rounded-md font-medium whitespace-nowrap transition-colors cursor-pointer text-xs',
                                'bg-blue-600 text-white shadow-xs' => $selectedCategory === $cat,
                                'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-gray-300 hover:bg-slate-200/80' => $selectedCategory !== $cat,
                            ])
                        >
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Product Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[calc(100vh-230px)] overflow-y-auto pr-1">
                @forelse($products as $product)
                    <div
                        wire:click="addToCart({{ $product->id }})"
                        class="p-3 rounded-lg border border-slate-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-blue-500/80 hover:shadow-xs transition-all cursor-pointer flex flex-col justify-between group relative"
                    >
                        @if(isset($cart[$product->id]))
                            <span class="absolute top-2.5 right-2.5 z-10 w-5 h-5 rounded-md bg-blue-600 text-white font-bold text-[11px] flex items-center justify-center shadow-xs">
                                {{ $cart[$product->id]['qty'] }}
                            </span>
                        @endif

                        <div class="flex items-start gap-3">
                            <!-- Thumbnail / Foto Makanan Kecil -->
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-md overflow-hidden bg-slate-100 dark:bg-gray-800 border border-slate-200/80 dark:border-gray-700/80 shrink-0 flex items-center justify-center relative shadow-2xs">
                                @if($product->foto_produk)
                                    <img
                                        src="{{ asset('storage/' . $product->foto_produk) }}"
                                        alt="{{ $product->nama_produk }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                        loading="lazy"
                                        onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                    >
                                    <div class="hidden w-full h-full flex items-center justify-center bg-slate-50 dark:bg-gray-800/80 text-slate-400 dark:text-gray-500">
                                        <svg class="w-5 h-5 stroke-[1.25]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @else
                                    <!-- Fallback Icon jika belum/tidak diupload foto makanan -->
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-50 dark:bg-gray-800/60 text-slate-400 dark:text-gray-500">
                                        <svg class="w-6 h-6 stroke-[1.25]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Detail Menu -->
                            <div class="flex-1 min-w-0 pr-4">
                                <span class="text-[10px] font-semibold text-blue-700 bg-blue-50 dark:bg-blue-900/40 dark:text-blue-300 px-2 py-0.5 rounded-md border border-blue-200/60 dark:border-blue-800/60 inline-block">
                                    {{ $product->kategori }}
                                </span>
                                <h3 class="text-xs font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mt-1 line-clamp-1">
                                    {{ $product->nama_produk }}
                                </h3>
                                <p class="text-[11px] text-slate-500 dark:text-gray-400 line-clamp-2 mt-0.5 leading-relaxed">
                                    {{ $product->deskripsi }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-gray-800 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900 dark:text-white tabular-nums">
                                <x-money :value="$product->harga_jual" />
                            </span>
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-blue-700 bg-blue-50 border border-blue-200/60 px-2 py-0.5 rounded-md group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                + Keranjang
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-white dark:bg-gray-900 rounded-lg border border-slate-200 dark:border-gray-800 text-slate-400 text-xs">
                        Tidak ada menu yang sesuai dengan filter pencarian.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT: Order Checkout & Cart (5 Cols) -->
        <div class="xl:col-span-5 space-y-4">
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs p-4 sm:p-5 space-y-4">
                <!-- Section 1: Batch Open PO & Pelanggan -->
                <div class="space-y-3 pb-3 border-b border-slate-100 dark:border-gray-800">
                    <!-- Batch Open PO Selector -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1.5">
                            Pilih Periode Open PO <span class="text-rose-500">*</span>
                        </label>
                        <select
                            wire:model.live="open_po_id"
                            class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                        >
                            <option value="">-- Tanpa Batch PO --</option>
                            @foreach($openPos as $po)
                                <option value="{{ $po->id }}">
                                    [{{ $po->kode_po }}] {{ $po->nama_po }} (Kirim: {{ $po->tanggal_pengiriman->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Searchable Customer Combobox with Quick Add -->
                    <div
                        x-data="{
                            open: false,
                            search: '',
                            customers: {{ Js::from($customers->map(fn($c) => [
                                'id' => $c->id,
                                'nama' => $c->nama,
                                'no_whatsapp' => $c->no_whatsapp,
                                'kategori_pelanggan' => $c->kategori_pelanggan,
                                'alamat' => $c->alamat,
                            ])) }},
                            get selectedId() {
                                return $wire.customer_id;
                            },
                            get selectedCustomer() {
                                return this.customers.find(c => c.id == this.selectedId) || null;
                            },
                            get filteredCustomers() {
                                if (!this.search.trim()) return this.customers;
                                const q = this.search.toLowerCase().trim();
                                return this.customers.filter(c => {
                                    const nama = (c.nama || '').toLowerCase();
                                    const wa = (c.no_whatsapp || '').toLowerCase();
                                    const kat = (c.kategori_pelanggan || '').toLowerCase();
                                    const alm = (c.alamat || '').toLowerCase();
                                    return nama.includes(q) || wa.includes(q) || kat.includes(q) || alm.includes(q);
                                });
                            },
                            openDropdown() {
                                this.open = true;
                                this.search = '';
                                this.$nextTick(() => {
                                    this.$refs.searchInput && this.$refs.searchInput.focus();
                                });
                            },
                            select(id) {
                                this.open = false;
                                this.search = '';
                                $wire.selectCustomer(id);
                            },
                            clear() {
                                this.open = false;
                                this.search = '';
                                $wire.selectCustomer(null);
                            }
                        }"
                        class="relative"
                        @click.outside="open = false"
                        @keydown.escape="open = false"
                    >
                        <!-- Top Label + Tambah Baru Link -->
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300">
                                Pelanggan (Pemesan) <span class="text-rose-500">*</span>
                            </label>
                            <button
                                type="button"
                                wire:click="openQuickCustomerModal"
                                class="text-xs font-medium text-blue-600 hover:text-blue-700 hover:underline cursor-pointer flex items-center gap-1"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah Baru</span>
                            </button>
                        </div>

                        <!-- Dropdown Trigger Box -->
                        <div
                            @click="openDropdown()"
                            class="w-full min-h-[38px] px-3 py-1.5 bg-slate-50 dark:bg-gray-800 border rounded-md cursor-pointer flex items-center justify-between gap-2 transition-all"
                            :class="open ? 'border-blue-600 ring-2 ring-blue-500/20 bg-white dark:bg-gray-900' : 'border-slate-200 dark:border-gray-700 hover:border-slate-300 dark:hover:border-gray-600'"
                        >
                            <!-- When Customer Selected -->
                            <template x-if="selectedCustomer">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <span
                                        class="shrink-0 px-1.5 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider"
                                        :class="{
                                            'bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 border border-purple-200 dark:border-purple-800': selectedCustomer.kategori_pelanggan === 'VIP',
                                            'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200 dark:border-amber-800': selectedCustomer.kategori_pelanggan === 'Reseller',
                                            'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800': selectedCustomer.kategori_pelanggan === 'Keluarga/Teman',
                                            'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border border-blue-200 dark:border-blue-800': selectedCustomer.kategori_pelanggan === 'Reguler' || !selectedCustomer.kategori_pelanggan
                                        }"
                                        x-text="selectedCustomer.kategori_pelanggan || 'Reguler'"
                                    ></span>
                                    <span class="text-xs font-semibold text-slate-900 dark:text-white truncate" x-text="selectedCustomer.nama"></span>
                                    <span class="text-[11px] font-mono text-slate-500 dark:text-gray-400 tabular-nums shrink-0" x-text="'(' + selectedCustomer.no_whatsapp + ')'"></span>
                                </div>
                            </template>

                            <!-- When No Customer Selected -->
                            <template x-if="!selectedCustomer">
                                <div class="flex items-center gap-2 text-xs text-slate-400 dark:text-gray-500">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>-- Pilih atau Cari Customer Terdaftar --</span>
                                </div>
                            </template>

                            <!-- Actions (Clear & Chevron) -->
                            <div class="flex items-center gap-1 shrink-0">
                                <button
                                    x-show="selectedCustomer"
                                    type="button"
                                    @click.stop="clear()"
                                    class="p-1 rounded text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-slate-200/60 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                                    title="Hapus pilihan customer"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <svg
                                    class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Dropdown Search & Options List -->
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                            class="absolute z-50 left-0 right-0 mt-1 bg-white dark:bg-gray-900 rounded-lg border border-slate-200 dark:border-gray-700 shadow-xl overflow-hidden"
                            style="display: none;"
                        >
                            <!-- Search Input Header -->
                            <div class="p-2 border-b border-slate-100 dark:border-gray-800 bg-slate-50/70 dark:bg-gray-800/70">
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>
                                    <input
                                        x-ref="searchInput"
                                        type="text"
                                        x-model="search"
                                        placeholder="Ketik nama, no WA (misal: 0812), atau kategori..."
                                        class="w-full pl-8 pr-7 py-1.5 text-xs bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-600 text-slate-900 dark:text-white placeholder-slate-400 font-medium"
                                        @keydown.enter.prevent="if (filteredCustomers.length === 1) select(filteredCustomers[0].id)"
                                    >
                                    <button
                                        x-show="search.length > 0"
                                        type="button"
                                        @click="search = ''; $refs.searchInput.focus()"
                                        class="absolute inset-y-0 right-0 pr-2 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Options List -->
                            <div class="max-h-56 overflow-y-auto divide-y divide-slate-100 dark:divide-gray-800/60">
                                <template x-for="c in filteredCustomers" :key="c.id">
                                    <div
                                        @click="select(c.id)"
                                        class="p-2.5 hover:bg-blue-50/70 dark:hover:bg-blue-950/30 cursor-pointer flex items-center justify-between gap-2.5 transition-colors group"
                                        :class="selectedId == c.id ? 'bg-blue-50/40 dark:bg-blue-950/20' : ''"
                                    >
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400" x-text="c.nama"></span>
                                                <span class="text-[11px] font-mono text-slate-500 dark:text-gray-400 tabular-nums" x-text="'(' + c.no_whatsapp + ')'"></span>
                                            </div>
                                            <div class="text-[10px] text-slate-400 dark:text-gray-500 truncate mt-0.5" x-text="c.alamat || 'Alamat belum diatur'"></div>
                                        </div>

                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span
                                                class="px-1.5 py-0.5 rounded text-[10px] font-semibold"
                                                :class="{
                                                    'bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 border border-purple-200 dark:border-purple-800': c.kategori_pelanggan === 'VIP',
                                                    'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200 dark:border-amber-800': c.kategori_pelanggan === 'Reseller',
                                                    'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800': c.kategori_pelanggan === 'Keluarga/Teman',
                                                    'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border border-blue-200 dark:border-blue-800': c.kategori_pelanggan === 'Reguler' || !c.kategori_pelanggan
                                                }"
                                                x-text="c.kategori_pelanggan || 'Reguler'"
                                            ></span>

                                            <span x-show="selectedId == c.id" class="text-blue-600 dark:text-blue-400">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </template>

                                <!-- Empty State when no customers match search -->
                                <div x-show="filteredCustomers.length === 0" class="py-6 px-4 text-center">
                                    <div class="w-8 h-8 mx-auto rounded-full bg-slate-100 dark:bg-gray-800 text-slate-400 flex items-center justify-center mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-gray-300 font-medium">Customer tidak ditemukan</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5" x-text="'Tidak ada hasil untuk \"' + search + '\"'"></p>
                                    <button
                                        type="button"
                                        @click="open = false; $wire.openQuickCustomerModal(search)"
                                        class="mt-2.5 inline-flex items-center gap-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-medium shadow-xs cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Daftarkan sebagai Customer Baru</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Footer Shortcut to Add New -->
                            <div class="p-2 bg-slate-50 dark:bg-gray-800/80 border-t border-slate-100 dark:border-gray-800 flex items-center justify-between text-[11px]">
                                <span class="text-slate-400" x-text="filteredCustomers.length + ' customer tersedia'"></span>
                                <button
                                    type="button"
                                    @click="open = false; $wire.openQuickCustomerModal()"
                                    class="text-blue-600 dark:text-blue-400 font-medium hover:underline flex items-center gap-1 cursor-pointer"
                                >
                                    <span>+ Tambah Baru</span>
                                </button>
                            </div>
                        </div>

                        <!-- Fallback / Hidden input for form validation & Livewire sync -->
                        <input type="hidden" name="customer_id" :value="selectedId">
                        @error('customer_id') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Section 2: Shopping Cart Items List -->
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between pb-1 border-b border-slate-100 dark:border-gray-800">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-gray-300">
                            Keranjang Menu @if(count($cart) > 0) <span class="text-blue-600 font-bold tabular-nums">({{ count($cart) }})</span> @endif:
                        </span>
                        @if(count($cart) > 0)
                            <button
                                type="button"
                                wire:click="clearCart"
                                class="text-[11px] font-medium text-rose-600 hover:text-rose-700 hover:underline cursor-pointer"
                            >
                                Kosongkan
                            </button>
                        @endif
                    </div>

                    @error('cart') <p class="text-[11px] text-rose-600">{{ $message }}</p> @enderror

                    @if(count($cart) > 0)
                        <div class="space-y-2 max-h-52 overflow-y-auto pr-1">
                            @foreach($cart as $item)
                                <div class="p-2.5 rounded-md border border-slate-200/80 dark:border-gray-800 bg-slate-50/60 dark:bg-gray-800/40 space-y-1.5 transition-colors">
                                    <div class="flex items-start justify-between gap-2.5">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded overflow-hidden bg-slate-100 dark:bg-gray-700/60 border border-slate-200/80 dark:border-gray-700 shrink-0 flex items-center justify-center">
                                                @if(!empty($item['foto_produk']))
                                                    <img src="{{ asset('storage/' . $item['foto_produk']) }}" alt="{{ $item['nama_produk'] }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-3.5 h-3.5 text-slate-400 dark:text-gray-400 stroke-[1.25]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <span class="font-medium text-xs text-slate-900 dark:text-white leading-tight truncate">
                                                {{ $item['nama_produk'] }}
                                            </span>
                                        </div>
                                        <button
                                            type="button"
                                            wire:click="removeFromCart({{ $item['product_id'] }})"
                                            class="text-slate-400 hover:text-rose-600 p-0.5 rounded transition-colors cursor-pointer shrink-0"
                                            title="Hapus item"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-200/50 dark:border-gray-700/50">
                                        <!-- Qty Stepper Controls -->
                                        <div class="flex items-center gap-1">
                                            <button
                                                type="button"
                                                wire:click="decrementQty({{ $item['product_id'] }})"
                                                class="w-5 h-5 rounded bg-white dark:bg-gray-700 border border-slate-200 dark:border-gray-600 text-slate-700 dark:text-gray-200 font-bold flex items-center justify-center text-xs hover:bg-slate-100 transition-colors cursor-pointer"
                                            >
                                                -
                                            </button>
                                            <span class="w-6 text-center font-bold text-xs text-slate-900 dark:text-white font-mono tabular-nums">{{ $item['qty'] }}</span>
                                            <button
                                                type="button"
                                                wire:click="incrementQty({{ $item['product_id'] }})"
                                                class="w-5 h-5 rounded bg-white dark:bg-gray-700 border border-slate-200 dark:border-gray-600 text-slate-700 dark:text-gray-200 font-bold flex items-center justify-center text-xs hover:bg-slate-100 transition-colors cursor-pointer"
                                            >
                                                +
                                            </button>
                                            <span class="text-[11px] text-slate-400 dark:text-gray-500 font-mono ml-1 tabular-nums">@ <x-money :value="$item['harga_jual']" /></span>
                                        </div>

                                        <span class="text-xs font-bold text-slate-900 dark:text-white font-mono tabular-nums">
                                            <x-money :value="$item['subtotal']" />
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center bg-slate-50 dark:bg-gray-800/40 rounded-md border border-dashed border-slate-200 dark:border-gray-800 text-slate-400 text-xs">
                            Keranjang masih kosong. Klik menu di katalog kiri untuk menambahkan.
                        </div>
                    @endif
                </div>

                <!-- Section 3: Diskon, Ongkir & Catatan -->
                <div class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-gray-800 text-xs">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 dark:text-gray-400 mb-1">Potongan Diskon (Rp)</label>
                            <input
                                wire:model.live.debounce.300ms="diskon"
                                wire:change="syncPaymentNominal"
                                type="number"
                                min="0"
                                step="1000"
                                placeholder="0"
                                class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md text-right font-mono font-medium focus:bg-white dark:focus:bg-gray-900 text-slate-900 dark:text-white tabular-nums"
                            >
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 dark:text-gray-400 mb-1">Ongkos Kirim (Rp)</label>
                            <input
                                wire:model.live.debounce.300ms="ongkos_kirim"
                                wire:change="syncPaymentNominal"
                                type="number"
                                min="0"
                                step="1000"
                                placeholder="0"
                                class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md text-right font-mono font-medium focus:bg-white dark:focus:bg-gray-900 text-slate-900 dark:text-white tabular-nums"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-gray-400 mb-1">Catatan Pesanan (Opsional)</label>
                        <input
                            wire:model="keterangan_pesanan"
                            type="text"
                            placeholder="Contoh: Sambal pisah, pita hampers warna emas..."
                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 text-slate-900 dark:text-white text-xs"
                        >
                    </div>

                    <!-- Section 4: Total Breakdown Panel (Linear / Stripe Style) -->
                    <div class="p-3.5 rounded-md bg-slate-50 dark:bg-gray-800/60 border border-slate-200 dark:border-gray-700 space-y-1.5">
                        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-gray-400">
                            <span>Subtotal Menu:</span>
                            <span class="font-mono text-slate-900 dark:text-white font-medium tabular-nums"><x-money :value="$this->subtotal" /></span>
                        </div>
                        @if((float)$diskon > 0)
                            <div class="flex items-center justify-between text-xs text-rose-600 dark:text-rose-400">
                                <span>Diskon:</span>
                                <span class="font-mono tabular-nums">- <x-money :value="$diskon" /></span>
                            </div>
                        @endif
                        @if((float)$ongkos_kirim > 0)
                            <div class="flex items-center justify-between text-xs text-slate-600 dark:text-gray-400">
                                <span>Ongkos Kirim:</span>
                                <span class="font-mono text-slate-900 dark:text-white font-medium tabular-nums">+ <x-money :value="$ongkos_kirim" /></span>
                            </div>
                        @endif
                        <div class="pt-1.5 border-t border-slate-200 dark:border-gray-700 flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">Total Tagihan:</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400 font-mono tabular-nums">
                                <x-money :value="$this->totalTagihan" />
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Delivery & Courier Section (Accordion / Collapse with Alpine) -->
                <div x-data="{ open: true }" class="border border-slate-200 dark:border-gray-700 rounded-md overflow-hidden">
                    <button
                        type="button"
                        x-on:click="open = !open"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-800/80 hover:bg-slate-100 dark:hover:bg-gray-800 flex items-center justify-between text-xs font-semibold text-slate-800 dark:text-white transition-colors"
                    >
                        <span>Informasi Pengantaran & Alamat Kirim</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transform transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" class="p-3.5 space-y-3 bg-white dark:bg-gray-900 text-xs border-t border-slate-100 dark:border-gray-800">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-gray-400 mb-1">Tanggal Kirim <span class="text-rose-500">*</span></label>
                                <input
                                    wire:model="tanggal_pengiriman"
                                    type="date"
                                    required
                                    class="w-full px-2.5 py-1.5 border border-slate-200 dark:border-gray-700 rounded-md text-slate-900 dark:text-white bg-slate-50 dark:bg-gray-800 focus:bg-white text-xs font-medium"
                                >
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-gray-400 mb-1">Jam Kirim (Opsional)</label>
                                <input
                                    wire:model="jam_pengiriman"
                                    type="text"
                                    placeholder="Contoh: 11:00 Siang"
                                    class="w-full px-2.5 py-1.5 border border-slate-200 dark:border-gray-700 rounded-md text-slate-900 dark:text-white bg-slate-50 dark:bg-gray-800 focus:bg-white text-xs"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-gray-400 mb-1">Nama Penerima <span class="text-rose-500">*</span></label>
                                <input
                                    wire:model="nama_penerima"
                                    type="text"
                                    required
                                    class="w-full px-2.5 py-1.5 border border-slate-200 dark:border-gray-700 rounded-md text-slate-900 dark:text-white bg-slate-50 dark:bg-gray-800 focus:bg-white text-xs font-medium"
                                >
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-gray-400 mb-1">No. Telepon Penerima <span class="text-rose-500">*</span></label>
                                <input
                                    wire:model="no_telepon_penerima"
                                    type="text"
                                    required
                                    class="w-full px-2.5 py-1.5 border border-slate-200 dark:border-gray-700 rounded-md text-slate-900 dark:text-white bg-slate-50 dark:bg-gray-800 focus:bg-white text-xs font-mono tabular-nums"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 dark:text-gray-400 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                            <textarea
                                wire:model="alamat_lengkap"
                                rows="2"
                                required
                                class="w-full px-2.5 py-1.5 border border-slate-200 dark:border-gray-700 rounded-md text-slate-900 dark:text-white bg-slate-50 dark:bg-gray-800 focus:bg-white text-xs leading-relaxed"
                            ></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-gray-400 mb-1">Patokan Lokasi</label>
                                <input
                                    wire:model="patokan_lokasi"
                                    type="text"
                                    placeholder="Contoh: Pagar abu depan pos"
                                    class="w-full px-2.5 py-1.5 border border-slate-200 dark:border-gray-700 rounded-md text-slate-900 dark:text-white bg-slate-50 dark:bg-gray-800 focus:bg-white text-xs"
                                >
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-gray-400 mb-1">Link Google Maps</label>
                                <input
                                    wire:model="link_google_maps"
                                    type="text"
                                    placeholder="https://maps.app.goo.gl/..."
                                    class="w-full px-2.5 py-1.5 border border-slate-200 dark:border-gray-700 rounded-md text-slate-900 dark:text-white bg-slate-50 dark:bg-gray-800 focus:bg-white text-xs"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 6: Payment Options Section -->
                <div class="p-3.5 rounded-md border border-slate-200 dark:border-gray-700 bg-slate-50/50 dark:bg-gray-800/40 space-y-2.5 text-xs">
                    <span class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider">
                        Pembayaran Awal Order:
                    </span>

                    <div class="grid grid-cols-3 gap-2">
                        <label @class([
                            'p-2 rounded-md border text-center cursor-pointer transition-colors flex flex-col items-center justify-center',
                            'bg-slate-200/90 dark:bg-gray-700 text-slate-900 dark:text-white border-slate-300 dark:border-gray-600 font-semibold' => $pembayaran_awal === 'belum_bayar',
                            'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-400 border-slate-200 dark:border-gray-700 hover:bg-slate-50' => $pembayaran_awal !== 'belum_bayar',
                        ])>
                            <input wire:model.live="pembayaran_awal" type="radio" value="belum_bayar" class="sr-only">
                            <span class="text-xs font-semibold">Belum Bayar</span>
                            <span class="text-[10px] opacity-75">(Piutang Penuh)</span>
                        </label>

                        <label @class([
                            'p-2 rounded-md border text-center cursor-pointer transition-colors flex flex-col items-center justify-center',
                            'bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border-blue-300 dark:border-blue-700 font-semibold' => $pembayaran_awal === 'dp',
                            'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-400 border-slate-200 dark:border-gray-700 hover:bg-slate-50' => $pembayaran_awal !== 'dp',
                        ])>
                            <input wire:model.live="pembayaran_awal" type="radio" value="dp" class="sr-only">
                            <span class="text-xs font-semibold">DP (Uang Muka)</span>
                            <span class="text-[10px] opacity-75">(Bayar Sebagian)</span>
                        </label>

                        <label @class([
                            'p-2 rounded-md border text-center cursor-pointer transition-colors flex flex-col items-center justify-center',
                            'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-700 font-semibold' => $pembayaran_awal === 'lunas',
                            'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-400 border-slate-200 dark:border-gray-700 hover:bg-slate-50' => $pembayaran_awal !== 'lunas',
                        ])>
                            <input wire:model.live="pembayaran_awal" type="radio" value="lunas" class="sr-only">
                            <span class="text-xs font-semibold">Lunas Langsung</span>
                            <span class="text-[10px] opacity-75">(Bayar Penuh)</span>
                        </label>
                    </div>

                    @if($pembayaran_awal !== 'belum_bayar')
                        <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-200 dark:border-gray-700">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 mb-1">Nominal Bayar (Rp) <span class="text-rose-500">*</span></label>
                                <input
                                    wire:model="nominal_bayar"
                                    type="number"
                                    min="1"
                                    class="w-full px-2.5 py-1.5 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-md text-right font-mono font-bold text-slate-900 dark:text-white tabular-nums text-xs"
                                >
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 mb-1">Metode Bayar <span class="text-rose-500">*</span></label>
                                <select
                                    wire:model="metode_pembayaran"
                                    class="w-full px-2.5 py-1.5 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-md text-slate-800 dark:text-white font-medium text-xs"
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

                <!-- Section 7: Checkout Button -->
                <div>
                    <button
                        type="button"
                        wire:click="checkout"
                        wire:loading.attr="disabled"
                        @disabled(count($cart) === 0 || ! $customer_id)
                        class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold text-xs rounded-md shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove>Proses & Simpan Pesanan (Checkout)</span>
                        <span wire:loading class="inline-flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan Pesanan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Add Customer Modal -->
    <div
        x-data="{ show: @entangle('showQuickCustomerModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
        style="display: none;"
        @keydown.escape.window="show = false; $wire.closeQuickCustomerModal()"
    >
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
            @click="show = false; $wire.closeQuickCustomerModal()"
        ></div>

        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            class="relative w-full max-w-md bg-white dark:bg-gray-900 rounded-lg shadow-xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all z-10"
        >
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Tambah Cepat Pelanggan Baru</h3>
                <button type="button" @click="show = false; $wire.closeQuickCustomerModal()" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="saveQuickCustomer" class="p-5 space-y-3 text-xs">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 mb-1">Nama Pelanggan <span class="text-rose-500">*</span></label>
                    <input
                        wire:model="quick_nama"
                        type="text"
                        required
                        placeholder="Nama Lengkap"
                        class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md text-slate-900 dark:text-white font-medium"
                    >
                    @error('quick_nama') <p class="text-[11px] text-rose-600 mt-0.5">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 mb-1">No. WhatsApp <span class="text-rose-500">*</span></label>
                        <input
                            wire:model="quick_no_whatsapp"
                            type="text"
                            required
                            placeholder="081234567890"
                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md font-mono text-slate-900 dark:text-white"
                        >
                        @error('quick_no_whatsapp') <p class="text-[11px] text-rose-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 mb-1">Kategori <span class="text-rose-500">*</span></label>
                        <select
                            wire:model="quick_kategori"
                            required
                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md text-slate-900 dark:text-white"
                        >
                            <option value="Reguler">Reguler</option>
                            <option value="Reseller">Reseller</option>
                            <option value="VIP">VIP</option>
                            <option value="Keluarga/Teman">Keluarga/Teman</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 mb-1">Alamat Lengkap Pengantaran <span class="text-rose-500">*</span></label>
                    <textarea
                        wire:model="quick_alamat"
                        rows="2"
                        required
                        placeholder="Alamat pengiriman pesanan..."
                        class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md text-slate-900 dark:text-white leading-relaxed"
                    ></textarea>
                    @error('quick_alamat') <p class="text-[11px] text-rose-600 mt-0.5">{{ $message }}</p> @enderror
                </div>

                <div class="pt-3 border-t border-slate-100 dark:border-gray-800 flex items-center justify-end gap-2">
                    <button
                        type="button"
                        @click="show = false; $wire.closeQuickCustomerModal()"
                        class="px-3 py-1.5 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-gray-300 rounded-md font-medium text-xs shadow-xs cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium text-xs shadow-xs cursor-pointer disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="saveQuickCustomer">Simpan & Pilih</span>
                        <span wire:loading wire:target="saveQuickCustomer">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Order Modal -->
    <div
        x-data="{ show: @entangle('showSuccessModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
        style="display: none;"
        @keydown.escape.window="show = false; $wire.resetPos()"
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
            @click="show = false; $wire.resetPos()"
        ></div>

        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            class="relative w-full max-w-sm bg-white dark:bg-gray-900 rounded-lg shadow-2xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all z-10 text-center p-6 space-y-4"
        >
            <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <div>
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Pesanan Berhasil Disimpan!</h3>
                <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">Nomor Pesanan Faktur:</p>
                <div class="text-sm font-bold text-slate-900 dark:text-white font-mono mt-1 bg-slate-50 dark:bg-gray-800 py-1 px-3 rounded-md inline-block border border-slate-200 dark:border-gray-700 tabular-nums">
                    {{ $lastOrderNumber }}
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 dark:border-gray-800 flex flex-col gap-2">
                <a
                    href="{{ route('admin.sales.index') }}"
                    class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-xs transition-colors"
                >
                    Buka Daftar & Cetak Struk Nota ↗
                </a>
                <button
                    type="button"
                    @click="show = false; $wire.resetPos()"
                    class="w-full py-2 px-3 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-750 text-slate-700 dark:text-gray-300 text-xs font-medium rounded-md border border-slate-200 dark:border-gray-700 shadow-xs transition-colors cursor-pointer"
                >
                    + Buat Transaksi Baru Lagi
                </button>
            </div>
        </div>
    </div>
</div>
