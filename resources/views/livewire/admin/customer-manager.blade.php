<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Database Pelanggan & Riwayat</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar customer setia, total nilai belanja, dan link cepat chat WhatsApp</p>
        </div>
    </div>

    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Nama Customer</th>
                        <th class="px-5 py-3.5">No. WhatsApp</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5 text-center">Total Order</th>
                        <th class="px-5 py-3.5 text-right">Total Belanja</th>
                        <th class="px-5 py-3.5">Alamat Lengkap</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3.5 font-bold text-slate-900">{{ $c->nama }}</td>
                            <td class="px-5 py-3.5">
                                <a href="{{ $c->wa_link }}" target="_blank" class="inline-flex items-center gap-1.5 text-emerald-700 hover:text-emerald-900 font-mono font-semibold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/60">
                                    <span>{{ $c->no_whatsapp }}</span>
                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.072-2.125-.521-1.828-.759-2.999-2.618-3.09-2.738-.088-.121-.734-.978-.734-1.865 0-.887.464-1.324.63-1.503.167-.179.364-.224.486-.224.123 0 .243.001.35.006.111.006.26-.042.408.312.149.364.512 1.25.557 1.341.045.091.076.197.015.318-.06.121-.09.197-.181.303-.09.106-.19.237-.272.318-.09.091-.184.19-.08.369.106.182.47 1.05 1.008 1.53.693.618 1.277.81 1.458.899.182.091.288.076.395-.045.106-.121.454-.53.576-.712.121-.182.242-.152.409-.091.167.061 1.06.5 1.242.591.182.091.303.136.35.212.045.076.045.439-.099.844z"/></svg>
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                                    {{ $c->kategori_pelanggan }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center font-bold text-slate-900">{{ $c->sales->count() }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-900">
                                <x-money :value="$c->sales->sum('total_tagihan')" />
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 max-w-xs truncate">{{ $c->alamat }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data customer.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
