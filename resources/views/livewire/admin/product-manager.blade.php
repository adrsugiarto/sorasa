<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Master Data Produk & Menu</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar makanan, dessert, dan hampers yang dijual pada Open PO</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($products as $product)
            <x-card class="flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-semibold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200/50">
                            {{ $product->kategori }}
                        </span>
                        <span class="text-[11px] font-mono text-slate-400">{{ $product->kode_produk }}</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mt-2.5">{{ $product->nama_produk }}</h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $product->deskripsi }}</p>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 block uppercase">Harga Jual</span>
                        <span class="text-sm font-bold text-slate-900">
                            <x-money :value="$product->harga_jual" />
                        </span>
                    </div>
                    <div>
                        @if($product->recipe)
                            <x-badge variant="emerald">Ada Resep</x-badge>
                        @else
                            <x-badge variant="slate">Belum Ada Resep</x-badge>
                        @endif
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>
</div>
