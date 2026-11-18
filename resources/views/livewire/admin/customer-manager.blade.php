<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Database Pelanggan & Riwayat Belanja</h1>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Daftar pelanggan, akumulasi nilai belanja, riwayat order, dan chat WhatsApp cepat</p>
        </div>
        <div>
            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-xs transition-colors cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span>Tambah Pelanggan Baru</span>
            </button>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-gray-900 p-3 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs">
        <div class="relative flex-1 max-w-md">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari nama pelanggan, nomor WhatsApp, atau alamat..."
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
                @foreach($customerCategories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Customers Table -->
    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-500 dark:text-gray-400 border-b border-slate-200 dark:border-gray-800 uppercase font-semibold text-[11px] tracking-wider">
                    <tr>
                        <th class="px-3.5 py-2.5">Nama Pelanggan</th>
                        <th class="px-3.5 py-2.5">Kontak WhatsApp</th>
                        <th class="px-3.5 py-2.5">Kategori</th>
                        <th class="px-3.5 py-2.5 text-center">Total Order</th>
                        <th class="px-3.5 py-2.5 text-right">Total Belanja</th>
                        <th class="px-3.5 py-2.5">Terakhir Pesan</th>
                        <th class="px-3.5 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-[13px] leading-5">
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="px-3.5 py-2.5 font-medium text-slate-900 dark:text-white">
                                <div>{{ $c->nama }}</div>
                                <span class="text-[11px] text-slate-400 dark:text-gray-500 font-normal line-clamp-1 max-w-xs">{{ $c->alamat }}</span>
                            </td>
                            <td class="px-3.5 py-2.5">
                                <a
                                    href="{{ $c->wa_link }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400 hover:underline font-mono text-[11px] bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-md border border-emerald-200/80 dark:border-emerald-800 transition-colors tabular-nums"
                                    title="Klik untuk membuka chat WhatsApp"
                                >
                                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.072-2.125-.521-1.828-.759-2.999-2.618-3.09-2.738-.088-.121-.734-.978-.734-1.865 0-.887.464-1.324.63-1.503.167-.179.364-.224.486-.224.123 0 .243.001.35.006.111.006.26-.042.408.312.149.364.512 1.25.557 1.341.045.091.076.197.015.318-.06.121-.09.197-.181.303-.09.106-.19.237-.272.318-.09.091-.184.19-.08.369.106.182.47 1.05 1.008 1.53.693.618 1.277.81 1.458.899.182.091.288.076.395-.045.106-.121.454-.53.576-.712.121-.182.242-.152.409-.091.167.061 1.06.5 1.242.591.182.091.303.136.35.212.045.076.045.439-.099.844z"/></svg>
                                    <span>{{ $c->no_whatsapp }}</span>
                                </a>
                            </td>
                            <td class="px-3.5 py-2.5">
                                @if($c->kategori_pelanggan === 'VIP')
                                    <x-badge variant="amber" :dot="true">VIP</x-badge>
                                @elseif($c->kategori_pelanggan === 'Reseller')
                                    <x-badge variant="indigo" :dot="true">Reseller</x-badge>
                                @elseif($c->kategori_pelanggan === 'Keluarga/Teman')
                                    <x-badge variant="sky">Keluarga/Teman</x-badge>
                                @else
                                    <x-badge variant="slate">Reguler</x-badge>
                                @endif
                            </td>
                            <td class="px-3.5 py-2.5 text-center font-medium text-slate-900 dark:text-white tabular-nums">
                                {{ $c->sales_count }}
                            </td>
                            <td class="px-3.5 py-2.5 text-right font-semibold text-slate-900 dark:text-white tabular-nums">
                                <x-money :value="$c->total_belanja ?? 0" />
                            </td>
                            <td class="px-3.5 py-2.5 text-slate-500 dark:text-gray-400 tabular-nums">
                                {{ $c->last_order_at ? \Illuminate\Support\Carbon::parse($c->last_order_at)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-3.5 py-2.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button
                                        type="button"
                                        wire:click="showCustomerDetail({{ $c->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-slate-50 dark:bg-gray-800 hover:bg-slate-100 dark:hover:bg-gray-750 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-700 font-medium text-xs transition-colors cursor-pointer"
                                        title="Lihat riwayat order dan belanja"
                                    >
                                        <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Riwayat</span>
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $c->id }})"
                                        class="p-1 rounded-md text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                                        title="Edit Pelanggan"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    @if($c->sales_count === 0)
                                        <button
                                            type="button"
                                            wire:click="delete({{ $c->id }})"
                                            wire:confirm="Yakin ingin menghapus pelanggan '{{ $c->nama }}'?"
                                            class="p-1 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors cursor-pointer"
                                            title="Hapus Pelanggan"
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
                            <td colspan="7" class="px-3.5 py-8 text-center text-slate-400 dark:text-gray-500">
                                Tidak ada data pelanggan yang cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Pagination -->
    <div>
        {{ $customers->links() }}
    </div>

    <!-- Modal Form Tambah / Edit Customer -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/40 dark:bg-black/70 backdrop-blur-xs transition-opacity" wire:click="closeModal"></div>

            <div class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-lg shadow-xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all z-10 text-slate-800 dark:text-slate-200">
                <div class="px-5 py-3.5 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                        {{ $isEditing ? 'Edit Data Pelanggan' : 'Tambah Pelanggan Baru' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-800">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="p-5 space-y-3.5 max-h-[75vh] overflow-y-auto text-xs">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Nama Lengkap Pelanggan *
                            </label>
                            <input
                                wire:model="nama"
                                type="text"
                                required
                                placeholder="Contoh: Maya Kusuma"
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                            >
                            @error('nama') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    Nomor WhatsApp *
                                </label>
                                <input
                                    wire:model="no_whatsapp"
                                    type="text"
                                    required
                                    placeholder="081234567890"
                                    class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-mono text-slate-900 dark:text-white tabular-nums"
                                >
                                @error('no_whatsapp') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    Kategori Pelanggan *
                                </label>
                                <select
                                    wire:model="kategori_pelanggan"
                                    required
                                    class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                                >
                                    @foreach($customerCategories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_pelanggan') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Alamat Lengkap Pengiriman *
                            </label>
                            <textarea
                                wire:model="alamat"
                                rows="3"
                                required
                                placeholder="Jalan, nomor rumah, perumahan/komplek, kelurahan, kecamatan, kota..."
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                            ></textarea>
                            @error('alamat') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                Catatan Khusus Pelanggan
                            </label>
                            <textarea
                                wire:model="catatan"
                                rows="2"
                                placeholder="Contoh: Suka sambal ekstra, arisan tiap minggu ke-3..."
                                class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                            ></textarea>
                            @error('catatan') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
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
                            <span wire:loading.remove>{{ $isEditing ? 'Simpan Perubahan' : 'Simpan Pelanggan' }}</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Riwayat Pesanan & Detail Pelanggan -->
    @if($showDetailModal && $selectedCustomer)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/50 dark:bg-black/70 backdrop-blur-xs transition-opacity" wire:click="closeDetailModal"></div>

            <div class="relative w-full max-w-3xl bg-white dark:bg-gray-900 rounded-lg shadow-xl border border-slate-200 dark:border-gray-800 overflow-hidden transform transition-all z-10 text-slate-800 dark:text-slate-200">
                <div class="px-5 py-3.5 bg-slate-50/80 dark:bg-gray-800/80 border-b border-slate-200 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ $selectedCustomer->nama }}</h3>
                            <x-badge variant="blue">{{ $selectedCustomer->kategori_pelanggan }}</x-badge>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">{{ $selectedCustomer->no_whatsapp }} • {{ $selectedCustomer->alamat }}</p>
                    </div>
                    <button type="button" wire:click="closeDetailModal" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-200/60 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                    <!-- Metrics Summary -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="p-3 bg-slate-50 dark:bg-gray-800/60 rounded-md border border-slate-200 dark:border-gray-700 text-center">
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Pesanan</span>
                            <div class="text-sm font-semibold text-slate-900 dark:text-white mt-0.5 tabular-nums">
                                {{ $selectedCustomer->sales->count() }} Kali Order
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-gray-800/60 rounded-md border border-slate-200 dark:border-gray-700 text-center">
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Akumulasi Belanja</span>
                            <div class="text-sm font-bold text-slate-900 dark:text-white mt-0.5 tabular-nums">
                                <x-money :value="$selectedCustomer->sales->sum('total_tagihan')" />
                            </div>
                        </div>
                        <div class="p-3 bg-rose-50/60 dark:bg-rose-950/30 rounded-md border border-rose-200/80 dark:border-rose-900/60 text-center">
                            <span class="text-[10px] font-semibold text-rose-700 dark:text-rose-400 uppercase tracking-wider">Sisa Piutang Pending</span>
                            <div class="text-sm font-bold text-rose-800 dark:text-rose-400 mt-0.5 tabular-nums">
                                @php
                                    $custTotal = $selectedCustomer->sales->sum('total_tagihan');
                                    $custPaid = $selectedCustomer->sales->flatMap->payments->where('status_verifikasi', 'verified')->sum('nominal_bayar');
                                    $custPiutang = max(0, $custTotal - $custPaid);
                                @endphp
                                <x-money :value="$custPiutang" />
                            </div>
                        </div>
                    </div>

                    @if($selectedCustomer->catatan)
                        <div class="p-3 rounded-md bg-blue-50/50 dark:bg-blue-950/30 border border-blue-200/60 dark:border-blue-900/60 text-xs text-blue-900 dark:text-blue-300">
                            <span class="font-semibold">Catatan Khusus:</span> {{ $selectedCustomer->catatan }}
                        </div>
                    @endif

                    <!-- Orders History Table -->
                    <div>
                        <h4 class="text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wider mb-2">Riwayat Transaksi Pesanan:</h4>
                        <div class="border border-slate-200 dark:border-gray-800 rounded-md overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-500 dark:text-gray-400 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-200 dark:border-gray-800">
                                    <tr>
                                        <th class="px-3.5 py-2">No. Order & Batch</th>
                                        <th class="px-3.5 py-2">Tanggal</th>
                                        <th class="px-3.5 py-2">Rincian Menu Dipesan</th>
                                        <th class="px-3.5 py-2 text-right">Total Tagihan</th>
                                        <th class="px-3.5 py-2">Status Bayar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-[13px] leading-5">
                                    @forelse($selectedCustomer->sales as $sale)
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40">
                                            <td class="px-3.5 py-2.5 font-medium text-slate-900 dark:text-white">
                                                <div class="font-mono tabular-nums">{{ $sale->nomor_order }}</div>
                                                <span class="text-[11px] text-slate-400 dark:text-gray-500 font-normal">{{ $sale->openPo->nama_po ?? '-' }}</span>
                                            </td>
                                            <td class="px-3.5 py-2.5 text-slate-600 dark:text-gray-400 tabular-nums">
                                                {{ $sale->tanggal_pemesanan->format('d M Y') }}
                                            </td>
                                            <td class="px-3.5 py-2.5">
                                                <ul class="space-y-0.5">
                                                    @foreach($sale->items as $item)
                                                        <li class="text-[11px]">
                                                            <span class="font-semibold text-slate-900 dark:text-white tabular-nums">{{ $item->jumlah_paket }}x</span> {{ $item->nama_produk_snapshot }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="px-3.5 py-2.5 text-right font-semibold text-slate-900 dark:text-white tabular-nums">
                                                <x-money :value="$sale->total_tagihan" />
                                            </td>
                                            <td class="px-3.5 py-2.5">
                                                @if($sale->status_pembayaran === 'lunas')
                                                    <x-badge variant="emerald">LUNAS</x-badge>
                                                @elseif($sale->status_pembayaran === 'dp')
                                                    <x-badge variant="amber">DP (Sisa <x-money :value="$sale->remaining_balance" />)</x-badge>
                                                @else
                                                    <x-badge variant="rose">BELUM BAYAR</x-badge>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-3.5 py-6 text-center text-slate-400 dark:text-gray-500">Belum ada riwayat pesanan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3 bg-slate-50 dark:bg-gray-800/60 border-t border-slate-100 dark:border-gray-800 flex items-center justify-between text-xs">
                    <a
                        href="{{ $selectedCustomer->wa_link }}"
                        target="_blank"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-md shadow-xs cursor-pointer"
                    >
                        <span>Chat Pelanggan di WhatsApp</span>
                    </a>
                    <button
                        type="button"
                        wire:click="closeDetailModal"
                        class="px-3.5 py-1.5 bg-slate-200 dark:bg-gray-800 hover:bg-slate-300 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-300 font-medium rounded-md cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
