<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Manajemen Batch Open PO</h1>
            <p class="text-xs text-slate-500 mt-1">Atur jadwal periode pemesanan, tanggal pengiriman, dan pantau rekap produksi dapur</p>
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
                <span>Buat Batch Open PO Baru</span>
            </button>
        </div>
    </div>

    <!-- Batches List -->
    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Kode & Nama Batch</th>
                        <th class="px-5 py-3.5">Periode Pemesanan</th>
                        <th class="px-5 py-3.5">Jadwal Kirim</th>
                        <th class="px-5 py-3.5 text-center">Order Masuk</th>
                        <th class="px-5 py-3.5 text-right">Omzet Batch</th>
                        <th class="px-5 py-3.5">Status Batch</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($batches as $batch)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-semibold text-slate-900">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-slate-900">{{ $batch->nama_po }}</span>
                                    @if($batch->status === 'open')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                            AKTIF
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[11px] font-mono text-slate-400 font-medium">{{ $batch->kode_po }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                <div class="font-medium">{{ $batch->tanggal_buka->format('d M') }} — {{ $batch->tanggal_tutup->format('d M Y') }}</div>
                                <span class="text-[10px] text-slate-400">Tutup H-{{ max(0, $batch->tanggal_buka->diffInDays($batch->tanggal_tutup)) }} sebelum kirim</span>
                            </td>
                            <td class="px-5 py-4 font-bold text-amber-950">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50/80 border border-amber-200/60">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $batch->tanggal_pengiriman->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="font-bold text-sm text-slate-900">{{ $batch->sales_count }}</span>
                                <span class="text-[11px] text-slate-400 block">pesanan</span>
                            </td>
                            <td class="px-5 py-4 text-right font-extrabold text-slate-900">
                                <x-money :value="$batch->total_omzet ?? 0" />
                            </td>
                            <td class="px-5 py-4">
                                <select
                                    wire:change="updateStatus({{ $batch->id }}, $event.target.value)"
                                    @class([
                                        'text-xs font-semibold rounded-lg px-2.5 py-1 border transition-colors cursor-pointer',
                                        'bg-amber-50 text-amber-800 border-amber-200' => $batch->status === 'open',
                                        'bg-emerald-50 text-emerald-800 border-emerald-200' => $batch->status === 'completed',
                                        'bg-slate-100 text-slate-700 border-slate-300' => in_array($batch->status, ['draft', 'closed']),
                                    ])
                                >
                                    <option value="draft" @selected($batch->status === 'draft')>DRAFT</option>
                                    <option value="open" @selected($batch->status === 'open')>OPEN (BUKA)</option>
                                    <option value="closed" @selected($batch->status === 'closed')>CLOSED (TUTUP)</option>
                                    <option value="completed" @selected($batch->status === 'completed')>COMPLETED (SELESAI)</option>
                                </select>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button
                                        type="button"
                                        wire:click="showRecap({{ $batch->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-amber-50 text-amber-900 hover:bg-amber-100 border border-amber-200/70 font-semibold text-xs transition-colors cursor-pointer"
                                        title="Lihat rekap produksi masakan dapur"
                                    >
                                        <svg class="w-3.5 h-3.5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span>Rekap Dapur</span>
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $batch->id }})"
                                        class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors cursor-pointer"
                                        title="Edit Batch"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    @if($batch->sales_count === 0)
                                        <button
                                            type="button"
                                            wire:click="delete({{ $batch->id }})"
                                            wire:confirm="Yakin ingin menghapus batch Open PO ini?"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                            title="Hapus Batch"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                Belum ada batch Open PO. Klik tombol di atas untuk membuat batch baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Modal Form Tambah / Edit Batch -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" wire:click="closeModal"></div>

            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all z-10">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $isEditing ? 'Edit Batch Open PO' : 'Buat Batch Open PO Baru' }}
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
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Kode Batch *
                                </label>
                                <input
                                    wire:model="kode_po"
                                    type="text"
                                    required
                                    placeholder="PO-202611-01"
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-mono font-bold uppercase text-slate-900"
                                >
                                @error('kode_po') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Status Batch *
                                </label>
                                <select
                                    wire:model="status"
                                    required
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900 font-semibold"
                                >
                                    <option value="draft">Draft</option>
                                    <option value="open">Open (Buka Pemesanan)</option>
                                    <option value="closed">Closed (Tutup)</option>
                                    <option value="completed">Completed (Selesai)</option>
                                </select>
                                @error('status') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Nama Batch PO *
                            </label>
                            <input
                                wire:model="nama_po"
                                type="text"
                                required
                                placeholder="Contoh: PO Batch #15 - Weekend Nusantara"
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900"
                            >
                            @error('nama_po') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Tanggal Buka *
                                </label>
                                <input
                                    wire:model="tanggal_buka"
                                    type="date"
                                    required
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900"
                                >
                                @error('tanggal_buka') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Tanggal Tutup *
                                </label>
                                <input
                                    wire:model="tanggal_tutup"
                                    type="date"
                                    required
                                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900"
                                >
                                @error('tanggal_tutup') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Jadwal Pengiriman Pesanan *
                            </label>
                            <input
                                wire:model="tanggal_pengiriman"
                                type="date"
                                required
                                class="w-full px-3 py-2 text-xs bg-amber-50/50 border border-amber-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-semibold text-slate-900"
                            >
                            @error('tanggal_pengiriman') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Catatan Batch (Opsional)
                            </label>
                            <textarea
                                wire:model="catatan"
                                rows="2"
                                placeholder="Contoh: Menu spesial rendang sapi & liwet komplit..."
                                class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-900"
                            ></textarea>
                            @error('catatan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
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
                            <span wire:loading.remove>{{ $isEditing ? 'Simpan Perubahan' : 'Buat Batch PO' }}</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Rekap Produksi Dapur (Detail Batch) -->
    @if($showDetailModal && $selectedBatch)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" wire:click="closeDetailModal"></div>

            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all z-10">
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                            {{ $selectedBatch->kode_po }}
                        </span>
                        <h3 class="text-base font-bold text-slate-900 mt-1">
                            Rekap Produksi Dapur — {{ $selectedBatch->nama_po }}
                        </h3>
                    </div>
                    <button type="button" wire:click="closeDetailModal" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200/60">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                    <!-- Metrics Bar -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <span class="text-[10px] font-semibold text-slate-400 uppercase">Jadwal Kirim</span>
                            <div class="text-xs font-bold text-amber-950 mt-0.5">
                                {{ $selectedBatch->tanggal_pengiriman->format('d M Y') }}
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <span class="text-[10px] font-semibold text-slate-400 uppercase">Total Pesanan</span>
                            <div class="text-sm font-bold text-slate-900 mt-0.5">
                                {{ $batchTotalOrders }} Order
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <span class="text-[10px] font-semibold text-slate-400 uppercase">Total Omzet Batch</span>
                            <div class="text-sm font-bold text-slate-900 mt-0.5">
                                <x-money :value="$batchOmzet" />
                            </div>
                        </div>
                    </div>

                    <!-- Cooking Summary Table (Sangat Berguna untuk Tim Dapur!) -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span>Kuantitas Masak / Produksi per Menu:</span>
                            </h4>
                            <span class="text-[11px] text-slate-400">Total item pesanan terkonfirmasi</span>
                        </div>

                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-2.5">Menu / Produk Makanan</th>
                                        <th class="px-4 py-2.5">Kategori</th>
                                        <th class="px-4 py-2.5 text-center">Qty Harus Dimasak</th>
                                        <th class="px-4 py-2.5 text-right">Kontribusi Omzet</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    @forelse($kitchenRecap as $item)
                                        <tr class="hover:bg-amber-50/40">
                                            <td class="px-4 py-3 font-bold text-slate-900">
                                                {{ $item['nama_produk'] }}
                                            </td>
                                            <td class="px-4 py-3 text-slate-500">
                                                {{ $item['kategori'] }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-900 font-mono">
                                                    {{ $item['total_qty'] }} paket
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right font-bold text-slate-900">
                                                <x-money :value="$item['total_nominal']" />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">
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
                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Gunakan data ini untuk belanja bahan & persiapan porsi dapur.</span>
                    <button
                        type="button"
                        wire:click="closeDetailModal"
                        class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
