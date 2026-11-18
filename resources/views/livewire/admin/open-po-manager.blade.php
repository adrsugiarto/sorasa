<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Manajemen Batch Open PO</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola jadwal buka, tutup, status PO, dan jadwal pengiriman pesanan</p>
        </div>
    </div>

    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Kode & Nama Batch</th>
                        <th class="px-5 py-3.5">Periode Pemesanan</th>
                        <th class="px-5 py-3.5">Jadwal Pengiriman</th>
                        <th class="px-5 py-3.5">Total Order</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($batches as $batch)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-semibold text-slate-900">
                                <div>{{ $batch->nama_po }}</div>
                                <span class="text-[11px] font-mono text-slate-400">{{ $batch->kode_po }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $batch->tanggal_buka->format('d M') }} — {{ $batch->tanggal_tutup->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-amber-900">
                                {{ $batch->tanggal_pengiriman->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-900">{{ $batch->sales_count }}</span> pesanan
                            </td>
                            <td class="px-5 py-4">
                                @if($batch->status === 'open')
                                    <x-badge variant="amber" :dot="true">SEDANG BUKA</x-badge>
                                @elseif($batch->status === 'completed')
                                    <x-badge variant="emerald">SELESAI</x-badge>
                                @elseif($batch->status === 'closed')
                                    <x-badge variant="slate">DITUTUP</x-badge>
                                @else
                                    <x-badge variant="slate">DRAFT</x-badge>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada batch Open PO.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
