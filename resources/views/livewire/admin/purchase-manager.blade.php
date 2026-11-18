<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Pembelian Bahan Baku (Habis Pakai)</h1>
            <p class="text-xs text-slate-500 mt-1">Pencatatan belanja bahan produksi kuliner langsung sebagai biaya operasional</p>
        </div>
    </div>

    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Toko / Supplier</th>
                        <th class="px-5 py-3.5">Nama Barang</th>
                        <th class="px-5 py-3.5">Qty & Satuan</th>
                        <th class="px-5 py-3.5 text-right">Total Biaya</th>
                        <th class="px-5 py-3.5">Batch PO</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($purchases as $p)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3.5 text-slate-600 font-medium">{{ $p->tanggal_pembelian->format('d M Y') }}</td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">{{ $p->nama_toko_supplier }}</td>
                            <td class="px-5 py-3.5 text-slate-800">{{ $p->nama_barang }}</td>
                            <td class="px-5 py-3.5 font-mono text-slate-700">{{ number_format($p->jumlah, 0) }} {{ $p->satuan }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-900">
                                <x-money :value="$p->total_pembelian" />
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $p->openPo->nama_po ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada pembelian bahan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
