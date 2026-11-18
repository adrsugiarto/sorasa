<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Manajemen Batch Open PO</h1>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Atur jadwal periode pemesanan, tanggal pengiriman, dan pantau rekap produksi dapur</p>
        </div>
        <div>
            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-xs transition-all active:scale-95 cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Buat Batch Open PO Baru</span>
            </button>
        </div>
    </div>

    <!-- Batches List -->
    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-500 dark:text-gray-400 border-b border-slate-200 dark:border-gray-800 uppercase font-semibold text-[11px] tracking-wider">
                    <tr>
                        <th class="px-3.5 py-2.5">Kode & Nama Batch</th>
                        <th class="px-3.5 py-2.5">Periode Pemesanan</th>
                        <th class="px-3.5 py-2.5">Jadwal Kirim</th>
                        <th class="px-3.5 py-2.5 text-center">Order Masuk</th>
                        <th class="px-3.5 py-2.5 text-right">Omzet Batch</th>
                        <th class="px-3.5 py-2.5">Status Batch</th>
                        <th class="px-3.5 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-[13px] leading-5">
                    @forelse($batches as $batch)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="px-3.5 py-2.5 font-medium text-slate-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $batch->nama_po }}</span>
                                    @if($batch->status === 'open')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/40 dark:text-blue-300">
                                            AKTIF
                                        </span>
                                    @endif
                                </div>
                                <span class="text-xs font-mono text-slate-400 dark:text-gray-500 font-medium tabular-nums">{{ $batch->kode_po }}</span>
                            </td>
                            <td class="px-3.5 py-2.5 text-slate-600 dark:text-gray-400">
                                <div class="font-medium text-slate-800 dark:text-gray-200 tabular-nums">{{ $batch->tanggal_buka->format('d M') }} — {{ $batch->tanggal_tutup->format('d M Y') }}</div>
                                <span class="text-[10px] text-slate-400 dark:text-gray-500">Tutup H-{{ max(0, $batch->tanggal_buka->diffInDays($batch->tanggal_tutup)) }} sebelum kirim</span>
                            </td>
                            <td class="px-3.5 py-2.5">
                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-xs font-medium tabular-nums text-slate-700 dark:text-gray-300">
                                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $batch->tanggal_pengiriman->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="px-3.5 py-2.5 text-center">
                                <span class="font-semibold text-slate-900 dark:text-white tabular-nums">{{ $batch->sales_count }}</span>
                                <span class="text-[11px] text-slate-400 dark:text-gray-500 block">pesanan</span>
                            </td>
                            <td class="px-3.5 py-2.5 text-right font-semibold text-slate-900 dark:text-white tabular-nums">
                                <x-money :value="$batch->total_omzet ?? 0" />
                            </td>
                            <td class="px-3.5 py-2.5">
                                <select
                                    wire:change="updateStatus({{ $batch->id }}, $event.target.value)"
                                    @class([
                                        'text-xs font-medium rounded-md px-2 py-1 border transition-colors cursor-pointer bg-white dark:bg-gray-800',
                                        'text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800 bg-blue-50/50' => $batch->status === 'open',
                                        'text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800 bg-emerald-50/50' => $batch->status === 'completed',
                                        'text-slate-600 dark:text-gray-400 border-slate-200 dark:border-gray-700' => in_array($batch->status, ['draft', 'closed']),
                                    ])
                                >
                                    <option value="draft" @selected($batch->status === 'draft')>DRAFT</option>
                                    <option value="open" @selected($batch->status === 'open')>OPEN (BUKA)</option>
                                    <option value="closed" @selected($batch->status === 'closed')>CLOSED (TUTUP)</option>
                                    <option value="completed" @selected($batch->status === 'completed')>COMPLETED (SELESAI)</option>
                                </select>
                            </td>
                            <td class="px-3.5 py-2.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button
                                        type="button"
                                        wire:click="showRecap({{ $batch->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-slate-50 dark:bg-gray-800 text-slate-700 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-700 font-medium text-xs transition-colors cursor-pointer"
                                        title="Lihat rekap produksi masakan dapur"
                                    >
                                        <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span>Rekap Dapur</span>
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $batch->id }})"
                                        class="p-1 rounded-md text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                                        title="Edit Batch"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    @if($batch->sales_count === 0)
                                        <button
                                            type="button"
                                            wire:click="delete({{ $batch->id }})"
                                            wire:confirm="Yakin ingin menghapus batch Open PO ini?"
                                            class="p-1 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer"
                                            title="Hapus Batch"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3.5 py-8 text-center text-slate-400">
                                Belum ada batch Open PO. Klik tombol di atas untuk membuat batch baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Modal Form Tambah / Edit Batch -->
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
            class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-lg shadow-xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all z-10"
        >
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                    {{ $isEditing ? 'Edit Batch Open PO' : 'Buat Batch Open PO Baru' }}
                </h3>
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
                                Kode Batch *
                            </label>
                            <input
                                wire:model="kode_po"
                                type="text"
                                required
                                placeholder="PO-202611-01"
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-mono font-bold uppercase text-slate-900 dark:text-white tabular-nums"
                            >
                            @error('kode_po') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Status Batch *
                            </label>
                            <select
                                wire:model="status"
                                required
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                            >
                                <option value="draft">Draft</option>
                                <option value="open">Open (Buka Pemesanan)</option>
                                <option value="closed">Closed (Tutup)</option>
                                <option value="completed">Completed (Selesai)</option>
                            </select>
                            @error('status') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Nama Batch PO *
                        </label>
                        <input
                            wire:model="nama_po"
                            type="text"
                            required
                            placeholder="Contoh: PO Batch #15 - Weekend Nusantara"
                            class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                        >
                        @error('nama_po') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Tanggal Buka *
                            </label>
                            <input
                                wire:model="tanggal_buka"
                                type="date"
                                required
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                            >
                            @error('tanggal_buka') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Tanggal Tutup *
                            </label>
                            <input
                                wire:model="tanggal_tutup"
                                type="date"
                                required
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                            >
                            @error('tanggal_tutup') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Jadwal Pengiriman Pesanan *
                        </label>
                        <input
                            wire:model="tanggal_pengiriman"
                            type="date"
                            required
                            class="w-full px-2.5 py-1.5 text-xs bg-blue-50/50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-medium text-slate-900 dark:text-white"
                        >
                        @error('tanggal_pengiriman') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Catatan Batch (Opsional)
                        </label>
                        <textarea
                            wire:model="catatan"
                            rows="2"
                            placeholder="Contoh: Menu spesial rendang sapi & liwet komplit..."
                            class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                        ></textarea>
                        @error('catatan') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
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
                        <span wire:loading.remove>{{ $isEditing ? 'Simpan Perubahan' : 'Buat Batch PO' }}</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Rekap Produksi Dapur (Detail Batch) -->
    <div
        x-data="{ show: @entangle('showDetailModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
        style="display: none;"
        @keydown.escape.window="show = false; $wire.closeDetailModal()"
    >
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"
            @click="show = false; $wire.closeDetailModal()"
        ></div>

        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            class="relative w-full max-w-2xl bg-white dark:bg-gray-900 rounded-lg shadow-xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all z-10"
        >
            @if($selectedBatch)
                <!-- Modal Header -->
                <div class="px-5 py-3.5 bg-slate-50/80 dark:bg-gray-800/80 border-b border-slate-200 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono font-semibold text-blue-700 bg-blue-50 dark:bg-blue-900/40 dark:text-blue-300 px-2 py-0.5 rounded-md border border-blue-200 dark:border-blue-800 tabular-nums">
                            {{ $selectedBatch->kode_po }}
                        </span>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mt-1">
                            Rekap Produksi Dapur — {{ $selectedBatch->nama_po }}
                        </h3>
                    </div>
                    <button type="button" @click="show = false; $wire.closeDetailModal()" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-200/60 dark:hover:bg-gray-700 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto">
                    <!-- Metrics Bar -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="p-3 bg-slate-50 dark:bg-gray-800/60 rounded-md border border-slate-200 dark:border-gray-700 text-center">
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Jadwal Kirim</span>
                            <div class="text-xs font-semibold text-slate-900 dark:text-white mt-0.5 tabular-nums">
                                {{ $selectedBatch->tanggal_pengiriman->format('d M Y') }}
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-gray-800/60 rounded-md border border-slate-200 dark:border-gray-700 text-center">
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Pesanan</span>
                            <div class="text-xs font-semibold text-slate-900 dark:text-white mt-0.5 tabular-nums">
                                {{ $batchTotalOrders }} Order
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-gray-800/60 rounded-md border border-slate-200 dark:border-gray-700 text-center">
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Omzet Batch</span>
                            <div class="text-xs font-semibold text-slate-900 dark:text-white mt-0.5 tabular-nums">
                                <x-money :value="$batchOmzet" />
                            </div>
                        </div>
                    </div>

                    <!-- Cooking Summary Table (Sangat Berguna untuk Tim Dapur!) -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span>Kuantitas Masak / Produksi per Menu:</span>
                            </h4>
                            <span class="text-[11px] text-slate-400">Total item pesanan terkonfirmasi</span>
                        </div>

                        <div class="border border-slate-200 dark:border-gray-800 rounded-md overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-500 dark:text-gray-400 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-200 dark:border-gray-800">
                                    <tr>
                                        <th class="px-3.5 py-2">Menu / Produk Makanan</th>
                                        <th class="px-3.5 py-2">Kategori</th>
                                        <th class="px-3.5 py-2 text-center">Qty Harus Dimasak</th>
                                        <th class="px-3.5 py-2 text-right">Kontribusi Omzet</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-[13px] leading-5">
                                    @forelse($kitchenRecap as $item)
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40">
                                            <td class="px-3.5 py-2 font-medium text-slate-900 dark:text-white">
                                                {{ $item['nama_produk'] }}
                                            </td>
                                            <td class="px-3.5 py-2 text-slate-500 dark:text-gray-400 text-xs">
                                                {{ $item['kategori'] }}
                                            </td>
                                            <td class="px-3.5 py-2 text-center">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/40 dark:text-blue-300 font-mono tabular-nums">
                                                    {{ $item['total_qty'] }} paket
                                                </span>
                                            </td>
                                            <td class="px-3.5 py-2 text-right font-semibold text-slate-900 dark:text-white tabular-nums">
                                                <x-money :value="$item['total_nominal']" />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-3.5 py-6 text-center text-slate-400">
                                                Belum ada pesanan masuk pada batch ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-5 py-3 bg-slate-50 dark:bg-gray-800/60 border-t border-slate-100 dark:border-gray-800 flex items-center justify-between text-xs">
                    <span class="text-slate-500 dark:text-gray-400">Gunakan data ini untuk belanja bahan & persiapan porsi dapur.</span>
                    <button
                        type="button"
                        @click="show = false; $wire.closeDetailModal()"
                        class="px-3 py-1.5 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-300 font-medium rounded-md shadow-xs cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
