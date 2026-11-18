<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Pembelian Bahan Baku (Habis Pakai)</h1>
            <p class="text-xs text-slate-500 mt-1">Pencatatan belanja bahan masakan yang langsung dihitung sebagai biaya operasional (tanpa stok)</p>
        </div>
        <div class="flex items-center gap-3">
            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Catat Pembelian Bahan</span>
            </button>
        </div>
    </div>

    <!-- Filter & Summary Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 flex-1">
            <div class="relative flex-1 max-w-xs">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Cari barang atau nama toko..."
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
                    <option value="{{ $po->id }}">{{ $po->nama_po }} ({{ $po->kode_po }})</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-3 self-end lg:self-auto">
            <div class="px-3.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-right">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold block">Total Belanja Terfilter:</span>
                <span class="text-sm font-extrabold text-slate-900">
                    <x-money :value="$totalFiltered" />
                </span>
            </div>
        </div>
    </div>

    <!-- Purchases Table -->
    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Toko / Supplier</th>
                        <th class="px-5 py-3.5">Nama Barang</th>
                        <th class="px-5 py-3.5">Kuantitas & Satuan</th>
                        <th class="px-5 py-3.5 text-right">Harga Satuan</th>
                        <th class="px-5 py-3.5 text-right">Total Biaya</th>
                        <th class="px-5 py-3.5">Batch PO</th>
                        <th class="px-5 py-3.5 text-center">Nota</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($purchases as $p)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 text-slate-600 font-medium whitespace-nowrap">
                                {{ $p->tanggal_pembelian->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-900">
                                <div>{{ $p->nama_toko_supplier }}</div>
                                <span class="text-[10px] text-slate-400 font-normal uppercase">{{ $p->metode_pembayaran }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900">{{ $p->nama_barang }}</div>
                                @if($p->keterangan)
                                    <span class="text-[11px] text-slate-400 block line-clamp-1">{{ $p->keterangan }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-mono font-medium text-slate-800 whitespace-nowrap">
                                {{ number_format($p->jumlah, 2) }} {{ $p->satuan }}
                            </td>
                            <td class="px-5 py-4 text-right text-slate-600 whitespace-nowrap">
                                <x-money :value="$p->harga_satuan" />
                            </td>
                            <td class="px-5 py-4 text-right font-extrabold text-slate-900 whitespace-nowrap">
                                <x-money :value="$p->total_pembelian" />
                            </td>
                            <td class="px-5 py-4 text-slate-500 whitespace-nowrap">
                                @if($p->openPo)
                                    <span class="text-[11px] font-semibold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200/50">
                                        {{ $p->openPo->kode_po }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400">Umum</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($p->foto_nota)
                                    <button
                                        type="button"
                                        wire:click="viewReceipt('{{ $p->foto_nota }}', '{{ $p->nama_barang }} - {{ $p->nama_toko_supplier }}')"
                                        class="p-1 rounded text-amber-700 hover:text-amber-900 hover:bg-amber-50"
                                        title="Lihat foto nota"
                                    >
                                        <svg class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="text-[10px] text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $p->id }})"
                                        class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors cursor-pointer"
                                        title="Edit Pembelian"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="delete({{ $p->id }})"
                                        wire:confirm="Yakin ingin menghapus catatan pembelian '{{ $p->nama_barang }}'?"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                        title="Hapus Pembelian"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center text-slate-400">
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
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" wire:click="closeModal"></div>

            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all z-10">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">
                            {{ $isEditing ? 'Edit Pembelian Bahan' : 'Catat Pembelian Bahan Baku' }}
                        </h3>
                        <p class="text-xs text-slate-500">Biaya habis pakai untuk produksi pesanan Open PO</p>
                    </div>
                    <button type="button" wire:click="closeModal" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Kaitkan ke Batch PO
                                </label>
                                <select
                                    wire:model="open_po_id"
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900 font-semibold"
                                >
                                    <option value="">-- Umum (Tanpa Batch) --</option>
                                    @foreach($openPos as $po)
                                        <option value="{{ $po->id }}">{{ $po->nama_po }}</option>
                                    @endforeach
                                </select>
                                @error('open_po_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Tanggal Pembelian *
                                </label>
                                <input
                                    wire:model="tanggal_pembelian"
                                    type="date"
                                    required
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900 font-medium"
                                >
                                @error('tanggal_pembelian') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Nama Toko / Supplier / Pasar *
                            </label>
                            <input
                                wire:model="nama_toko_supplier"
                                type="text"
                                required
                                placeholder="Contoh: Toko Daging Barokah Ciroyom"
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900"
                            >
                            @error('nama_toko_supplier') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Nama Barang / Bahan *
                            </label>
                            <input
                                wire:model="nama_barang"
                                type="text"
                                required
                                placeholder="Contoh: Daging Sapi Has Dalam Segar"
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900 font-semibold"
                            >
                            @error('nama_barang') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Qty, Satuan, Harga Satuan -->
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Jumlah (Qty) *
                                </label>
                                <input
                                    wire:model.live.debounce.300ms="jumlah"
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    required
                                    placeholder="1"
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-mono text-slate-900 text-right"
                                >
                                @error('jumlah') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Satuan *
                                </label>
                                <select
                                    wire:model="satuan"
                                    required
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900 font-medium"
                                >
                                    @foreach($satuanOptions as $sat)
                                        <option value="{{ $sat }}">{{ $sat }}</option>
                                    @endforeach
                                </select>
                                @error('satuan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Harga Satuan *
                                </label>
                                <input
                                    wire:model.live.debounce.300ms="harga_satuan"
                                    type="number"
                                    step="100"
                                    min="0"
                                    required
                                    placeholder="135000"
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-mono text-slate-900 text-right font-medium"
                                >
                                @error('harga_satuan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Realtime Total Calculation Banner -->
                        <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/80 flex items-center justify-between">
                            <span class="text-xs font-semibold text-amber-900 uppercase">Total Pembelian:</span>
                            <span class="text-base font-extrabold text-slate-900">
                                <x-money :value="$this->totalPembelian" />
                            </span>
                        </div>

                        <!-- Metode Bayar & Upload Nota -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Metode Pembayaran *
                                </label>
                                <select
                                    wire:model="metode_pembayaran"
                                    required
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900 font-semibold"
                                >
                                    @foreach($paymentMethods as $m)
                                        <option value="{{ $m }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                                @error('metode_pembayaran') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Foto / Bukti Nota Belanja
                                </label>
                                <input
                                    wire:model="foto_nota"
                                    type="file"
                                    accept="image/*"
                                    class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200"
                                >
                                @error('foto_nota') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror

                                @if($foto_nota)
                                    <div class="mt-1 text-[11px] text-slate-500 flex items-center gap-2">
                                        <span>Preview:</span>
                                        <img src="{{ $foto_nota->temporaryUrl() }}" class="w-10 h-10 rounded object-cover border">
                                    </div>
                                @elseif($existingFotoNota)
                                    <div class="mt-1 text-[11px] text-slate-500 flex items-center gap-2">
                                        <span>Nota tersimpan:</span>
                                        <img src="{{ asset('storage/' . $existingFotoNota) }}" class="w-10 h-10 rounded object-cover border">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Keterangan Tambahan (Opsional)
                            </label>
                            <textarea
                                wire:model="keterangan"
                                rows="2"
                                placeholder="Contoh: Beli potong pagi segar untuk rendang..."
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900"
                            ></textarea>
                            @error('keterangan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
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
                            <span wire:loading.remove>{{ $isEditing ? 'Simpan Perubahan' : 'Catat Pembelian' }}</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Receipt Image Lightbox Modal -->
    @if($showReceiptModal && $receiptUrl)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" wire:click="closeReceiptModal"></div>

            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all z-10">
                <div class="px-5 py-3.5 bg-slate-900 text-white flex items-center justify-between">
                    <h4 class="text-xs font-bold">{{ $receiptTitle }}</h4>
                    <button type="button" wire:click="closeReceiptModal" class="p-1 text-slate-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 bg-slate-100 flex items-center justify-center max-h-[75vh] overflow-auto">
                    <img src="{{ $receiptUrl }}" alt="Foto Nota" class="max-h-[65vh] object-contain rounded-lg shadow-sm">
                </div>
                <div class="px-5 py-3 bg-white border-t border-slate-200 text-right">
                    <a href="{{ $receiptUrl }}" target="_blank" class="text-xs font-semibold text-amber-700 hover:underline">
                        Buka Gambar di Tab Baru ↗
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
