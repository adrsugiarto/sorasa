<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Profil & Pengaturan Usaha</h1>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Identitas usaha SORASA, nomor WhatsApp admin, dan info rekening nota pesanan</p>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session()->has('profile_success'))
        <div class="p-3 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between text-emerald-800 dark:text-emerald-300 text-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('profile_success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:hover:text-emerald-200 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if(session()->has('bank_success'))
        <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 flex items-center justify-between text-blue-900 dark:text-blue-300 text-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('bank_success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-blue-600 hover:text-blue-800 dark:hover:text-blue-200 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <div class="max-w-4xl space-y-5">
        <!-- Card 1: Identitas Bisnis & Dapur -->
        <div class="bg-white dark:bg-gray-900 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Identitas Bisnis & Dapur</h2>
                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Informasi profil kuliner yang tercetak pada kop nota dan kontak pesanan</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                    Profil Utama
                </span>
            </div>

            <form wire:submit="updateProfile" class="p-4 sm:p-5 space-y-3.5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Nama Usaha -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">
                            Nama Usaha <span class="text-rose-600">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="store_name"
                            placeholder="Contoh: SORASA Kitchen & Pastry"
                            class="w-full px-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium placeholder-slate-400"
                        >
                        @error('store_name') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nomor WhatsApp Usaha -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">
                            Nomor WhatsApp Usaha <span class="text-rose-600">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 font-mono text-xs">
                                📞
                            </span>
                            <input
                                type="text"
                                wire:model="store_phone"
                                placeholder="Contoh: 081223456789"
                                class="w-full pl-8 pr-3 py-1.5 text-xs font-mono bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-semibold placeholder-slate-400"
                            >
                        </div>
                        @error('store_phone') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Slogan / Tagline -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">
                            Slogan / Tagline Usaha
                        </label>
                        <input
                            type="text"
                            wire:model="store_tagline"
                            placeholder="Contoh: Spesialis Pre-Order Masakan Nusantara & Dessert Homemade"
                            class="w-full px-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white placeholder-slate-400"
                        >
                        @error('store_tagline') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email Usaha -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">
                            Email Usaha (Opsional)
                        </label>
                        <input
                            type="email"
                            wire:model="store_email"
                            placeholder="Contoh: halo@sorasa.test"
                            class="w-full px-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white placeholder-slate-400"
                        >
                        @error('store_email') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Alamat Dapur -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">
                        Alamat Dapur <span class="text-rose-600">*</span>
                    </label>
                    <textarea
                        rows="2"
                        wire:model="store_address"
                        placeholder="Contoh: Jl. Cendrawasih No. 24, Sukajadi, Kota Bandung, Jawa Barat 40162"
                        class="w-full px-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white placeholder-slate-400 leading-relaxed"
                    ></textarea>
                    @error('store_address') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Catatan Khusus Nota Pesanan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">
                        Catatan Khusus Nota Pesanan / Dapur (Opsional)
                    </label>
                    <textarea
                        rows="2"
                        wire:model="kitchen_notes"
                        placeholder="Contoh: Harap konfirmasi jadwal kirim H-1 sebelum tanggal pengiriman PO."
                        class="w-full px-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white placeholder-slate-400 leading-relaxed"
                    ></textarea>
                    @error('kitchen_notes') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Footer Save Button -->
                <div class="pt-2 flex justify-end">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-md font-medium text-xs shadow-xs transition-all disabled:opacity-50 cursor-pointer"
                    >
                        <svg wire:loading wire:target="updateProfile" class="animate-spin w-3.5 h-3.5 text-white shrink-0 inline-block" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg wire:loading.remove wire:target="updateProfile" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span wire:loading.remove wire:target="updateProfile">Simpan Perubahan Identitas</span>
                        <span wire:loading wire:target="updateProfile">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Card 2: Informasi Rekening Pembayaran (Nota) -->
        <div class="bg-white dark:bg-gray-900 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Informasi Rekening Pembayaran (Nota)</h2>
                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Daftar rekening bank & e-wallet tujuan transfer pembayaran pesanan</p>
                </div>
                <button
                    type="button"
                    wire:click="openCreateBankModal"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-md font-medium text-xs shadow-xs transition-all self-start sm:self-auto cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    + Tambah Rekening
                </button>
            </div>

            <div class="p-4 sm:p-5 space-y-3">
                @if(empty($bankAccounts))
                    <div class="text-center py-8 px-4 border border-dashed border-slate-200 dark:border-gray-800 rounded-lg">
                        <div class="w-10 h-10 mx-auto rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-600 flex items-center justify-center mb-2.5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xs font-semibold text-slate-900 dark:text-white">Belum Ada Rekening Pembayaran</h3>
                        <p class="text-[11px] text-slate-500 dark:text-gray-400 max-w-sm mx-auto mt-0.5">Tambahkan setidaknya satu rekening bank atau e-wallet agar pelanggan dapat melakukan transfer pembayaran.</p>
                        <button
                            type="button"
                            wire:click="openCreateBankModal"
                            class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium text-xs cursor-pointer"
                        >
                            + Tambah Rekening Pertama
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-2.5">
                        @foreach($bankAccounts as $index => $acc)
                            <div class="p-3.5 rounded-lg border {{ !empty($acc['is_primary']) ? 'bg-blue-50/40 dark:bg-blue-950/20 border-blue-200 dark:border-blue-800/80' : 'bg-slate-50/70 dark:bg-gray-800/50 border-slate-200 dark:border-gray-800' }} flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 transition-all">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-md {{ !empty($acc['is_primary']) ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-200 dark:bg-gray-700 text-slate-700 dark:text-gray-300' }} flex items-center justify-center shrink-0 font-bold text-xs">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="text-xs font-semibold text-slate-900 dark:text-white">{{ $acc['bank_name'] }}</h4>
                                            @if(!empty($acc['is_primary']))
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-medium bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                    ★ Rekening Utama
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-0.5 flex items-center gap-2">
                                            <span class="font-mono text-xs font-bold text-slate-900 dark:text-white tabular-nums">{{ $acc['account_number'] }}</span>
                                            <button
                                                type="button"
                                                x-data
                                                @click="navigator.clipboard.writeText('{{ $acc['account_number'] }}'); alert('Nomor rekening disalin!')"
                                                class="text-[10px] text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 underline cursor-pointer"
                                                title="Salin Nomor Rekening"
                                            >
                                                Salin
                                            </button>
                                        </div>
                                        <p class="text-[11px] text-slate-500 dark:text-gray-400">
                                            a.n. <span class="font-medium text-slate-700 dark:text-gray-300">{{ $acc['account_name'] }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 self-end sm:self-center shrink-0">
                                    @if(empty($acc['is_primary']))
                                        <button
                                            type="button"
                                            wire:click="setPrimaryBank('{{ $acc['id'] }}')"
                                            class="px-2 py-1 text-[11px] font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-100 dark:hover:bg-blue-900/60 border border-blue-200 dark:border-blue-800 rounded-md transition-all cursor-pointer"
                                            title="Jadikan rekening ini sebagai pilihan utama pada nota"
                                        >
                                            Set Utama
                                        </button>
                                    @endif

                                    <button
                                        type="button"
                                        wire:click="openEditBankModal('{{ $acc['id'] }}')"
                                        class="p-1 text-slate-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-gray-800 rounded-md border border-transparent hover:border-slate-200 dark:hover:border-gray-700 transition-all cursor-pointer"
                                        title="Edit Rekening"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="confirmDeleteBank('{{ $acc['id'] }}')"
                                        class="p-1 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-white dark:hover:bg-gray-800 rounded-md border border-transparent hover:border-slate-200 dark:hover:border-gray-700 transition-all cursor-pointer"
                                        title="Hapus Rekening"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-3 p-2.5 rounded-md bg-slate-50 dark:bg-gray-800/40 border border-slate-200 dark:border-gray-800 text-[11px] text-slate-500 dark:text-gray-400 flex items-start gap-2">
                    <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Seluruh rekening di atas akan otomatis tercantum pada <strong>Nota Cetak Pesanan (Invoice)</strong> dan rincian konfirmasi tagihan WhatsApp agar memudahkan pelanggan memilih jalur transfer resmi.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah / Edit Rekening -->
    <div
        x-data="{ show: @entangle('showBankModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
        style="display: none;"
        @keydown.escape.window="show = false; $wire.set('showBankModal', false)"
    >
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs transition-opacity"
            @click="show = false; $wire.set('showBankModal', false)"
        ></div>

        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            class="relative bg-white dark:bg-gray-900 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xl max-w-lg w-full overflow-hidden z-10"
        >
            <div class="px-4 py-3 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                        {{ $isEditingBank ? 'Edit Rekening Pembayaran' : 'Tambah Rekening Pembayaran Baru' }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Lengkapi informasi bank atau dompet digital untuk nota pesanan</p>
                </div>
                <button
                    type="button"
                    @click="show = false; $wire.set('showBankModal', false)"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-md cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="saveBankAccount" class="p-4 sm:p-5 space-y-3.5">
                <!-- Quick Bank Preset Pills -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 dark:text-gray-400 mb-1">
                        Pilihan Bank Cepat:
                    </label>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach(['BCA', 'Mandiri', 'BRI', 'BNI', 'BSI', 'Bank Jago', 'SeaBank', 'QRIS', 'GoPay', 'DANA', 'ShopeePay'] as $bankPreset)
                            <button
                                type="button"
                                wire:click="selectBankPreset('{{ $bankPreset }}')"
                                class="px-2.5 py-1 text-[11px] font-medium rounded-md border transition-all cursor-pointer {{ $bank_name === $bankPreset ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-50 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border-slate-200 dark:border-gray-700 hover:border-blue-500' }}"
                            >
                                {{ $bankPreset }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Nama Bank -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">
                        Nama Bank / Penyedia E-Wallet <span class="text-rose-600">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="bank_name"
                        placeholder="Contoh: BCA (Bank Central Asia) atau QRIS"
                        class="w-full px-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                    >
                    @error('bank_name') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nomor Rekening -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">
                        Nomor Rekening / Nomor Akun / No. HP <span class="text-rose-600">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="account_number"
                        placeholder="Contoh: 8420198273 atau 081223456789"
                        class="w-full px-3 py-1.5 text-xs font-mono font-bold bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white tabular-nums"
                    >
                    @error('account_number') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Atas Nama -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-gray-300 mb-1">
                        Atas Nama Pemilik Rekening <span class="text-rose-600">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="account_name"
                        placeholder="Contoh: SORASA KULINER NUSANTARA"
                        class="w-full px-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                    >
                    @error('account_name') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Jadikan Rekening Utama -->
                <div class="pt-0.5">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            wire:model="is_primary"
                            class="w-3.5 h-3.5 text-blue-600 rounded border-slate-300 focus:ring-blue-500"
                        >
                        <span class="text-xs font-medium text-slate-700 dark:text-gray-300">
                            Jadikan sebagai <strong>Rekening Utama</strong> (Prioritas Utama pada Nota)
                        </span>
                    </label>
                </div>

                <div class="pt-3 border-t border-slate-100 dark:border-gray-800 flex items-center justify-end gap-2">
                    <button
                        type="button"
                        @click="show = false; $wire.set('showBankModal', false)"
                        class="px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-800 rounded-md transition-all cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-md font-medium text-xs shadow-xs transition-all disabled:opacity-50 cursor-pointer"
                    >
                        <svg wire:loading wire:target="saveBankAccount" class="animate-spin w-3 h-3 text-white shrink-0 inline-block" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="saveBankAccount">
                            {{ $isEditingBank ? 'Perbarui Rekening' : 'Simpan Rekening' }}
                        </span>
                        <span wire:loading wire:target="saveBankAccount">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Rekening -->
    <div
        x-data="{ show: @entangle('showDeleteBankModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
        style="display: none;"
        @keydown.escape.window="show = false; $wire.set('showDeleteBankModal', false)"
    >
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs transition-opacity"
            @click="show = false; $wire.set('showDeleteBankModal', false)"
        ></div>

        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            class="relative bg-white dark:bg-gray-900 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xl max-w-sm w-full p-4 text-center space-y-3.5 z-10"
        >
            <div class="w-10 h-10 mx-auto rounded-full bg-rose-100 dark:bg-rose-950/50 text-rose-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Hapus Rekening Pembayaran?</h3>
                <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">
                    Rekening <strong class="text-slate-800 dark:text-gray-200">{{ $deletingBankName }}</strong> tidak akan ditampilkan lagi pada nota pesanan.
                </p>
            </div>
            <div class="flex items-center justify-center gap-2 pt-1">
                <button
                    type="button"
                    @click="show = false; $wire.set('showDeleteBankModal', false)"
                    class="px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-800 rounded-md cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    wire:click="deleteBank"
                    class="px-3.5 py-1.5 text-xs font-medium text-white bg-rose-600 hover:bg-rose-700 active:scale-95 rounded-md shadow-xs transition-all cursor-pointer"
                >
                    Ya, Hapus Rekening
                </button>
            </div>
        </div>
    </div>
</div>
