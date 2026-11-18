<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Resep Standar & Estimasi HPP Bahan</h1>
            <p class="text-xs text-slate-500 mt-1">Standarisasi takaran bahan per batch masakan & perkiraan modal bahan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($recipes as $recipe)
            <x-card :title="$recipe->nama_resep" :description="'Untuk produk: ' . ($recipe->product->nama_produk ?? '-')">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 text-xs">
                        <div>
                            <span class="text-slate-500">Hasil Porsi Batch:</span>
                            <span class="font-bold text-slate-900 ml-1">{{ $recipe->porsi_hasil }} Porsi</span>
                        </div>
                        <div>
                            <span class="text-slate-500">Total Modal Bahan:</span>
                            <span class="font-bold text-emerald-800 ml-1">
                                <x-money :value="$recipe->total_biaya_bahan" />
                            </span>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Rincian Bahan Baku:</h4>
                        <div class="divide-y divide-slate-100 text-xs">
                            @foreach($recipe->items as $item)
                                <div class="py-1.5 flex items-center justify-between text-slate-600">
                                    <span>{{ $item->nama_bahan }} ({{ number_format($item->qty, 1) }} {{ $item->satuan }})</span>
                                    <span class="font-semibold text-slate-900">
                                        <x-money :value="$item->harga_total" />
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>
</div>
