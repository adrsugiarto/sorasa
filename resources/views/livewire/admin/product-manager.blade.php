<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Master Data Produk & Menu</h1>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Kelola katalog makanan, dessert, harga jual, dan status aktif kasir POS</p>
        </div>
        <div>
            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-xs transition-colors cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Produk Baru</span>
            </button>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-gray-900 p-3 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs">
        <div class="relative flex-1 max-w-md">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nama produk, kode PRD, atau kategori..."
                class="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800 dark:text-white placeholder-slate-400 font-medium"
            >
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <div class="flex items-center gap-2">
            <select
                wire:model.live="selectedCategory"
                class="px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300 font-medium"
            >
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
        @forelse($products as $product)
            <x-card class="flex flex-col justify-between hover:border-slate-300 dark:hover:border-gray-700 transition-all">
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-[10px] font-semibold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/40 px-2 py-0.5 rounded-md border border-blue-200/80 dark:border-blue-800">
                            {{ $product->kategori }}
                        </span>
                        <span class="text-[11px] font-mono font-medium text-slate-400 dark:text-gray-500 tabular-nums">
                            {{ $product->kode_produk }}
                        </span>
                    </div>

                    @if($product->foto_produk)
                        <div class="w-full h-36 rounded-md overflow-hidden bg-slate-100 dark:bg-gray-800 border border-slate-100 dark:border-gray-800">
                            <img src="{{ asset('storage/' . $product->foto_produk) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-full h-24 rounded-md overflow-hidden bg-slate-50 dark:bg-gray-800/40 border border-dashed border-slate-200 dark:border-gray-800 flex items-center justify-center text-slate-400 dark:text-gray-500">
                            <div class="flex items-center gap-1.5 text-[11px] font-medium">
                                <svg class="w-4 h-4 stroke-[1.25]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Tanpa Foto Menu</span>
                            </div>
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ $product->nama_produk }}</h3>
                        <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5 line-clamp-2 leading-relaxed">
                            {{ $product->deskripsi ?: 'Tidak ada deskripsi.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-gray-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Harga Jual</span>
                            <span class="text-base font-bold text-slate-900 dark:text-white tabular-nums">
                                <x-money :value="$product->harga_jual" />
                            </span>
                        </div>
                        <div class="text-right">
                            @if($product->recipe)
                                <a href="{{ route('admin.recipes') }}" class="inline-block text-[11px] font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-800 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition-colors tabular-nums">
                                    HPP: <x-money :value="$product->recipe->total_biaya_bahan / max(1, $product->recipe->porsi_hasil)" />
                                </a>
                            @else
                                <a href="{{ route('admin.recipes') }}" class="text-[11px] text-blue-600 dark:text-blue-400 hover:underline">
                                    + Buat Resep HPP
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <!-- Active Toggle -->
                        <button
                            type="button"
                            wire:click="toggleActive({{ $product->id }})"
                            @class([
                                'inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium transition-colors cursor-pointer border',
                                'bg-emerald-50 text-emerald-700 border-emerald-200/80 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800' => $product->is_active,
                                'bg-slate-50 text-slate-500 border-slate-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700' => ! $product->is_active,
                            ])
                            title="Klik untuk ubah status aktif di POS"
                        >
                            <span class="w-1.5 h-1.5 rounded-full {{ $product->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                            <span>{{ $product->is_active ? 'Aktif di POS' : 'Nonaktif' }}</span>
                        </button>

                        <!-- Actions -->
                        <div class="flex items-center gap-1">
                            <button
                                type="button"
                                wire:click="openEditModal({{ $product->id }})"
                                class="p-1 rounded-md text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                                title="Edit Produk"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                wire:click="delete({{ $product->id }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus produk '{{ $product->nama_produk }}'?"
                                class="p-1 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer"
                                title="Hapus Produk"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </x-card>
        @empty
            <div class="col-span-full py-12 text-center bg-white dark:bg-gray-900 rounded-lg border border-slate-200 dark:border-gray-800">
                <svg class="w-10 h-10 text-slate-300 dark:text-gray-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <p class="text-xs font-semibold text-slate-700 dark:text-gray-300">Tidak ada produk ditemukan</p>
                <p class="text-[11px] text-slate-400 dark:text-gray-500 mt-0.5">Coba sesuaikan kata kunci pencarian atau buat produk baru.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div>
        {{ $products->links() }}
    </div>

    <!-- Product Modal Form (Livewire controlled) -->
    <div
        x-data="{ show: @entangle('showModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
        style="display: none;"
        @keydown.escape.window="show = false; $wire.closeModal()"
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
            @click="show = false; $wire.closeModal()"
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
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                    {{ $isEditing ? 'Edit Data Produk' : 'Tambah Produk Baru' }}
                </h3>
                <button type="button" @click="show = false; $wire.closeModal()" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-800 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="save">
                <div class="p-5 space-y-3.5 max-h-[75vh] overflow-y-auto text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Kode Produk -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Kode Produk *
                            </label>
                            <input
                                wire:model="kode_produk"
                                type="text"
                                required
                                placeholder="PRD-001"
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-mono text-slate-900 dark:text-white uppercase tabular-nums"
                            >
                            @error('kode_produk') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Kategori *
                            </label>
                            <select
                                wire:model="kategori"
                                required
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                            >
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('kategori') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Nama Produk -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Nama Produk *
                        </label>
                        <input
                            wire:model="nama_produk"
                            type="text"
                            required
                            placeholder="Contoh: Paket Ayam Bakar Madu"
                            class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                        >
                        @error('nama_produk') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Harga Jual -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Harga Jual Satuan (Rp) *
                        </label>
                        <input
                            wire:model="harga_jual"
                            type="number"
                            step="any"
                            min="0"
                            required
                            placeholder="125000"
                            class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-mono font-bold text-slate-900 dark:text-white tabular-nums"
                        >
                        @error('harga_jual') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Foto Produk -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider">
                                Foto Produk / Menu (Opsional)
                            </label>
                            <span class="text-[11px] text-slate-400">Bisa upload nanti / kosongkan</span>
                        </div>
                        <input
                            wire:model="foto"
                            type="file"
                            accept="image/*"
                            class="w-full text-xs text-slate-500 dark:text-gray-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-100 dark:file:bg-gray-800 file:text-slate-700 dark:file:text-gray-300 hover:file:bg-slate-200 dark:hover:file:bg-gray-700 cursor-pointer"
                        >
                        <div wire:loading wire:target="foto" class="text-[11px] text-blue-600 mt-1 font-medium">
                            Mengunggah foto...
                        </div>
                        @error('foto') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror

                        @if($foto)
                            <div class="mt-2 text-[11px] text-slate-600 dark:text-gray-300 flex items-center justify-between p-2 rounded-md bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $foto->temporaryUrl() }}" class="w-12 h-12 rounded-md object-cover border border-slate-200 dark:border-gray-700 shadow-2xs">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">Foto baru dipilih</p>
                                        <p class="text-[10px] text-slate-400">Siap disimpan</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="$set('foto', null)" class="text-[11px] font-medium text-rose-600 hover:underline cursor-pointer">
                                    Batal Ganti Foto
                                </button>
                            </div>
                        @elseif($existingFoto)
                            <div class="mt-2 text-[11px] text-slate-600 dark:text-gray-300 flex items-center justify-between p-2 rounded-md bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('storage/' . $existingFoto) }}" class="w-12 h-12 rounded-md object-cover border border-slate-200 dark:border-gray-700 shadow-2xs">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">Foto produk tersimpan</p>
                                        <p class="text-[10px] text-slate-400">Tampil pada Kasir POS</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="removeFoto" class="text-[11px] font-medium text-rose-600 hover:underline cursor-pointer">
                                    Hapus Foto
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Deskripsi Menu
                        </label>
                        <textarea
                            wire:model="deskripsi"
                            rows="3"
                            placeholder="Komposisi bumbu, rasa, petunjuk penyajian..."
                            class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                        ></textarea>
                        @error('deskripsi') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Checkboxes: Active & Published -->
                    <div class="pt-2 flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input
                                wire:model="is_active"
                                type="checkbox"
                                class="rounded border-slate-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500"
                            >
                            <span class="text-xs font-medium text-slate-700 dark:text-gray-300">Tampilkan di Kasir POS</span>
                        </label>

                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input
                                wire:model="is_published"
                                type="checkbox"
                                class="rounded border-slate-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500"
                            >
                            <span class="text-xs font-medium text-slate-700 dark:text-gray-300">Siap untuk Storefront Publik</span>
                        </label>
                    </div>
                </div>

                <div class="px-5 py-3 bg-slate-50 dark:bg-gray-800/60 border-t border-slate-100 dark:border-gray-800 flex items-center justify-end gap-2">
                    <button
                        type="button"
                        @click="show = false; $wire.closeModal()"
                        class="px-3 py-1.5 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-750 text-slate-700 dark:text-gray-300 text-xs font-medium rounded-md shadow-xs cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-xs disabled:opacity-50 cursor-pointer"
                    >
                        <span wire:loading.remove>{{ $isEditing ? 'Simpan Perubahan' : 'Tambah Produk' }}</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
