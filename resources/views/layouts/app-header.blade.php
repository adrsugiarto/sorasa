@php
    $activePo = \App\Models\OpenPo::where('status', 'open')->latest('id')->first();
@endphp

<header
    class="sticky top-0 z-40 flex w-full shrink-0 border-b border-gray-200 bg-white/95 backdrop-blur-md dark:border-gray-800 dark:bg-gray-900/95"
    x-data="{
        isApplicationMenuOpen: false,
        toggleApplicationMenu() {
            this.isApplicationMenuOpen = !this.isApplicationMenuOpen;
        }
    }">
    <div class="flex grow items-center justify-between px-4 py-3 sm:px-6">
        <!-- Left side content -->
        <div class="flex items-center gap-3">
            <!-- Desktop sidebar toggle (xl and up) -->
            <button
                type="button"
                class="hidden xl:inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-500 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 transition-colors"
                aria-label="Toggle Sidebar"
                @click.stop="$store.sidebar.toggleExpanded()"
            >
                <x-svg.menu-two class="size-5" />
            </button>

            <!-- Mobile sidebar toggle (below xl) -->
            <button
                type="button"
                class="xl:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-500 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 transition-colors"
                aria-label="Toggle Mobile Sidebar"
                @click.stop="$store.sidebar.toggleMobileOpen()"
            >
                <x-svg.menu-two class="size-5" />
            </button>

            <!-- Active Open PO Status Pill -->
            @if($activePo)
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-800 text-xs text-amber-900 dark:text-amber-200 font-medium">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="font-bold">{{ $activePo->nama_po }}</span>
                    <span class="text-amber-600 dark:text-amber-400">• Kirim: {{ $activePo->tanggal_pengiriman->format('d M Y') }}</span>
                </div>
            @endif
        </div>

        <!-- Right side content -->
        <div class="flex items-center gap-2.5 sm:gap-4">
            <!-- Quick Link POS -->
            <a
                href="{{ route('admin.sales.pos') }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-2xs hover:shadow-xs transition-all active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Kasir POS</span>
            </a>

            <!-- Theme toggler -->
            <button
                type="button"
                class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 border border-gray-200 dark:border-gray-800 transition-colors"
                aria-label="Toggle theme"
                @click="$store.theme.toggle()"
            >
                <x-svg.sun x-show="$store.theme.resolvedTheme === 'dark'" class="size-5 text-amber-400" />
                <x-svg.moon x-show="$store.theme.resolvedTheme !== 'dark'" class="size-5" />
            </button>

            <!-- User Dropdown -->
            <x-header.user-dropdown />
        </div>
    </div>
</header>
