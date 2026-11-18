<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Pembayaran & Tracking Piutang</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar pesanan dengan status DP atau Belum Bayar yang membutuhkan tindak lanjut</p>
        </div>
    </div>

    <x-card noPadding="true" title="Daftar Piutang Pelanggan">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">No. Order</th>
                        <th class="px-5 py-3.5">Pelanggan</th>
                        <th class="px-5 py-3.5 text-right">Total Tagihan</th>
                        <th class="px-5 py-3.5 text-right">Sudah Dibayar</th>
                        <th class="px-5 py-3.5 text-right">Sisa Piutang</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($unpaidSales as $sale)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3.5 font-mono font-semibold text-slate-900">{{ $sale->nomor_order }}</td>
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-slate-900">{{ $sale->customer->nama ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $sale->customer->no_whatsapp ?? '' }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-right font-medium text-slate-900">
                                <x-money :value="$sale->total_tagihan" />
                            </td>
                            <td class="px-5 py-3.5 text-right font-medium text-emerald-700">
                                <x-money :value="$sale->total_paid" />
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold text-rose-700">
                                <x-money :value="$sale->remaining_balance" />
                            </td>
                            <td class="px-5 py-3.5">
                                @if($sale->status_pembayaran === 'dp')
                                    <x-badge variant="amber" :dot="true">DP</x-badge>
                                @else
                                    <x-badge variant="rose" :dot="true">BELUM BAYAR</x-badge>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Tidak ada piutang pending. Semua order lunas!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
