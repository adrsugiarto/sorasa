<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Jadwal Pengantaran & Format Kurir</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola data alamat penerima, link maps, dan salin teks format kurir WhatsApp</p>
        </div>
    </div>

    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Jadwal Kirim</th>
                        <th class="px-5 py-3.5">Penerima & Kontak</th>
                        <th class="px-5 py-3.5">Alamat & Patokan</th>
                        <th class="px-5 py-3.5">Status Kirim</th>
                        <th class="px-5 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($deliveries as $deliv)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3.5 font-semibold text-slate-900">
                                <div>{{ $deliv->tanggal_pengiriman->format('d M Y') }}</div>
                                <span class="text-[11px] text-amber-700 font-mono">{{ $deliv->jam_pengiriman ?? 'Fleksibel' }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-slate-900">{{ $deliv->nama_penerima }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $deliv->no_telepon_penerima }}</div>
                            </td>
                            <td class="px-5 py-3.5 max-w-xs">
                                <p class="text-slate-800 line-clamp-2">{{ $deliv->alamat_lengkap }}</p>
                                @if($deliv->patokan_lokasi)
                                    <span class="text-[10px] text-slate-400">Patokan: {{ $deliv->patokan_lokasi }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @if($deliv->status_pengantaran === 'terkirim')
                                    <x-badge variant="emerald">TERKIRIM</x-badge>
                                @elseif($deliv->status_pengantaran === 'dalam_pengiriman')
                                    <x-badge variant="sky">DI JALAN</x-badge>
                                @else
                                    <x-badge variant="slate">DIJADWALKAN</x-badge>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <button type="button" class="px-2.5 py-1 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-xs font-semibold">
                                    Salin Info Kurir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada pengantaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
