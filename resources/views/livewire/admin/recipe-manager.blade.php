<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Resep Standar & Estimasi HPP Bahan</h1>
            <p class="text-xs text-slate-500 mt-1">Standarisasi takaran porsi, perhitungan biaya modal bahan masakan, dan estimasi margin laba</p>
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
                <span>Buat Resep Baru</span>
            </button>
        </div>
    </div>

    <!-- Recipes Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($recipes as $recipe)
            @php
                $porsi = max(1, $recipe->porsi_hasil);
                $hppPerPorsi = $recipe->total_biaya_bahan / $porsi;
                $hargaJual = (float) ($recipe->product->harga_jual ?? 0);
                $marginRp = max(0, $hargaJual - $hppPerPorsi);
                $marginPersen = $hargaJual > 0 ? round(($marginRp / $hargaJual) * 100, 1) : 0;
            @endphp
            <x-card class="flex flex-col justify-between hover:border-slate-300 transition-all">
                <div class="space-y-4">
                    <!-- Recipe Header -->
                    <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-semibold text-amber-800 bg-amber-50 px-2.5 py-0.5 rounded-md border border-amber-200/60">
                                {{ $recipe->product->kategori ?? 'Menu' }}
                            </span>
                            <h3 class="text-sm font-bold text-slate-900 mt-1.5">{{ $recipe->nama_resep }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Produk: <span class="font-semibold text-slate-800">{{ $recipe->product->nama_produk ?? '-' }}</span></p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button
                                type="button"
                                wire:click="openEditModal({{ $recipe->id }})"
                                class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors cursor-pointer"
                                title="Edit Resep"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                wire:click="delete({{ $recipe->id }})"
                                wire:confirm="Yakin ingin menghapus resep '{{ $recipe->nama_resep }}'?"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                title="Hapus Resep"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Cost & Margin Indicators -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/70 text-xs">
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-semibold">Porsi Batch</span>
                            <span class="text-sm font-bold text-slate-900">{{ $recipe->porsi_hasil }} Porsi</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-semibold">Modal Batch</span>
                            <span class="text-sm font-bold text-slate-900"><x-money :value="$recipe->total_biaya_bahan" /></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-semibold">HPP / Porsi</span>
                            <span class="text-sm font-bold text-amber-900"><x-money :value="$hppPerPorsi" /></span>
                        </div>
                        <div>
                            <span class="text-[10px] text-emerald-700 uppercase tracking-wider block font-semibold">Est. Margin</span>
                            <span class="text-sm font-extrabold text-emerald-700">{{ $marginPersen }}%</span>
                        </div>
                    </div>

                    <!-- Ingredients Breakdown List -->
                    <div class="space-y-1.5">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rincian Bahan Baku:</span>
                        <div class="border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100 text-xs">
                            @foreach($recipe->items as $item)
                                <div class="px-3.5 py-2 flex items-center justify-between text-slate-700">
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        <span class="font-medium text-slate-900">{{ $item->nama_bahan }}</span>
                                        <span class="text-slate-400 text-[11px]">({{ number_format($item->qty, 2) }} {{ $item->satuan }})</span>
                                    </div>
                                    <div class="font-bold text-slate-800">
                                        <x-money :value="$item->harga_total" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($recipe->catatan_pembuatan)
                        <div class="p-3 rounded-xl bg-amber-50/50 border border-amber-200/60 text-xs text-amber-900">
                            <span class="font-bold">Tips Masak / Instruksi:</span>
                            <p class="mt-0.5 text-slate-600 line-clamp-2">{{ $recipe->catatan_pembuatan }}</p>
                        </div>
                    @endif
                </div>
            </x-card>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-sm font-semibold text-slate-700">Belum ada resep bahan tercatat</p>
                <p class="text-xs text-slate-400 mt-1">Buat resep baru untuk menghitung estimasi HPP modal per porsi menu makanan Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- Modal Form Tambah / Edit Resep -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" wire:click="closeModal"></div>

            <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all z-10">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">
                            {{ $isEditing ? 'Edit Resep Standar' : 'Buat Resep Standar Baru' }}
                        </h3>
                        <p class="text-xs text-slate-500">Kalkulasi modal bahan baku per batch dan per porsi</p>
                    </div>
                    <button type="button" wire:click="closeModal" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                        <!-- Top Metadata -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Pilih Produk Menu *
                                </label>
                                <select
                                    wire:model.live="product_id"
                                    required
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900 font-semibold"
                                >
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->nama_produk }} ({{ format_rupiah($prod->harga_jual) }})</option>
                                    @endforeach
                                </select>
                                @error('product_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Nama Resep *
                                </label>
                                <input
                                    wire:model="nama_resep"
                                    type="text"
                                    required
                                    placeholder="Contoh: Resep Nasi Liwet Ayam Bakar"
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900"
                                >
                                @error('nama_resep') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Hasil Porsi per Batch *
                                </label>
                                <input
                                    wire:model.live="porsi_hasil"
                                    type="number"
                                    min="1"
                                    required
                                    placeholder="10"
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-bold text-slate-900 tabular-nums"
                                >
                                @error('porsi_hasil') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Repeater Bahan Baku -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider">
                                    Daftar Komponen Bahan Baku:
                                </label>
                                <button
                                    type="button"
                                    wire:click="addIngredient"
                                    class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 text-xs font-semibold rounded-lg transition-colors cursor-pointer"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Tambah Baris Bahan</span>
                                </button>
                            </div>

                            @error('items') <p class="text-xs text-rose-600 mb-2">{{ $message }}</p> @enderror

                            <div class="border border-slate-200 rounded-xl overflow-hidden">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold border-b border-slate-200">
                                        <tr>
                                            <th class="px-3.5 py-2.5">Nama Bahan</th>
                                            <th class="px-3 py-2.5 w-24">Jumlah</th>
                                            <th class="px-3 py-2.5 w-24">Satuan</th>
                                            <th class="px-3 py-2.5 w-32">Harga Satuan (Rp)</th>
                                            <th class="px-3.5 py-2.5 text-right w-32">Subtotal (Rp)</th>
                                            <th class="px-2 py-2.5 text-center w-10"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-slate-700">
                                        @foreach($items as $index => $item)
                                            <tr class="hover:bg-slate-50/50">
                                                <td class="p-2">
                                                    <input
                                                        wire:model="items.{{ $index }}.nama_bahan"
                                                        type="text"
                                                        required
                                                        placeholder="Contoh: Daging Sapi Has Dalam"
                                                        class="w-full px-2.5 py-1.5 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-amber-500"
                                                    >
                                                    @error("items.{$index}.nama_bahan") <p class="text-[10px] text-rose-600">{{ $message }}</p> @enderror
                                                </td>
                                                <td class="p-2">
                                                    <input
                                                        wire:model.live.debounce.300ms="items.{{ $index }}.qty"
                                                        wire:change="calculateRowTotal({{ $index }})"
                                                        type="number"
                                                        step="0.01"
                                                        min="0.01"
                                                        required
                                                        placeholder="1"
                                                        class="w-full px-2 py-1.5 text-xs bg-white border border-slate-200 rounded-lg text-right font-mono focus:outline-none focus:ring-1 focus:ring-amber-500"
                                                    >
                                                </td>
                                                <td class="p-2">
                                                    <select
                                                        wire:model="items.{{ $index }}.satuan"
                                                        required
                                                        class="w-full px-2 py-1.5 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-amber-500"
                                                    >
                                                        @foreach($unitOptions as $unit)
                                                            <option value="{{ $unit }}">{{ $unit }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="p-2">
                                                    <input
                                                        wire:model.live.debounce.300ms="items.{{ $index }}.harga_per_satuan"
                                                        wire:change="calculateRowTotal({{ $index }})"
                                                        type="number"
                                                        step="100"
                                                        min="0"
                                                        required
                                                        placeholder="10000"
                                                        class="w-full px-2 py-1.5 text-xs bg-white border border-slate-200 rounded-lg text-right font-mono focus:outline-none focus:ring-1 focus:ring-amber-500"
                                                    >
                                                </td>
                                                <td class="px-3.5 py-2 text-right font-bold text-slate-900 tabular-nums">
                                                    <x-money :value="((float)($item['qty'] ?? 0)) * ((float)($item['harga_per_satuan'] ?? 0))" />
                                                </td>
                                                <td class="p-2 text-center">
                                                    @if(count($items) > 1)
                                                        <button
                                                            type="button"
                                                            wire:click="removeIngredient({{ $index }})"
                                                            class="p-1 rounded text-slate-300 hover:text-rose-600 transition-colors"
                                                            title="Hapus baris"
                                                        >
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Live Calculation Summary Card -->
                        <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200/80">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                                <div>
                                    <span class="text-slate-500 block">Total Biaya Bahan 1 Batch:</span>
                                    <span class="text-base font-extrabold text-slate-900">
                                        <x-money :value="$this->totalCost" />
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Estimasi HPP per Porsi:</span>
                                    <span class="text-base font-extrabold text-amber-900">
                                        <x-money :value="$this->costPerPortion" />
                                    </span>
                                </div>
                                <div>
                                    @php
                                        $selectedProd = $product_id ? $products->firstWhere('id', $product_id) : null;
                                        $hJual = $selectedProd ? (float)$selectedProd->harga_jual : 0;
                                        $marginEst = max(0, $hJual - $this->costPerPortion);
                                        $persenEst = $hJual > 0 ? round(($marginEst / $hJual) * 100, 1) : 0;
                                    @endphp
                                    <span class="text-slate-500 block">Est. Margin Laba per Porsi:</span>
                                    <span class="text-base font-extrabold text-emerald-700">
                                        <x-money :value="$marginEst" /> ({{ $persenEst }}%)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Pembuatan -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Catatan Pembuatan & Standar Porsi (Opsional)
                            </label>
                            <textarea
                                wire:model="catatan_pembuatan"
                                rows="2"
                                placeholder="Tuliskan catatan teknis memasak, waktu rebus, suhu oven, atau tips persiapan..."
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900"
                            ></textarea>
                            @error('catatan_pembuatan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
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
                            <span wire:loading.remove>{{ $isEditing ? 'Simpan Perubahan Resep' : 'Simpan Resep' }}</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
