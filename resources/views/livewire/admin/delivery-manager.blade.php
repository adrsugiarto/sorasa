<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Jadwal Pengantaran & Format Kurir</h1>
            <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">Kelola jadwal pengiriman pesanan, alamat, koordinat peta, dan salin format teks WhatsApp kurir</p>
        </div>
    </div>

    <!-- Status Pengantaran Interactive Widgets -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <!-- Widget: Menunggu -->
        <button
            type="button"
            wire:click="filterByStatus('menunggu')"
            @class([
                'relative text-left p-4 rounded-xl border transition-all cursor-pointer group',
                'bg-blue-50/70 border-blue-500 ring-2 ring-blue-500/30 dark:bg-blue-950/40 dark:border-blue-600 dark:ring-blue-500/20 shadow-xs' => $selectedStatus === 'menunggu',
                'bg-white dark:bg-gray-900 border-slate-200 dark:border-gray-800 hover:border-slate-300 dark:hover:border-gray-700 hover:shadow-xs' => $selectedStatus !== 'menunggu',
            ])
        >
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-gray-400">
                    Menunggu
                </span>
                <span @class([
                    'inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold tracking-tight',
                    'bg-slate-100 text-slate-700 dark:bg-gray-800 dark:text-gray-300' => $selectedStatus !== 'menunggu',
                    'bg-blue-600 text-white font-semibold' => $selectedStatus === 'menunggu',
                ])>
                    {{ $selectedStatus === 'menunggu' ? 'Aktif' : 'Filter' }}
                </span>
            </div>
            <div class="mt-2.5 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white tabular-nums">
                    {{ $statusCounts['menunggu'] }}
                </div>
                <div class="p-2 rounded-lg bg-slate-100 text-slate-600 dark:bg-gray-800 dark:text-gray-400">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-1.5 truncate">
                Belum dijadwalkan
            </p>
        </button>

        <!-- Widget: Dijadwalkan -->
        <button
            type="button"
            wire:click="filterByStatus('dijadwalkan')"
            @class([
                'relative text-left p-4 rounded-xl border transition-all cursor-pointer group',
                'bg-blue-50/70 border-blue-500 ring-2 ring-blue-500/30 dark:bg-blue-950/40 dark:border-blue-600 dark:ring-blue-500/20 shadow-xs' => $selectedStatus === 'dijadwalkan',
                'bg-white dark:bg-gray-900 border-slate-200 dark:border-gray-800 hover:border-slate-300 dark:hover:border-gray-700 hover:shadow-xs' => $selectedStatus !== 'dijadwalkan',
            ])
        >
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                    Dijadwalkan
                </span>
                <span @class([
                    'inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold tracking-tight',
                    'bg-amber-50 text-amber-700 border border-amber-200/60 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800' => $selectedStatus !== 'dijadwalkan',
                    'bg-blue-600 text-white font-semibold' => $selectedStatus === 'dijadwalkan',
                ])>
                    {{ $selectedStatus === 'dijadwalkan' ? 'Aktif' : 'Filter' }}
                </span>
            </div>
            <div class="mt-2.5 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white tabular-nums">
                    {{ $statusCounts['dijadwalkan'] }}
                </div>
                <div class="p-2 rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-1.5 truncate">
                Rute siap diantar
            </p>
        </button>

        <!-- Widget: Dalam Pengiriman -->
        <button
            type="button"
            wire:click="filterByStatus('dalam_pengiriman')"
            @class([
                'relative text-left p-4 rounded-xl border transition-all cursor-pointer group',
                'bg-blue-50/70 border-blue-500 ring-2 ring-blue-500/30 dark:bg-blue-950/40 dark:border-blue-600 dark:ring-blue-500/20 shadow-xs' => $selectedStatus === 'dalam_pengiriman',
                'bg-white dark:bg-gray-900 border-slate-200 dark:border-gray-800 hover:border-slate-300 dark:hover:border-gray-700 hover:shadow-xs' => $selectedStatus !== 'dalam_pengiriman',
            ])
        >
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                    Dalam Pengiriman
                </span>
                <span @class([
                    'inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold tracking-tight',
                    'bg-blue-50 text-blue-700 border border-blue-200/60 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800' => $selectedStatus !== 'dalam_pengiriman',
                    'bg-blue-600 text-white font-semibold' => $selectedStatus === 'dalam_pengiriman',
                ])>
                    {{ $selectedStatus === 'dalam_pengiriman' ? 'Aktif' : 'Filter' }}
                </span>
            </div>
            <div class="mt-2.5 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white tabular-nums">
                    {{ $statusCounts['dalam_pengiriman'] }}
                </div>
                <div class="p-2 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h2m-6 0a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-1.5 truncate">
                Sedang di jalan ke lokasi
            </p>
        </button>

        <!-- Widget: Terkirim (Selesai) -->
        <button
            type="button"
            wire:click="filterByStatus('terkirim')"
            @class([
                'relative text-left p-4 rounded-xl border transition-all cursor-pointer group',
                'bg-blue-50/70 border-blue-500 ring-2 ring-blue-500/30 dark:bg-blue-950/40 dark:border-blue-600 dark:ring-blue-500/20 shadow-xs' => $selectedStatus === 'terkirim',
                'bg-white dark:bg-gray-900 border-slate-200 dark:border-gray-800 hover:border-slate-300 dark:hover:border-gray-700 hover:shadow-xs' => $selectedStatus !== 'terkirim',
            ])
        >
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                    Terkirim (Selesai)
                </span>
                <span @class([
                    'inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold tracking-tight',
                    'bg-emerald-50 text-emerald-700 border border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800' => $selectedStatus !== 'terkirim',
                    'bg-blue-600 text-white font-semibold' => $selectedStatus === 'terkirim',
                ])>
                    {{ $selectedStatus === 'terkirim' ? 'Aktif' : 'Filter' }}
                </span>
            </div>
            <div class="mt-2.5 flex items-baseline justify-between">
                <div class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white tabular-nums">
                    {{ $statusCounts['terkirim'] }}
                </div>
                <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-1.5 truncate">
                Telah diterima pemesan
            </p>
        </button>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-gray-900 p-4 rounded-xl border border-slate-200 dark:border-gray-800 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
            <div class="relative flex-1 max-w-sm">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Cari penerima, no telepon, atau no order..."
                    class="w-full pl-10 pr-3.5 py-2 text-sm bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800 dark:text-white placeholder-slate-400 font-medium"
                >
                <svg class="w-4.5 h-4.5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select
                wire:model.live="selectedStatus"
                class="px-3.5 py-2 text-sm bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300 font-medium"
            >
                <option value="">Semua Status Pengantaran ({{ $statusCounts['total'] }})</option>
                <option value="menunggu">Menunggu ({{ $statusCounts['menunggu'] }})</option>
                <option value="dijadwalkan">Dijadwalkan ({{ $statusCounts['dijadwalkan'] }})</option>
                <option value="dalam_pengiriman">Dalam Pengiriman ({{ $statusCounts['dalam_pengiriman'] }})</option>
                <option value="terkirim">Terkirim (Selesai) ({{ $statusCounts['terkirim'] }})</option>
            </select>

            <input
                wire:model.live="filterDate"
                type="date"
                class="px-3.5 py-2 text-sm bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300 font-medium"
            >
            @if($selectedStatus || $filterDate || $search)
                <button
                    type="button"
                    wire:click="resetFilters"
                    class="text-xs sm:text-sm text-rose-600 dark:text-rose-400 hover:text-rose-700 font-semibold hover:underline cursor-pointer inline-flex items-center gap-1 shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Reset Filter</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Deliveries Table -->
    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-600 dark:text-gray-400 border-b border-slate-200 dark:border-gray-800 uppercase font-bold text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Jadwal Kirim</th>
                        <th class="px-4 py-3.5">No. Order & Batch</th>
                        <th class="px-4 py-3.5">Penerima & Kontak</th>
                        <th class="px-4 py-3.5">Alamat Lengkap & Peta</th>
                        <th class="px-4 py-3.5">Menu Dipesan</th>
                        <th class="px-4 py-3.5">Status Pengantaran</th>
                        <th class="px-4 py-3.5 text-right">Aksi & Kurir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-sm leading-6">
                    @forelse($deliveries as $deliv)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40 transition-colors">
                            <!-- Jadwal -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="font-bold text-slate-900 dark:text-white tabular-nums">
                                    {{ $deliv->tanggal_pengiriman->format('d M Y') }}
                                </div>
                                <span class="text-xs text-slate-400 dark:text-gray-500 font-medium block">
                                    {{ $deliv->jam_pengiriman ?: 'Fleksibel' }}
                                </span>
                            </td>

                            <!-- Order -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="font-mono font-bold text-slate-900 dark:text-white block tabular-nums">{{ $deliv->sale?->nomor_order }}</span>
                                <span class="text-xs text-slate-400 dark:text-gray-500 font-normal">{{ $deliv->sale?->openPo?->nama_po ?? '-' }}</span>
                            </td>

                            <!-- Penerima -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="font-semibold text-slate-900 dark:text-white">{{ $deliv->nama_penerima }}</div>
                                <a
                                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $deliv->no_telepon_penerima) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 text-xs font-mono text-emerald-600 dark:text-emerald-400 hover:underline tabular-nums"
                                >
                                    <span>{{ $deliv->no_telepon_penerima }}</span>
                                </a>
                            </td>

                            <!-- Alamat -->
                            <td class="px-4 py-3.5 max-w-xs">
                                <p class="text-slate-700 dark:text-gray-300 text-xs sm:text-sm line-clamp-2 leading-relaxed">
                                    {{ $deliv->alamat_lengkap }}
                                </p>
                                @if($deliv->patokan_lokasi)
                                    <div class="text-xs text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded border border-blue-200/60 dark:border-blue-800 mt-1 inline-block">
                                        Patokan: {{ $deliv->patokan_lokasi }}
                                    </div>
                                @endif
                                @if($deliv->link_google_maps)
                                    <a
                                        href="{{ $deliv->link_google_maps }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 text-xs text-blue-600 dark:text-blue-400 hover:underline font-semibold mt-1.5 block"
                                    >
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Buka Google Maps</span>
                                    </a>
                                @endif
                            </td>

                            <!-- Items -->
                            <td class="px-4 py-3.5 max-w-xs">
                                <ul class="space-y-1 text-xs sm:text-sm">
                                    @foreach($deliv->sale?->items ?? [] as $item)
                                        <li class="truncate">
                                            <span class="font-semibold text-slate-900 dark:text-white tabular-nums">{{ $item->jumlah_paket }}x</span> {{ $item->nama_produk_snapshot }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Status Pengantaran Dropdown -->
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <select
                                    wire:change="updateStatus({{ $deliv->id }}, $event.target.value)"
                                    @class([
                                        'text-xs sm:text-sm font-semibold rounded-lg px-2.5 py-1.5 border transition-colors cursor-pointer bg-white dark:bg-gray-800',
                                        'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800' => $deliv->status_pengantaran === 'terkirim',
                                        'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800' => $deliv->status_pengantaran === 'dalam_pengiriman',
                                        'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800' => $deliv->status_pengantaran === 'dijadwalkan',
                                        'bg-slate-50 text-slate-700 border-slate-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700' => $deliv->status_pengantaran === 'menunggu',
                                    ])
                                >
                                    <option value="menunggu" @selected($deliv->status_pengantaran === 'menunggu')>Menunggu</option>
                                    <option value="dijadwalkan" @selected($deliv->status_pengantaran === 'dijadwalkan')>Dijadwalkan</option>
                                    <option value="dalam_pengiriman" @selected($deliv->status_pengantaran === 'dalam_pengiriman')>Dalam Pengiriman</option>
                                    <option value="terkirim" @selected($deliv->status_pengantaran === 'terkirim')>Terkirim (Selesai)</option>
                                </select>
                            </td>

                            <!-- Action & Copy Courier Button -->
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5" x-data="{ copied: false }">
                                    <!-- Copy Courier Format Button -->
                                    <button
                                        type="button"
                                        x-on:click="navigator.clipboard.writeText(`{{ addslashes(\App\Livewire\Admin\DeliveryManager::formatCourierText($deliv)) }}`); copied = true; setTimeout(() => copied = false, 2500)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer shadow-xs border"
                                        :class="copied ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 dark:bg-gray-800 hover:bg-slate-100 dark:hover:bg-gray-750 text-slate-700 dark:text-gray-300 border-slate-200 dark:border-gray-700'"
                                        title="Salin teks rapi untuk dikirimkan ke WhatsApp kurir"
                                    >
                                        <template x-if="!copied">
                                            <svg class="w-4 h-4 text-slate-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                        </template>
                                        <template x-if="copied">
                                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                        <span x-text="copied ? 'Tersalin!' : 'Salin Info Kurir'"></span>
                                    </button>

                                    <!-- Edit Button -->
                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $deliv->id }})"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                                        title="Edit Jadwal & Alamat"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3.5 py-8 text-center text-slate-400 dark:text-gray-500">
                                Tidak ada data pengantaran yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Pagination -->
    <div>
        {{ $deliveries->links() }}
    </div>

    <!-- Modal Edit Pengantaran -->
    <div
        x-data="{ show: @entangle('showEditModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
        style="display: none;"
        @keydown.escape.window="show = false; $wire.closeEditModal()"
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
            @click="show = false; $wire.closeEditModal()"
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
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Edit Informasi Pengantaran</h3>
                <button type="button" @click="show = false; $wire.closeEditModal()" class="p-1 rounded-md text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-800 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="save">
                <div class="p-5 space-y-3.5 max-h-[75vh] overflow-y-auto text-xs">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Tanggal Kirim *</label>
                            <input
                                wire:model="tanggal_pengiriman"
                                type="date"
                                required
                                class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                            >
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Jam Kirim</label>
                            <input
                                wire:model="jam_pengiriman"
                                type="text"
                                placeholder="Contoh: 11:30"
                                class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Nama Penerima *</label>
                            <input
                                wire:model="nama_penerima"
                                type="text"
                                required
                                class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                            >
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">No. Telepon Penerima *</label>
                            <input
                                wire:model="no_telepon_penerima"
                                type="text"
                                required
                                class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-mono tabular-nums"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Alamat Lengkap *</label>
                        <textarea
                            wire:model="alamat_lengkap"
                            rows="3"
                            required
                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Patokan Lokasi</label>
                            <input
                                wire:model="patokan_lokasi"
                                type="text"
                                placeholder="Contoh: Depan taman bermain"
                                class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                            >
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Link Google Maps</label>
                            <input
                                wire:model="link_google_maps"
                                type="text"
                                placeholder="https://maps.google.com/..."
                                class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Status Pengantaran *</label>
                        <select
                            wire:model="status_pengantaran"
                            required
                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                        >
                            <option value="menunggu">Menunggu</option>
                            <option value="dijadwalkan">Dijadwalkan</option>
                            <option value="dalam_pengiriman">Dalam Pengiriman</option>
                            <option value="terkirim">Terkirim</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Catatan Kurir</label>
                        <input
                            wire:model="catatan_kurir"
                            type="text"
                            placeholder="Contoh: Titip sekuriti pos barat jika tidak ada orang..."
                            class="w-full px-2.5 py-1.5 bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                        >
                    </div>
                </div>

                <div class="px-5 py-3 bg-slate-50 dark:bg-gray-800/60 border-t border-slate-100 dark:border-gray-800 flex items-center justify-end gap-2">
                    <button
                        type="button"
                        @click="show = false; $wire.closeEditModal()"
                        class="px-3 py-1.5 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-700 text-slate-700 dark:text-gray-300 text-xs font-medium rounded-md shadow-xs cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-xs cursor-pointer"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
