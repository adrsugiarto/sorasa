@php
    $activePo = \App\Models\OpenPo::where('status', 'open')->latest('id')->first();
@endphp
<div class="mb-4">
    <div class="mx-auto rounded-lg bg-slate-50 border border-slate-200 px-3 py-3 text-center dark:bg-gray-800/60 dark:border-gray-800">
        <div class="w-7 h-7 rounded-md bg-blue-600 text-white font-bold flex items-center justify-center text-xs mx-auto mb-1.5 shadow-xs">
            S
        </div>
        <h4 class="text-xs font-semibold text-slate-900 dark:text-white">
            SORASA
        </h4>
        @if($activePo)
            <div class="mt-2 pt-2 border-t border-slate-200 dark:border-gray-700">
                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/40 dark:text-blue-300">
                    PO BUKA: {{ $activePo->kode_po }}
                </span>
                <p class="text-[10px] text-slate-500 dark:text-gray-400 mt-1 truncate font-medium">
                    Kirim: {{ $activePo->tanggal_pengiriman->format('d M Y') }}
                </p>
            </div>
        @else
            <p class="text-[10px] text-slate-500 dark:text-gray-400 mt-0.5">
                Point of Sale (POS)
            </p>
        @endif
    </div>
</div>
