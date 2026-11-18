<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Jadwal Pengantaran & Format Kurir</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola jadwal pengiriman pesanan, alamat, koordinat peta, dan salin format teks WhatsApp kurir</p>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 flex-1">
            <div class="relative flex-1 max-w-xs">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Cari penerima, no telepon, atau no order..."
                    class="w-full pl-9 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-800 placeholder-slate-400"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select
                wire:model.live="selectedStatus"
                class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-700 font-medium"
            >
                <option value="">Semua Status Pengantaran</option>
                <option value="menunggu">Menunggu</option>
                <option value="dijadwalkan">Dijadwalkan</option>
                <option value="dalam_pengiriman">Dalam Pengiriman</option>
                <option value="terkirim">Terkirim (Selesai)</option>
            </select>

            <input
                wire:model.live="filterDate"
                type="date"
                class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-700 font-medium"
            >
            @if($filterDate)
                <button
                    type="button"
                    wire:click="$set('filterDate', null)"
                    class="text-xs text-rose-600 font-medium hover:underline"
                >
                    Reset Tanggal
                </button>
            @endif
        </div>
    </div>

    <!-- Deliveries Table -->
    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Jadwal Kirim</th>
                        <th class="px-5 py-3.5">No. Order & Batch</th>
                        <th class="px-5 py-3.5">Penerima & Kontak</th>
                        <th class="px-5 py-3.5">Alamat Lengkap & Peta</th>
                        <th class="px-5 py-3.5">Menu Dipesan</th>
                        <th class="px-5 py-3.5">Status Pengantaran</th>
                        <th class="px-5 py-3.5 text-right">Aksi & Kurir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($deliveries as $deliv)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Jadwal -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-extrabold text-amber-950">
                                    {{ $deliv->tanggal_pengiriman->format('d M Y') }}
                                </div>
                                <span class="text-[11px] text-slate-500 font-medium block">
                                    {{ $deliv->jam_pengiriman ?: 'Fleksibel' }}
                                </span>
                            </td>

                            <!-- Order -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-slate-900 block">{{ $deliv->sale?->nomor_order }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $deliv->sale?->openPo?->nama_po ?? '-' }}</span>
                            </td>

                            <!-- Penerima -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $deliv->nama_penerima }}</div>
                                <a
                                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $deliv->no_telepon_penerima) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 text-[11px] font-mono text-emerald-700 hover:underline font-semibold"
                                >
                                    <span>{{ $deliv->no_telepon_penerima }}</span>
                                </a>
                            </td>

                            <!-- Alamat -->
                            <td class="px-5 py-4 max-w-xs">
                                <p class="text-slate-800 font-medium line-clamp-2 leading-relaxed">
                                    {{ $deliv->alamat_lengkap }}
                                </p>
                                @if($deliv->patokan_lokasi)
                                    <div class="text-[11px] text-amber-900 bg-amber-50/70 px-1.5 py-0.5 rounded mt-1 inline-block border border-amber-200/50">
                                        Patokan: {{ $deliv->patokan_lokasi }}
                                    </div>
                                @endif
                                @if($deliv->link_google_maps)
                                    <a
                                        href="{{ $deliv->link_google_maps }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1 text-[11px] text-sky-700 hover:text-sky-900 font-semibold mt-1 block"
                                    >
                                        <svg class="w-3 h-3 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Buka Google Maps</span>
                                    </a>
                                @endif
                            </td>

                            <!-- Items -->
                            <td class="px-5 py-4 max-w-xs">
                                <ul class="space-y-0.5 text-[11px]">
                                    @foreach($deliv->sale?->items ?? [] as $item)
                                        <li class="truncate">
                                            <span class="font-bold text-slate-900">{{ $item->jumlah_paket }}x</span> {{ $item->nama_produk_snapshot }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <!-- Status Pengantaran Dropdown -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <select
                                    wire:change="updateStatus({{ $deliv->id }}, $event.target.value)"
                                    @class([
                                        'text-xs font-semibold rounded-lg px-2.5 py-1 border transition-colors cursor-pointer',
                                        'bg-emerald-50 text-emerald-800 border-emerald-200' => $deliv->status_pengantaran === 'terkirim',
                                        'bg-sky-50 text-sky-800 border-sky-200' => $deliv->status_pengantaran === 'dalam_pengiriman',
                                        'bg-amber-50 text-amber-800 border-amber-200' => $deliv->status_pengantaran === 'dijadwalkan',
                                        'bg-slate-100 text-slate-700 border-slate-300' => $deliv->status_pengantaran === 'menunggu',
                                    ])
                                >
                                    <option value="menunggu" @selected($deliv->status_pengantaran === 'menunggu')>Menunggu</option>
                                    <option value="dijadwalkan" @selected($deliv->status_pengantaran === 'dijadwalkan')>Dijadwalkan</option>
                                    <option value="dalam_pengiriman" @selected($deliv->status_pengantaran === 'dalam_pengiriman')>Dalam Pengiriman</option>
                                    <option value="terkirim" @selected($deliv->status_pengantaran === 'terkirim')>Terkirim (Selesai)</option>
                                </select>
                            </td>

                            <!-- Action & Copy Courier Button -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5" x-data="{ copied: false }">
                                    <!-- Copy Courier Format Button -->
                                    <button
                                        type="button"
                                        x-on:click="navigator.clipboard.writeText(`{{ addslashes(\App\Livewire\Admin\DeliveryManager::formatCourierText($deliv)) }}`); copied = true; setTimeout(() => copied = false, 2500)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer shadow-2xs"
                                        :class="copied ? 'bg-emerald-600 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-800'"
                                        title="Salin teks rapi untuk dikirimkan ke WhatsApp kurir"
                                    >
                                        <template x-if="!copied">
                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                        </template>
                                        <template x-if="copied">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </template>
                                        <span x-text="copied ? 'Tersalin!' : 'Salin Info Kurir'"></span>
                                    </button>

                                    <!-- Edit Button -->
                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $deliv->id }})"
                                        class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors"
                                        title="Edit Jadwal & Alamat"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
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
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" wire:click="closeEditModal"></div>

            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all z-10">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900">Edit Informasi Pengantaran</h3>
                    <button type="button" wire:click="closeEditModal" class="p-1 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Tanggal Kirim *</label>
                                <input
                                    wire:model="tanggal_pengiriman"
                                    type="date"
                                    required
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900 font-semibold"
                                >
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Jam Kirim</label>
                                <input
                                    wire:model="jam_pengiriman"
                                    type="text"
                                    placeholder="Contoh: 11:30"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Nama Penerima *</label>
                                <input
                                    wire:model="nama_penerima"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900 font-semibold"
                                >
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">No. Telepon Penerima *</label>
                                <input
                                    wire:model="no_telepon_penerima"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900 font-mono"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Alamat Lengkap *</label>
                            <textarea
                                wire:model="alamat_lengkap"
                                rows="3"
                                required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900"
                            ></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Patokan Lokasi</label>
                                <input
                                    wire:model="patokan_lokasi"
                                    type="text"
                                    placeholder="Contoh: Depan taman bermain"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900"
                                >
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Link Google Maps</label>
                                <input
                                    wire:model="link_google_maps"
                                    type="text"
                                    placeholder="https://maps.google.com/..."
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Status Pengantaran *</label>
                            <select
                                wire:model="status_pengantaran"
                                required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900 font-semibold"
                            >
                                <option value="menunggu">Menunggu</option>
                                <option value="dijadwalkan">Dijadwalkan</option>
                                <option value="dalam_pengiriman">Dalam Pengiriman</option>
                                <option value="terkirim">Terkirim</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Catatan Kurir</label>
                            <input
                                wire:model="catatan_kurir"
                                type="text"
                                placeholder="Contoh: Titip sekuriti pos barat jika tidak ada orang..."
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-900"
                            >
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button
                            type="button"
                            wire:click="closeEditModal"
                            class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-xl font-semibold"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-semibold shadow-xs"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
