@php
    $activeOpenPo = \App\Models\OpenPo::query()->where('status', 'open')->latest()->first();
    $user = auth()->user();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SORASA' }} — Manajemen Open PO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="h-full antialiased font-sans text-slate-800 bg-slate-50 flex"
>
    <!-- Mobile Sidebar Backdrop -->
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 lg:hidden"
        x-on:click="sidebarOpen = false"
        style="display: none;"
    ></div>

    <!-- Mobile Drawer Sidebar -->
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 flex flex-col z-50 w-72 bg-white border-r border-slate-200 lg:hidden shadow-xl"
        style="display: none;"
    >
        <!-- Mobile Sidebar Header -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-base shadow-xs">
                    S
                </div>
                <div>
                    <h2 class="text-sm font-bold tracking-tight text-slate-900">SORASA</h2>
                    <p class="text-[10px] text-slate-500 font-medium">Kitchen & Pastry</p>
                </div>
            </div>
            <button
                type="button"
                x-on:click="sidebarOpen = false"
                class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation Items -->
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-6">
            @include('layouts.partials.admin-nav')
        </div>

        <!-- Mobile User Footer -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 font-bold text-xs flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-900 truncate">{{ $user->name ?? 'Pengguna' }}</p>
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider font-medium">{{ $user->role ?? 'admin' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Keluar">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Sticky Sidebar -->
    <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:shrink-0 bg-white border-r border-slate-200/90 h-screen sticky top-0">
        <!-- Sidebar Brand -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-base shadow-xs">
                    S
                </div>
                <div>
                    <h2 class="text-sm font-bold tracking-tight text-slate-900 leading-none">SORASA</h2>
                    <p class="text-[10px] text-slate-400 font-medium tracking-tight mt-0.5">Kitchen & Pastry POS</p>
                </div>
            </a>
            <span class="px-2 py-0.5 rounded-md bg-amber-50 border border-amber-200/60 text-[10px] font-semibold text-amber-700">
                v1.0
            </span>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto px-3.5 py-4 space-y-6">
            @include('layouts.partials.admin-nav')
        </div>

        <!-- Desktop User Profile & Logout -->
        <div class="p-3.5 border-t border-slate-100 bg-slate-50/60">
            <div class="flex items-center justify-between gap-2 p-1.5 rounded-xl hover:bg-white border border-transparent hover:border-slate-200/80 transition-all">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-amber-600 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                        {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-900 truncate">{{ $user->name ?? 'Pengguna' }}</p>
                        <span class="inline-block text-[10px] px-1.5 py-0.2 rounded bg-slate-200/70 text-slate-600 font-medium uppercase tracking-wider">
                            {{ $user->role ?? 'admin' }}
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                        title="Keluar dari akun"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Topbar Header -->
        <header class="h-16 bg-white border-b border-slate-200/90 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <!-- Mobile Hamburger Toggle -->
                <button
                    type="button"
                    x-on:click="sidebarOpen = true"
                    class="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 lg:hidden"
                    title="Buka menu navigasi"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Active Open PO Status Pill -->
                <div class="flex items-center gap-2">
                    @if($activeOpenPo)
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50/90 border border-amber-200/80 text-xs text-amber-900 font-medium shadow-2xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="font-semibold text-amber-950">{{ $activeOpenPo->nama_po }}</span>
                            <span class="text-amber-700/80 hidden sm:inline">• Kirim: {{ $activeOpenPo->tanggal_pengiriman->format('d M Y') }}</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-xs text-slate-600 font-medium">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            <span>Tidak ada Open PO aktif saat ini</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Topbar Actions -->
            <div class="flex items-center gap-3">
                <!-- Quick POS Button -->
                <a
                    href="{{ route('admin.sales.pos') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold shadow-xs transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Kasir POS</span>
                </a>
            </div>
        </header>

        <!-- Flash Notifications -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mx-4 sm:mx-6 lg:mx-8 mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium px-4 py-3 rounded-xl flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" x-on:click="show = false" class="text-emerald-600 hover:text-emerald-900">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="mx-4 sm:mx-6 lg:mx-8 mt-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium px-4 py-3 rounded-xl flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" x-on:click="show = false" class="text-rose-600 hover:text-rose-900">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        @endif

        <!-- Page Main View -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
