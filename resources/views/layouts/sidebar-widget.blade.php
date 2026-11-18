@php
    $activePo = \App\Models\OpenPo::where('status', 'open')->latest('id')->first();
@endphp
<div class="mb-4">
    <div class="mx-auto rounded-2xl bg-amber-500/10 border border-amber-500/20 px-3.5 py-4 text-center dark:bg-amber-500/5">
        <div class="w-8 h-8 rounded-lg bg-amber-600 text-white font-bold flex items-center justify-center text-xs mx-auto mb-2 shadow-2xs">
            S
        </div>
        <h4 class="text-xs font-bold text-gray-900 dark:text-white">
            SORASA Kitchen & Pastry
        </h4>
        @if($activePo)
            <div class="mt-2 pt-2 border-t border-amber-500/20">
                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-amber-600 text-white">
                    PO BUKA: {{ $activePo->kode_po }}
                </span>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 truncate font-medium">
                    Kirim: {{ $activePo->tanggal_pengiriman->format('d M Y') }}
                </p>
            </div>
        @else
            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">
                Manajemen Operasional PO
            </p>
        @endif
    </div>
</div>
