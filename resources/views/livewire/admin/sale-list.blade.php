<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Daftar Pesanan Penjualan</h1>
            <p class="text-xs text-slate-500 mt-1">Seluruh riwayat transaksi order pelanggan</p>
        </div>
        <a href="{{ route('admin.sales.pos') }}" class="px-3.5 py-2 bg-amber-600 text-white rounded-xl text-xs font-semibold hover:bg-amber-700 shadow-xs">
            + Order Baru (Kasir POS)
        </a>
    </div>

    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">No. Order</th>
                        <th class="px-5 py-3.5">Pelanggan</th>
                        <th class="px-5 py-3.5">Batch PO</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5 text-right">Total Tagihan</th>
                        <th class="px-5 py-3.5">Status Bayar</th>
                        <th class="px-5 py-3.5">Status Order</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3.5 font-mono font-semibold text-slate-900">
                                {{ $sale->nomor_order }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-900">{{ $sale->customer->nama ?? '-' }}</div>
                                <span class="text-[11px] text-slate-400">{{ $sale->customer->kategori_pelanggan ?? '' }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">
                                {{ $sale->openPo->nama_po ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">
                                {{ $sale->tanggal_pemesanan->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-900">
                                <x-money :value="$sale->total_tagihan" />
                            </td>
                            <td class="px-5 py-3.5">
                                @if($sale->status_pembayaran === 'lunas')
                                    <x-badge variant="emerald" :dot="true">LUNAS</x-badge>
                                @elseif($sale->status_pembayaran === 'dp')
                                    <x-badge variant="amber" :dot="true">DP</x-badge>
                                @else
                                    <x-badge variant="rose" :dot="true">BELUM BAYAR</x-badge>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 uppercase">
                                    {{ str_replace('_', ' ', $sale->status_order) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada pesanan penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
