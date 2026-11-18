<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Kasir POS — Entri Pesanan Baru</h1>
            <p class="text-xs text-slate-500 mt-1">Input pemesanan pelanggan ke periode Open PO</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Products Grid (Left) -->
        <div class="lg:col-span-2 space-y-4">
            <x-card title="Katalog Menu Makanan">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($products as $product)
                        <div class="p-3.5 rounded-xl border border-slate-200/90 bg-slate-50/50 hover:bg-amber-50/50 hover:border-amber-300 transition-all flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200/50">{{ $product->kategori }}</span>
                                <h4 class="text-xs font-bold text-slate-900 mt-2">{{ $product->nama_produk }}</h4>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mt-1">{{ $product->deskripsi }}</p>
                            </div>
                            <div class="mt-3 pt-2 border-t border-slate-200/60 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900">
                                    <x-money :value="$product->harga_jual" />
                                </span>
                                <button type="button" class="text-xs font-semibold px-2.5 py-1 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                                    + Tambah
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        </div>

        <!-- Order Cart (Right) -->
        <div>
            <x-card title="Ringkasan Keranjang Pesanan" description="Detail pesanan yang akan dibuat">
                <div class="py-12 text-center text-slate-400 text-xs">
                    Pilih produk di sebelah kiri untuk memasukkan ke keranjang pesanan.
                </div>
            </x-card>
        </div>
    </div>
</div>
