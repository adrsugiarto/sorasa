<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Pengeluaran Operasional Lain</h1>
            <p class="text-xs text-slate-500 mt-1">Biaya non-bahan baku seperti gas, kemasan, tenaga kerja, dan utilitas dapur</p>
        </div>
    </div>

    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Kategori Biaya</th>
                        <th class="px-5 py-3.5">Deskripsi Pengeluaran</th>
                        <th class="px-5 py-3.5">Metode Bayar</th>
                        <th class="px-5 py-3.5 text-right">Nominal</th>
                        <th class="px-5 py-3.5">Batch PO</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($expenses as $e)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3.5 text-slate-600 font-medium">{{ $e->tanggal_pengeluaran->format('d M Y') }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $e->kategori_biaya }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">{{ $e->nama_pengeluaran }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $e->metode_pembayaran }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-900">
                                <x-money :value="$e->nominal" />
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $e->openPo->nama_po ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada pengeluaran lain.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
