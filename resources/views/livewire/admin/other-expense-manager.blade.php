<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Pengeluaran Operasional Lain</h1>
            <p class="text-xs text-slate-500 mt-1">Catat biaya kemasan, gas, listrik, tenaga kerja, transportasi, dan operasional dapur lainnya</p>
        </div>
        <button
            type="button"
            wire:click="openCreateModal"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 text-white rounded-xl font-bold text-xs hover:bg-rose-700 shadow-2xs hover:shadow-xs transition-all active:scale-95 shrink-0"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Catat Pengeluaran
        </button>
    </div>

    <!-- Summary KPI Card -->
    <div class="bg-white p-5 rounded-2xl border border-rose-200/80 shadow-2xs flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">Total Biaya Operasional (Tersaring)</p>
            <h3 class="text-2xl font-black text-rose-700 mt-1 tabular-nums">@rupiah($totalFilteredExpenses)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Biaya non-bahan langsung yang mengurangi margin usaha</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2.5 flex-1">
            <!-- Search -->
            <div class="relative">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Cari pengeluaran atau keterangan..."
                    class="w-full pl-9 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-800 placeholder-slate-400"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Kategori Filter -->
            <select
                wire:model.live="selectedCategory"
                class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-700 font-medium"
            >
                <option value="">Semua Kategori Biaya</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>

            <!-- Open PO Filter -->
            <select
                wire:model.live="selectedOpenPoId"
                class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-700 font-medium"
            >
                <option value="">Semua Batch Open PO</option>
                @foreach($openPos as $po)
                    <option value="{{ $po->id }}">[{{ $po->kode_po }}] {{ $po->nama_po }}</option>
                @endforeach
            </select>

            <!-- Date Range -->
            <div class="flex items-center gap-1.5">
                <input
                    wire:model.live="startDate"
                    type="date"
                    class="w-1/2 px-2.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-700"
                    placeholder="Mulai"
                >
                <span class="text-xs text-slate-400">-</span>
                <input
                    wire:model.live="endDate"
                    type="date"
                    class="w-1/2 px-2.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-700"
                    placeholder="Sampai"
                >
            </div>
        </div>

        @if($search || $selectedCategory || $selectedOpenPoId || $startDate || $endDate)
            <button
                type="button"
                wire:click="$set('search', ''); $set('selectedCategory', ''); $set('selectedOpenPoId', ''); $set('startDate', null); $set('endDate', null);"
                class="text-xs text-rose-600 font-medium hover:underline shrink-0 px-2"
            >
                Reset Filter
            </button>
        @endif
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

    <!-- Expenses Table -->
    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Kaitan Open PO</th>
                        <th class="px-5 py-3.5">Kategori Biaya</th>
                        <th class="px-5 py-3.5">Nama Pengeluaran & Ket.</th>
                        <th class="px-5 py-3.5">Metode Bayar</th>
                        <th class="px-5 py-3.5 text-right">Nominal (Rp)</th>
                        <th class="px-5 py-3.5">Bukti Nota</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($expenses as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Tanggal -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $item->tanggal_pengeluaran->format('d M Y') }}</div>
                                <span class="text-[10px] text-slate-400 font-medium block">
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                            </td>

                            <!-- Open PO -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($item->openPo)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        {{ $item->openPo->nama_po }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Rutin / Umum</span>
                                @endif
                            </td>

                            <!-- Kategori Biaya -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @php
                                    $catBadge = match($item->kategori_biaya) {
                                        'Kemasan/Packaging' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'Gas' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'Listrik/Air' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Tenaga Kerja' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'Transportasi' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold border {{ $catBadge }}">
                                    {{ $item->kategori_biaya }}
                                </span>
                            </td>

                            <!-- Nama & Keterangan -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-800">{{ $item->nama_pengeluaran }}</div>
                                @if($item->keterangan)
                                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $item->keterangan }}</p>
                                @endif
                            </td>

                            <!-- Metode Bayar -->
                            <td class="px-5 py-4 whitespace-nowrap font-medium text-slate-600">
                                {{ $item->metode_pembayaran }}
                            </td>

                            <!-- Nominal -->
                            <td class="px-5 py-4 whitespace-nowrap text-right font-black text-rose-700 tabular-nums">
                                @rupiah($item->nominal)
                            </td>

                            <!-- Bukti Nota -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($item->bukti_nota)
                                    <button
                                        type="button"
                                        wire:click="viewReceipt('{{ $item->bukti_nota }}', '{{ $item->nama_pengeluaran }}')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded-lg text-[11px] font-semibold border border-amber-200 transition-colors"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat Nota
                                    </button>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Tanpa nota</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 whitespace-nowrap text-right space-x-1.5">
                                <button
                                    type="button"
                                    wire:click="openEditModal({{ $item->id }})"
                                    class="p-1.5 text-slate-400 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors"
                                    title="Edit Pengeluaran"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    wire:click="delete({{ $item->id }})"
                                    wire:confirm="Yakin ingin menghapus catatan pengeluaran ini?"
                                    class="p-1.5 text-slate-400 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors"
                                    title="Hapus"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                <p class="font-bold text-slate-600 text-sm">Belum ada catatan pengeluaran operasional</p>
                                <p class="text-xs text-slate-400 mt-1">Klik tombol "Catat Pengeluaran" di atas untuk menambahkan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
            <div class="px-5 py-3 border-t border-slate-200">
                {{ $expenses->links() }}
            </div>
        @endif
    </x-card>

    <!-- MODAL FORM PENGELUARAN -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in-95">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">
                            {{ $isEditing ? 'Edit Catatan Pengeluaran' : 'Catat Pengeluaran Operasional' }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Biaya non-bahan habis pakai (kemasan, gas, utilitas, dll)</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="mt-4 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Tanggal Pengeluaran -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal *</label>
                            <input
                                wire:model="tanggal_pengeluaran"
                                type="date"
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium"
                                required
                            >
                            @error('tanggal_pengeluaran') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kategori Biaya -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Biaya *</label>
                            <select
                                wire:model="kategori_biaya"
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium"
                                required
                            >
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('kategori_biaya') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Nama Pengeluaran -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama / Item Pengeluaran *</label>
                        <input
                            wire:model="nama_pengeluaran"
                            type="text"
                            placeholder="Misal: Dus Box Hampers 100 pcs, Gas Melon 3kg"
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium"
                            required
                        >
                        @error('nama_pengeluaran') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Nominal -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal Biaya (Rp) *</label>
                            <input
                                wire:model="nominal"
                                type="number"
                                step="any"
                                min="0"
                                class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-bold text-rose-700 tabular-nums"
                                required
                            >
                            @error('nominal') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Bayar *</label>
                            <select
                                wire:model="metode_pembayaran"
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium"
                                required
                            >
                                @foreach($paymentMethods as $paym)
                                    <option value="{{ $paym }}">{{ $paym }}</option>
                                @endforeach
                            </select>
                            @error('metode_pembayaran') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Kaitan Batch Open PO -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kaitkan ke Batch Open PO (Opsional)</label>
                        <select
                            wire:model="open_po_id"
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium"
                        >
                            <option value="">Pengeluaran Rutin / Umum (Tanpa Batch)</option>
                            @foreach($openPos as $po)
                                <option value="{{ $po->id }}">[{{ $po->kode_po }}] {{ $po->nama_po }}</option>
                            @endforeach
                        </select>
                        @error('open_po_id') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Foto / Bukti Nota -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Upload Bukti / Nota (Opsional)</label>
                        <input
                            wire:model="bukti_nota"
                            type="file"
                            accept="image/*"
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100"
                        >
                        <div wire:loading wire:target="bukti_nota" class="text-[11px] text-amber-600 mt-1 font-medium">
                            Mengunggah berkas nota...
                        </div>
                        @if ($bukti_nota)
                            <div class="mt-2">
                                <img src="{{ $bukti_nota->temporaryUrl() }}" class="h-20 w-auto rounded-lg border border-slate-200 object-cover shadow-2xs">
                            </div>
                        @elseif ($existingBuktiNota)
                            <div class="mt-2 flex items-center gap-2">
                                <img src="{{ asset('storage/'.$existingBuktiNota) }}" class="h-14 w-auto rounded-lg border border-slate-200 object-cover">
                                <span class="text-[11px] text-slate-400">Nota saat ini tersimpan</span>
                            </div>
                        @endif
                        @error('bukti_nota') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan Tambahan (Opsional)</label>
                        <textarea
                            wire:model="keterangan"
                            rows="2"
                            placeholder="Detail pembelian atau catatan lainnya..."
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600"
                        ></textarea>
                        @error('keterangan') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-2xs hover:shadow-xs transition-all disabled:opacity-50"
                        >
                            {{ $isEditing ? 'Simpan Perubahan' : 'Catat Pengeluaran' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- LIGHTBOX MODAL BUKTI NOTA -->
    @if($showReceiptModal && $receiptUrl)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-xl w-full p-4 shadow-2xl relative">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-xs font-bold text-slate-800">Bukti Nota Pengeluaran</h3>
                        <p class="text-[11px] text-slate-500">{{ $receiptTitle }}</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeReceiptModal"
                        class="text-slate-400 hover:text-slate-600 p-1 rounded-lg"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-3 flex justify-center">
                    <img src="{{ $receiptUrl }}" alt="Bukti Nota" class="max-h-[70vh] rounded-xl object-contain border border-slate-100 shadow-inner">
                </div>
            </div>
        </div>
    @endif
</div>
