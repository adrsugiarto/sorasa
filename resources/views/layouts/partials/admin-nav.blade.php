<nav class="space-y-6">
    <!-- Section: Utama -->
    <div>
        <p class="px-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Utama</p>
        <div class="space-y-1">
            <a
                href="{{ route('admin.dashboard') }}"
                @class([
                    'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.dashboard'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.dashboard'),
                ])
            >
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a
                href="{{ route('admin.open-po') }}"
                @class([
                    'flex items-center justify-between px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.open-po*'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.open-po*'),
                ])
            >
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.open-po*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Batch Open PO</span>
                </div>
            </a>

            <a
                href="{{ route('admin.sales.pos') }}"
                @class([
                    'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.sales.pos'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.sales.pos'),
                ])
            >
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.sales.pos') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span>Kasir POS (Order Baru)</span>
            </a>
        </div>
    </div>

    <!-- Section: Transaksi & Operasional -->
    <div>
        <p class="px-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Operasional</p>
        <div class="space-y-1">
            <a
                href="{{ route('admin.sales.index') }}"
                @class([
                    'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.sales.index*'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.sales.index*'),
                ])
            >
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.sales.index*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Pesanan Penjualan</span>
            </a>

            <a
                href="{{ route('admin.deliveries') }}"
                @class([
                    'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.deliveries*'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.deliveries*'),
                ])
            >
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.deliveries*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
                <span>Pengantaran & Kurir</span>
            </a>

            <a
                href="{{ route('admin.payments') }}"
                @class([
                    'flex items-center justify-between px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.payments*'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.payments*'),
                ])
            >
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.payments*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Pembayaran & Piutang</span>
                </div>
            </a>

            <a
                href="{{ route('admin.purchases') }}"
                @class([
                    'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.purchases*'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.purchases*'),
                ])
            >
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.purchases*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Pembelian Bahan</span>
            </a>

            <a
                href="{{ route('admin.expenses') }}"
                @class([
                    'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.expenses*'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.expenses*'),
                ])
            >
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.expenses*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                </svg>
                <span>Pengeluaran Lain</span>
            </a>
        </div>
    </div>

    <!-- Section: Master Data -->
    <div>
        <p class="px-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Master Data</p>
        <div class="space-y-1">
            <a
                href="{{ route('admin.products') }}"
                @class([
                    'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.products*'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.products*'),
                ])
            >
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.products*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Master Produk</span>
            </a>

            <a
                href="{{ route('admin.recipes') }}"
                @class([
                    'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.recipes*'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.recipes*'),
                ])
            >
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.recipes*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Resep & Estimasi HPP</span>
            </a>

            <a
                href="{{ route('admin.customers') }}"
                @class([
                    'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.customers*'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.customers*'),
                ])
            >
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.customers*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Database Customer</span>
            </a>
        </div>
    </div>

    <!-- Section: Laporan & Sistem -->
    <div>
        <p class="px-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Laporan & Pengaturan</p>
        <div class="space-y-1">
            <a
                href="{{ route('admin.reports') }}"
                @class([
                    'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.reports*'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.reports*'),
                ])
            >
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.reports*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Laba Rugi Sederhana</span>
            </a>

            <a
                href="{{ route('admin.settings') }}"
                @class([
                    'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                    'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.settings*'),
                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.settings*'),
                ])
            >
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.settings*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Profil & Pengaturan Usaha</span>
            </a>

            @if(auth()->user()?->isOwner())
                <a
                    href="{{ route('admin.users') }}"
                    @class([
                        'flex items-center gap-3 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors',
                        'bg-amber-50 text-amber-900 font-bold border border-amber-200/60' => request()->routeIs('admin.users*'),
                        'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' => ! request()->routeIs('admin.users*'),
                    ])
                >
                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.users*') ? 'text-amber-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Kelola Akun Admin</span>
                </a>
            @endif
        </div>
    </div>
</nav>
