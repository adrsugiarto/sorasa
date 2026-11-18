<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Pengeluaran Operasional Lain</h1>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Catat biaya kemasan, gas, listrik, tenaga kerja, transportasi, dan operasional dapur lainnya</p>
        </div>
        <button
            type="button"
            wire:click="openCreateModal"
            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium text-xs shadow-xs transition-colors shrink-0 cursor-pointer"
        >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Catat Pengeluaran
        </button>
    </div>

    <!-- Summary KPI Card -->
    <div class="bg-white dark:bg-gray-900 p-4 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-gray-400">Total Biaya Operasional (Tersaring)</p>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">@rupiah($totalFilteredExpenses)</h3>
            <p class="text-[11px] text-slate-400 dark:text-gray-500 mt-0.5">Biaya non-bahan langsung yang mengurangi margin usaha</p>
        </div>
        <div class="w-10 h-10 rounded-md bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-gray-300 flex items-center justify-center shrink-0 border border-slate-200 dark:border-gray-700">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-gray-900 p-3 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2.5 flex-1">
            <!-- Search -->
            <div class="relative">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Cari pengeluaran atau keterangan..."
                    class="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800 dark:text-white placeholder-slate-400 font-medium"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Kategori Filter -->
            <select
                wire:model.live="selectedCategory"
                class="px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300 font-medium"
            >
                <option value="">Semua Kategori Biaya</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>

            <!-- Open PO Filter -->
            <select
                wire:model.live="selectedOpenPoId"
                class="px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300 font-medium"
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
                    class="w-1/2 px-2 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300"
                    placeholder="Mulai"
                >
                <span class="text-xs text-slate-400">-</span>
                <input
                    wire:model.live="endDate"
                    type="date"
                    class="w-1/2 px-2 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300"
                    placeholder="Sampai"
                >
            </div>
        </div>

        @if($search || $selectedCategory || $selectedOpenPoId || $startDate || $endDate)
            <button
                type="button"
                wire:click="$set('search', ''); $set('selectedCategory', ''); $set('selectedOpenPoId', ''); $set('startDate', null); $set('endDate', null);"
                class="text-xs text-rose-600 hover:text-rose-700 font-medium hover:underline shrink-0 px-2 cursor-pointer"
            >
                Reset Filter
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-md text-xs flex items-center justify-between">
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
                <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-500 dark:text-gray-400 border-b border-slate-200 dark:border-gray-800 uppercase font-semibold text-[11px] tracking-wider">
                    <tr>
                        <th class="px-3.5 py-2.5">Tanggal</th>
                        <th class="px-3.5 py-2.5">Kaitan Open PO</th>
                        <th class="px-3.5 py-2.5">Kategori Biaya</th>
                        <th class="px-3.5 py-2.5">Nama Pengeluaran & Ket.</th>
                        <th class="px-3.5 py-2.5">Metode Bayar</th>
                        <th class="px-3.5 py-2.5 text-right">Nominal (Rp)</th>
                        <th class="px-3.5 py-2.5 text-center">Bukti Nota</th>
                        <th class="px-3.5 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-[13px] leading-5">
                    @forelse($expenses as $item)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40 transition-colors">
                            <!-- Tanggal -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap">
                                <div class="font-medium text-slate-900 dark:text-white tabular-nums">{{ $item->tanggal_pengeluaran->format('d M Y') }}</div>
                                <span class="text-[10px] text-slate-400 dark:text-gray-500 block">
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                            </td>

                            <!-- Open PO -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap">
                                @if($item->openPo)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-medium bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-800 tabular-nums">
                                        {{ $item->openPo->kode_po }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400 dark:text-gray-500 italic">Rutin / Umum</span>
                                @endif
                            </td>

                            <!-- Kategori Biaya -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap">
                                @php
                                    $catBadge = match($item->kategori_biaya) {
                                        'Kemasan/Packaging' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800',
                                        'Gas' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800',
                                        'Listrik/Air' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-800',
                                        'Tenaga Kerja' => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800',
                                        'Transportasi' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium border {{ $catBadge }}">
                                    {{ $item->kategori_biaya }}
                                </span>
                            </td>

                            <!-- Nama & Keterangan -->
                            <td class="px-3.5 py-2.5">
                                <div class="font-medium text-slate-900 dark:text-white">{{ $item->nama_pengeluaran }}</div>
                                @if($item->keterangan)
                                    <p class="text-[11px] text-slate-400 dark:text-gray-500 mt-0.5">{{ $item->keterangan }}</p>
                                @endif
                            </td>

                            <!-- Metode Bayar -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap font-medium text-slate-600 dark:text-gray-400 uppercase text-xs">
                                {{ $item->metode_pembayaran }}
                            </td>

                            <!-- Nominal -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap text-right font-semibold text-slate-900 dark:text-white tabular-nums">
                                @rupiah($item->nominal)
                            </td>

                            <!-- Bukti Nota -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap text-center">
                                @if($item->bukti_nota)
                                    <button
                                        type="button"
                                        wire:click="viewReceipt('{{ $item->bukti_nota }}', '{{ $item->nama_pengeluaran }}')"
                                        class="inline-flex items-center gap-1 text-[11px] font-medium text-blue-600 dark:text-blue-400 hover:underline cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Lihat Nota</span>
                                    </button>
                                @else
                                    <span class="text-[11px] text-slate-400 dark:text-gray-600 italic">Tanpa nota</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $item->id }})"
                                        class="p-1 rounded-md text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                                        title="Edit Pengeluaran"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="delete({{ $item->id }})"
                                        wire:confirm="Yakin ingin menghapus catatan pengeluaran ini?"
                                        class="p-1 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer"
                                        title="Hapus Pengeluaran"
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
                            <td colspan="8" class="px-3.5 py-8 text-center text-slate-400 dark:text-gray-500">
                                Belum ada catatan pengeluaran operasional.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Pagination -->
    <div>
        {{ $expenses->links() }}
    </div>

    <!-- Modal Form Tambah / Edit Pengeluaran -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/40 dark:bg-black/70 backdrop-blur-xs transition-opacity" wire:click="closeModal"></div>

            <div class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-lg shadow-xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all z-10 text-slate-800 dark:text-slate-200">
                <div class="px-5 py-3.5 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $isEditing ? 'Edit Pengeluaran Operasional' : 'Catat Pengeluaran Operasional' }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-gray-400">Kemasan, gas, utilitas, atau tenaga kerja non-bahan</p>
                    </div>
                    <button type="button" wire:click="closeModal" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-800">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="p-5 space-y-3.5 max-h-[75vh] overflow-y-auto text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    Kaitan Open PO (Opsional)
                                </label>
                                <select
                                    wire:model="open_po_id"
                                    class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                                >
                                    <option value="">-- Rutin / Umum (Tanpa Batch) --</option>
                                    @foreach($openPos as $po)
                                        <option value="{{ $po->id }}">[{{ $po->kode_po }}] {{ $po->nama_po }}</option>
                                    @endforeach
                                </select>
                                @error('open_po_id') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    Tanggal Pengeluaran *
                                </label>
                                <input
                                    wire:model="tanggal_pengeluaran"
                                    type="date"
                                    required
                                    class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                                >
                                @error('tanggal_pengeluaran') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    Kategori Biaya *
                                </label>
                                <select
                                    wire:model="kategori_biaya"
                                    required
                                    class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                                >
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_biaya') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    Metode Pembayaran *
                                </label>
                                <select
                                    wire:model="metode_pembayaran"
                                    required
                                    class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                                >
                                    @foreach($paymentMethods as $pm)
                                        <option value="{{ $pm }}">{{ $pm }}</option>
                                    @endforeach
                                </select>
                                @error('metode_pembayaran') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Nama Pengeluaran *
                            </label>
                            <input
                                wire:model="nama_pengeluaran"
                                type="text"
                                required
                                placeholder="Contoh: Dus Box Hampers Tebal 50 Pcs"
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                            >
                            @error('nama_pengeluaran') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Nominal Pengeluaran (Rp) *
                            </label>
                            <input
                                wire:model="nominal"
                                type="number"
                                step="any"
                                min="1"
                                required
                                placeholder="150000"
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-mono font-bold text-slate-900 dark:text-white tabular-nums"
                            >
                            @error('nominal') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Foto / Bukti Nota (Opsional)
                            </label>
                            <input
                                wire:model="bukti_nota"
                                type="file"
                                accept="image/*"
                                class="w-full text-xs text-slate-500 dark:text-gray-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-100 dark:file:bg-gray-800 file:text-slate-700 dark:file:text-gray-300 hover:file:bg-slate-200 dark:hover:file:bg-gray-700"
                            >
                            @error('bukti_nota') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror

                            @if($bukti_nota)
                                <div class="mt-2 text-[11px] text-slate-500 dark:text-gray-400 flex items-center gap-2">
                                    <span>Preview:</span>
                                    <img src="{{ $bukti_nota->temporaryUrl() }}" class="w-12 h-12 rounded-md object-cover border border-slate-200 dark:border-gray-700">
                                </div>
                            @elseif($existingBuktiNota)
                                <div class="mt-2 text-[11px] text-slate-500 dark:text-gray-400 flex items-center gap-2">
                                    <span>Nota tersimpan:</span>
                                    <img src="{{ asset('storage/' . $existingBuktiNota) }}" class="w-12 h-12 rounded-md object-cover border border-slate-200 dark:border-gray-700">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Keterangan Tambahan (Opsional)
                            </label>
                            <textarea
                                wire:model="keterangan"
                                rows="2"
                                placeholder="Contoh: Beli di Toko Plastik Jaya, termasuk pita..."
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                            ></textarea>
                            @error('keterangan') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-slate-50 dark:bg-gray-800/60 border-t border-slate-100 dark:border-gray-800 flex items-center justify-end gap-2">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-3 py-1.5 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-750 text-slate-700 dark:text-gray-300 text-xs font-medium rounded-md shadow-xs cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-xs disabled:opacity-50 cursor-pointer"
                        >
                            <span wire:loading.remove>{{ $isEditing ? 'Simpan Perubahan' : 'Catat Pengeluaran' }}</span>
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
            <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-xs transition-opacity" wire:click="closeReceiptModal"></div>

            <div class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-lg shadow-2xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all z-10 text-slate-800 dark:text-slate-200">
                <div class="px-5 py-3.5 bg-slate-900 dark:bg-gray-950 text-white flex items-center justify-between border-b border-slate-800">
                    <h4 class="text-xs font-semibold">{{ $receiptTitle }}</h4>
                    <button type="button" wire:click="closeReceiptModal" class="p-1 text-slate-400 hover:text-white cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 bg-slate-100 dark:bg-gray-950 flex items-center justify-center max-h-[75vh] overflow-auto">
                    <img src="{{ $receiptUrl }}" alt="Foto Nota" class="max-h-[65vh] object-contain rounded-md shadow-sm">
                </div>
                <div class="px-5 py-3 bg-white dark:bg-gray-900 border-t border-slate-200 dark:border-gray-800 text-right">
                    <a href="{{ $receiptUrl }}" target="_blank" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                        Buka Gambar di Tab Baru ↗
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
