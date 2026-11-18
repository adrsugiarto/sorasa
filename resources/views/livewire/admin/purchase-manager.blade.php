<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Pembelian Bahan Baku (Habis Pakai)</h1>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Pencatatan belanja bahan masakan yang langsung dihitung sebagai biaya operasional (tanpa stok)</p>
        </div>
        <div class="flex items-center gap-2">
            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-xs transition-colors cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Catat Pembelian Bahan</span>
            </button>
        </div>
    </div>

    <!-- Filter & Summary Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 bg-white dark:bg-gray-900 p-3 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 flex-1">
            <div class="relative flex-1 max-w-xs">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Cari barang atau nama toko..."
                    class="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800 dark:text-white placeholder-slate-400 font-medium"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select
                wire:model.live="selectedOpenPoId"
                class="px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300 font-medium"
            >
                <option value="">Semua Batch PO</option>
                @foreach($openPos as $po)
                    <option value="{{ $po->id }}">{{ $po->nama_po }} ({{ $po->kode_po }})</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-3 self-end lg:self-auto">
            <div class="px-3 py-1.5 rounded-md bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-right">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 dark:text-gray-400 font-semibold block">Total Belanja Terfilter:</span>
                <span class="text-sm font-bold text-slate-900 dark:text-white tabular-nums">
                    <x-money :value="$totalFiltered" />
                </span>
            </div>
        </div>
    </div>

    <!-- Purchases Table -->
    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-500 dark:text-gray-400 border-b border-slate-200 dark:border-gray-800 uppercase font-semibold text-[11px] tracking-wider">
                    <tr>
                        <th class="px-3.5 py-2.5">Tanggal</th>
                        <th class="px-3.5 py-2.5">Toko / Supplier</th>
                        <th class="px-3.5 py-2.5">Nama Barang</th>
                        <th class="px-3.5 py-2.5">Kuantitas & Satuan</th>
                        <th class="px-3.5 py-2.5 text-right">Harga Satuan</th>
                        <th class="px-3.5 py-2.5 text-right">Total Biaya</th>
                        <th class="px-3.5 py-2.5">Batch PO</th>
                        <th class="px-3.5 py-2.5 text-center">Nota</th>
                        <th class="px-3.5 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-[13px] leading-5">
                    @forelse($purchases as $p)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="px-3.5 py-2.5 text-slate-600 dark:text-gray-400 font-medium whitespace-nowrap tabular-nums">
                                {{ $p->tanggal_pembelian->format('d M Y') }}
                            </td>
                            <td class="px-3.5 py-2.5 font-medium text-slate-900 dark:text-white">
                                <div>{{ $p->nama_toko_supplier }}</div>
                                <span class="text-[10px] text-slate-400 dark:text-gray-500 font-normal uppercase">{{ $p->metode_pembayaran }}</span>
                            </td>
                            <td class="px-3.5 py-2.5">
                                <div class="font-medium text-slate-900 dark:text-white">{{ $p->nama_barang }}</div>
                                @if($p->keterangan)
                                    <span class="text-[11px] text-slate-400 dark:text-gray-500 block line-clamp-1">{{ $p->keterangan }}</span>
                                @endif
                            </td>
                            <td class="px-3.5 py-2.5 font-mono font-medium text-slate-800 dark:text-gray-200 whitespace-nowrap tabular-nums">
                                {{ number_format($p->jumlah, 2) }} {{ $p->satuan }}
                            </td>
                            <td class="px-3.5 py-2.5 text-right text-slate-600 dark:text-gray-400 whitespace-nowrap tabular-nums">
                                <x-money :value="$p->harga_satuan" />
                            </td>
                            <td class="px-3.5 py-2.5 text-right font-semibold text-slate-900 dark:text-white whitespace-nowrap tabular-nums">
                                <x-money :value="$p->total_pembelian" />
                            </td>
                            <td class="px-3.5 py-2.5 text-slate-500 dark:text-gray-400 whitespace-nowrap">
                                @if($p->openPo)
                                    <span class="text-[10px] font-mono font-medium text-blue-700 bg-blue-50 dark:bg-blue-900/40 dark:text-blue-300 px-2 py-0.5 rounded-md border border-blue-200 dark:border-blue-800 tabular-nums">
                                        {{ $p->openPo->kode_po }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400 dark:text-gray-500">Umum</span>
                                @endif
                            </td>
                            <td class="px-3.5 py-2.5 text-center">
                                @if($p->foto_nota)
                                    <button
                                        type="button"
                                        wire:click="viewReceipt('{{ $p->foto_nota }}', '{{ $p->nama_barang }} - {{ $p->nama_toko_supplier }}')"
                                        class="p-1 rounded text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-950/40 cursor-pointer"
                                        title="Lihat foto nota"
                                    >
                                        <svg class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="text-[10px] text-slate-300 dark:text-gray-600">-</span>
                                @endif
                            </td>
                            <td class="px-3.5 py-2.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $p->id }})"
                                        class="p-1 rounded-md text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                                        title="Edit Pembelian"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="delete({{ $p->id }})"
                                        wire:confirm="Yakin ingin menghapus catatan pembelian '{{ $p->nama_barang }}'?"
                                        class="p-1 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer"
                                        title="Hapus Pembelian"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-3.5 py-8 text-center text-slate-400 dark:text-gray-500">
                                Belum ada pembelian bahan baku yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Pagination -->
    <div>
        {{ $purchases->links() }}
    </div>

    <!-- Modal Form Tambah / Edit Pembelian -->
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
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                        {{ $isEditing ? 'Edit Pembelian Bahan' : 'Catat Pembelian Bahan Baku' }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-gray-400">Biaya habis pakai untuk produksi pesanan Open PO</p>
                </div>
                <button type="button" @click="show = false; $wire.closeModal()" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-800 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="save">
                <div class="p-5 space-y-3.5 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Kaitkan ke Batch PO
                            </label>
                            <select
                                wire:model="open_po_id"
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                            >
                                <option value="">-- Umum (Tanpa Batch) --</option>
                                @foreach($openPos as $po)
                                    <option value="{{ $po->id }}">{{ $po->nama_po }}</option>
                                @endforeach
                            </select>
                            @error('open_po_id') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Tanggal Pembelian *
                            </label>
                            <input
                                wire:model="tanggal_pembelian"
                                type="date"
                                required
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                            >
                            @error('tanggal_pembelian') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Nama Toko / Supplier / Pasar *
                        </label>
                        <input
                            wire:model="nama_toko_supplier"
                            type="text"
                            required
                            placeholder="Contoh: Toko Daging Barokah Ciroyom"
                            class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                        >
                        @error('nama_toko_supplier') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Nama Barang / Bahan *
                        </label>
                        <input
                            wire:model="nama_barang"
                            type="text"
                            required
                            placeholder="Contoh: Daging Sapi Has Dalam Segar"
                            class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                        >
                        @error('nama_barang') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Qty, Satuan, Harga Satuan -->
                    <div class="grid grid-cols-3 gap-2.5">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Jumlah (Qty) *
                            </label>
                            <input
                                wire:model.live.debounce.300ms="jumlah"
                                type="number"
                                step="0.01"
                                min="0.01"
                                required
                                placeholder="1"
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-mono text-slate-900 dark:text-white text-right tabular-nums"
                            >
                            @error('jumlah') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Satuan *
                            </label>
                            <select
                                wire:model="satuan"
                                required
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                            >
                                @foreach($satuanOptions as $sat)
                                    <option value="{{ $sat }}">{{ $sat }}</option>
                                @endforeach
                            </select>
                            @error('satuan') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Harga Satuan *
                            </label>
                            <input
                                wire:model.live.debounce.300ms="harga_satuan"
                                type="number"
                                step="100"
                                min="0"
                                required
                                placeholder="135000"
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-mono text-slate-900 dark:text-white text-right font-medium tabular-nums"
                            >
                            @error('harga_satuan') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Realtime Total Calculation Banner -->
                    <div class="p-3 rounded-md bg-blue-50/60 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800/60 flex items-center justify-between">
                        <span class="text-xs font-semibold text-blue-900 dark:text-blue-300 uppercase">Total Pembelian:</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white tabular-nums">
                            <x-money :value="$this->totalPembelian" />
                        </span>
                    </div>

                    <!-- Metode Bayar & Upload Nota -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Metode Pembayaran *
                            </label>
                            <select
                                wire:model="metode_pembayaran"
                                required
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                            >
                                @foreach($paymentMethods as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                            @error('metode_pembayaran') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Foto / Bukti Nota Belanja
                            </label>
                            <input
                                wire:model="foto_nota"
                                type="file"
                                accept="image/*"
                                class="w-full text-xs text-slate-500 dark:text-gray-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-100 dark:file:bg-gray-800 file:text-slate-700 dark:file:text-gray-300 hover:file:bg-slate-200 dark:hover:file:bg-gray-700"
                            >
                            @error('foto_nota') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror

                            @if($foto_nota)
                                <div class="mt-1 text-[11px] text-slate-500 dark:text-gray-400 flex items-center gap-2">
                                    <span>Preview:</span>
                                    <img src="{{ $foto_nota->temporaryUrl() }}" class="w-10 h-10 rounded-md object-cover border border-slate-200 dark:border-gray-700">
                                </div>
                            @elseif($existingFotoNota)
                                <div class="mt-1 text-[11px] text-slate-500 dark:text-gray-400 flex items-center gap-2">
                                    <span>Nota tersimpan:</span>
                                    <img src="{{ asset('storage/' . $existingFotoNota) }}" class="w-10 h-10 rounded-md object-cover border border-slate-200 dark:border-gray-700">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Keterangan Tambahan (Opsional)
                        </label>
                        <textarea
                            wire:model="keterangan"
                            rows="2"
                            placeholder="Contoh: Beli potong pagi segar untuk rendang..."
                            class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                        ></textarea>
                        @error('keterangan') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="px-5 py-3 bg-slate-50 dark:bg-gray-800/60 border-t border-slate-100 dark:border-gray-800 flex items-center justify-end gap-2">
                    <button
                        type="button"
                        @click="show = false; $wire.closeModal()"
                        class="px-3 py-1.5 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-300 text-xs font-medium rounded-md shadow-xs cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-xs disabled:opacity-50 cursor-pointer"
                    >
                        <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Simpan Perubahan' : 'Catat Pembelian' }}</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Receipt Image Lightbox Modal -->
    <div
        x-data="{ show: @entangle('showReceiptModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
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
            class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs transition-opacity"
            @click="show = false; $wire.closeReceiptModal()"
        ></div>

        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-lg shadow-2xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all z-10"
        >
            <div class="px-5 py-3.5 bg-slate-900 dark:bg-gray-950 text-white flex items-center justify-between border-b border-slate-800">
                <h4 class="text-xs font-semibold">{{ $receiptTitle }}</h4>
                <button type="button" @click="show = false; $wire.closeReceiptModal()" class="p-1 text-slate-400 hover:text-white cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4 bg-slate-100 dark:bg-gray-950 flex items-center justify-center max-h-[75vh] overflow-auto">
                @if($receiptUrl)
                    <img src="{{ $receiptUrl }}" alt="Foto Nota" class="max-h-[65vh] object-contain rounded-md shadow-sm">
                @endif
            </div>
            <div class="px-5 py-3 bg-white dark:bg-gray-900 border-t border-slate-200 dark:border-gray-800 text-right">
                @if($receiptUrl)
                    <a href="{{ $receiptUrl }}" target="_blank" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                        Buka Gambar di Tab Baru ↗
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
