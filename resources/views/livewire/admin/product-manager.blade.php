<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Master Data Produk & Menu</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola katalog makanan, dessert, harga jual, dan status aktif kasir POS</p>
        </div>
        <div>
            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Produk Baru</span>
            </button>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs">
        <div class="relative flex-1 max-w-md">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nama produk, kode PRD, atau kategori..."
                class="w-full pl-9 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-800 placeholder-slate-400"
            >
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <div class="flex items-center gap-2">
            <select
                wire:model.live="selectedCategory"
                class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-700"
            >
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($products as $product)
            <x-card class="flex flex-col justify-between hover:border-slate-300 transition-all">
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-[10px] font-semibold text-amber-800 bg-amber-50 px-2.5 py-0.5 rounded-md border border-amber-200/60">
                            {{ $product->kategori }}
                        </span>
                        <span class="text-[11px] font-mono font-medium text-slate-400">
                            {{ $product->kode_produk }}
                        </span>
                    </div>

                    @if($product->foto_produk)
                        <div class="w-full h-36 rounded-lg overflow-hidden bg-slate-100 border border-slate-100">
                            <img src="{{ asset('storage/' . $product->foto_produk) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-bold text-slate-900">{{ $product->nama_produk }}</h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ $product->deskripsi ?: 'Tidak ada deskripsi.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Harga Jual</span>
                            <span class="text-base font-extrabold text-slate-900">
                                <x-money :value="$product->harga_jual" />
                            </span>
                        </div>
                        <div class="text-right">
                            @if($product->recipe)
                                <a href="{{ route('admin.recipes') }}" class="inline-block text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200/60 hover:bg-emerald-100 transition-colors">
                                    Resep HPP: <x-money :value="$product->recipe->total_biaya_bahan / max(1, $product->recipe->porsi_hasil)" />
                                </a>
                            @else
                                <a href="{{ route('admin.recipes') }}" class="text-[11px] text-slate-400 hover:text-amber-700 hover:underline">
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
                                'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors cursor-pointer',
                                'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' => $product->is_active,
                                'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' => ! $product->is_active,
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
                                class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors cursor-pointer"
                                title="Edit Produk"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                wire:click="delete({{ $product->id }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus produk '{{ $product->nama_produk }}'?"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                title="Hapus Produk"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </x-card>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <p class="text-sm font-semibold text-slate-700">Tidak ada produk ditemukan</p>
                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau buat produk baru.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div>
        {{ $products->links() }}
    </div>

    <!-- Product Modal Form (Livewire controlled) -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" wire:click="closeModal"></div>

            <div class="relative w-full max-w-xl bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all z-10">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $isEditing ? 'Edit Data Produk' : 'Tambah Produk Baru' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Kode Produk -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Kode Produk *
                                </label>
                                <input
                                    wire:model="kode_produk"
                                    type="text"
                                    required
                                    placeholder="PRD-001"
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-mono text-slate-900 uppercase"
                                >
                                @error('kode_produk') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Kategori *
                                </label>
                                <select
                                    wire:model="kategori"
                                    required
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900"
                                >
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                                @error('kategori') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Nama Produk -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Nama Menu / Paket *
                            </label>
                            <input
                                wire:model="nama_produk"
                                type="text"
                                required
                                placeholder="Contoh: Nasi Liwet Komplit Ayam Bakar"
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900"
                            >
                            @error('nama_produk') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Harga Jual -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Harga Jual Satuan (Rp) *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-xs font-semibold text-slate-400">Rp</span>
                                <input
                                    wire:model="harga_jual"
                                    type="number"
                                    min="0"
                                    step="500"
                                    required
                                    placeholder="45000"
                                    class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900 tabular-nums font-semibold"
                                >
                            </div>
                            @error('harga_jual') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Deskripsi Produk & Porsi
                            </label>
                            <textarea
                                wire:model="deskripsi"
                                rows="3"
                                placeholder="Tuliskan isi lauk, kemasan, atau detail paket makanan..."
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900"
                            ></textarea>
                            @error('deskripsi') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Foto Produk -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Foto Produk
                            </label>
                            <input
                                wire:model="foto"
                                type="file"
                                accept="image/*"
                                class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200"
                            >
                            @error('foto') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror

                            @if($foto)
                                <div class="mt-2 text-xs text-slate-500 flex items-center gap-2">
                                    <span>Preview baru:</span>
                                    <img src="{{ $foto->temporaryUrl() }}" class="w-12 h-12 rounded object-cover border">
                                </div>
                            @elseif($existingFoto)
                                <div class="mt-2 text-xs text-slate-500 flex items-center gap-2">
                                    <span>Foto saat ini:</span>
                                    <img src="{{ asset('storage/' . $existingFoto) }}" class="w-12 h-12 rounded object-cover border">
                                </div>
                            @endif
                        </div>

                        <!-- Checkboxes: Active & Published -->
                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer">
                                <input wire:model="is_active" type="checkbox" class="w-4 h-4 rounded text-amber-600 border-slate-300 focus:ring-amber-500">
                                <div>
                                    <div class="text-xs font-semibold text-slate-900">Aktif di Kasir POS</div>
                                    <div class="text-[10px] text-slate-500">Bisa dipilih saat order</div>
                                </div>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer">
                                <input wire:model="is_published" type="checkbox" class="w-4 h-4 rounded text-amber-600 border-slate-300 focus:ring-amber-500">
                                <div>
                                    <div class="text-xs font-semibold text-slate-900">Tampilkan di Web</div>
                                    <div class="text-[10px] text-slate-500">Untuk storefront publik</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-xl shadow-xs disabled:opacity-50"
                        >
                            <span wire:loading.remove>{{ $isEditing ? 'Simpan Perubahan' : 'Tambahkan Produk' }}</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
