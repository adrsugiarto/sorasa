@php
    $activePo = \App\Models\OpenPo::where('status', 'open')->latest('id')->first();
@endphp

<header
    class="sticky top-0 z-40 flex w-full shrink-0 border-b border-slate-200 bg-white/95 backdrop-blur-md dark:border-gray-800 dark:bg-gray-900/95"
    x-data="{
        isApplicationMenuOpen: false,
        toggleApplicationMenu() {
            this.isApplicationMenuOpen = !this.isApplicationMenuOpen;
        }
    }">
    <div class="flex grow items-center justify-between px-4 py-2.5 sm:px-6">
        <!-- Left side content -->
        <div class="flex items-center gap-3">
            <!-- Desktop sidebar toggle (xl and up) -->
            <button
                type="button"
                class="hidden xl:inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 transition-colors"
                aria-label="Toggle Sidebar"
                @click.stop="$store.sidebar.toggleExpanded()"
            >
                <x-svg.menu-two class="size-4" />
            </button>

            <!-- Mobile sidebar toggle (below xl) -->
            <button
                type="button"
                class="xl:hidden inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 transition-colors"
                aria-label="Toggle Mobile Sidebar"
                @click.stop="$store.sidebar.toggleMobileOpen()"
            >
                <x-svg.menu-two class="size-4" />
            </button>

            <!-- Active Open PO Status Pill -->
            @if($activePo)
                <div class="hidden sm:flex items-center gap-2 px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:border-blue-800 dark:text-blue-300 text-xs font-medium">
                    <span class="w-2 h-2 rounded-full bg-blue-600 shrink-0"></span>
                    <span class="font-semibold">{{ $activePo->nama_po }}</span>
                    <span class="text-blue-600/80 dark:text-blue-400/80 tabular-nums">• Kirim: {{ $activePo->tanggal_pengiriman->format('d M Y') }}</span>
                </div>
            @endif
        </div>

        <!-- Right side content -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Quick Link POS -->
            <a
                href="{{ route('admin.sales.pos') }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium shadow-xs transition-all active:scale-95"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">+ Kasir POS</span>
            </a>

            <!-- Theme toggler -->
            <button
                type="button"
                class="w-8 h-8 rounded-md flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 border border-slate-200 dark:border-gray-800 transition-colors"
                aria-label="Toggle theme"
                @click="$store.theme.toggle()"
            >
                <x-svg.sun x-show="$store.theme.resolvedTheme === 'dark'" class="size-4 text-amber-500" />
                <x-svg.moon x-show="$store.theme.resolvedTheme !== 'dark'" class="size-4 text-slate-600" />
            </button>

            <!-- User Dropdown -->
            <x-header.user-dropdown />
        </div>
    </div>
</header>
