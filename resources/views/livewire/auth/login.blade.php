<div class="px-4 sm:px-0">
    <!-- Brand Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-600 text-white shadow-xs mb-3">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">SORASA</h1>
        <p class="text-xs text-slate-500 font-medium mt-1">Sistem Manajemen Operasional & POS Open PO Makanan</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white py-8 px-6 sm:px-8 border border-slate-200/90 rounded-2xl shadow-xs">
        <form wire:submit="login" class="space-y-5">
            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Email
                </label>
                <input
                    wire:model="email"
                    type="email"
                    id="email"
                    autocomplete="email"
                    required
                    placeholder="nama@sorasa.test"
                    class="w-full px-3.5 py-2.5 text-sm bg-slate-50/50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 transition-all text-slate-900 placeholder-slate-400"
                >
                @error('email')
                    <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Kata Sandi
                    </label>
                    <button
                        type="button"
                        x-on:click="show = !show"
                        class="text-xs text-slate-500 hover:text-slate-800 transition-colors"
                        tabindex="-1"
                    >
                        <span x-show="!show">Tampilkan</span>
                        <span x-show="show" style="display: none;">Sembunyikan</span>
                    </button>
                </div>
                <input
                    wire:model="password"
                    :type="show ? 'text' : 'password'"
                    id="password"
                    autocomplete="current-password"
                    required
                    placeholder="••••••••"
                    class="w-full px-3.5 py-2.5 text-sm bg-slate-50/50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 transition-all text-slate-900 placeholder-slate-400"
                >
                @error('password')
                    <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        wire:model="remember"
                        type="checkbox"
                        class="w-4 h-4 rounded text-amber-600 border-slate-300 focus:ring-amber-500"
                    >
                    <span class="text-xs text-slate-600 font-medium">Ingat perangkat ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full py-2.5 px-4 bg-amber-600 hover:bg-amber-700 active:bg-amber-800 text-white text-sm font-semibold rounded-xl shadow-xs transition-colors flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                >
                    <span wire:loading.remove>Masuk ke Aplikasi</span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memverifikasi...
                    </span>
                </button>
            </div>
        </form>

        <!-- Demo Accounts Helper -->
        <div class="mt-6 pt-5 border-t border-slate-100">
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider text-center mb-3">
                Akun Percobaan Cepat (Klik untuk Isi)
            </p>
            <div class="grid grid-cols-2 gap-2">
                <button
                    type="button"
                    wire:click="fillCredentials('owner')"
                    class="text-left px-3 py-2 rounded-lg border border-slate-200 bg-slate-50/60 hover:bg-slate-100 hover:border-slate-300 transition-colors"
                >
                    <div class="text-xs font-semibold text-slate-800">Owner Sorasa</div>
                    <div class="text-[11px] text-slate-500 truncate">owner@sorasa.test</div>
                </button>
                <button
                    type="button"
                    wire:click="fillCredentials('admin')"
                    class="text-left px-3 py-2 rounded-lg border border-slate-200 bg-slate-50/60 hover:bg-slate-100 hover:border-slate-300 transition-colors"
                >
                    <div class="text-xs font-semibold text-slate-800">Admin Dapur</div>
                    <div class="text-[11px] text-slate-500 truncate">admin@sorasa.test</div>
                </button>
            </div>
            <p class="text-[10px] text-slate-400 text-center mt-2">Password default: <code class="bg-slate-100 px-1 py-0.5 rounded text-slate-600 font-mono">password</code></p>
        </div>
    </div>
</div>
