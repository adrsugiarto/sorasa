<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white">Kelola Akun Admin & Kredensial</h1>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Khusus Pemilik Usaha (Owner): Atur akun login, hak akses, dan kata sandi admin operasional dapur</p>
        </div>
        <button
            type="button"
            wire:click="openCreateModal"
            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium text-xs shadow-xs transition-colors shrink-0 cursor-pointer"
        >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Tambah Akun Baru
        </button>
    </div>

    <!-- KPI Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        <div class="bg-white dark:bg-gray-900 p-4 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">Pemilik Usaha (Owner)</p>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $totalOwner }} Akun</h3>
                <p class="text-[11px] text-slate-400 dark:text-gray-500 mt-0.5">Memiliki hak akses penuh sistem dan manajemen akun admin</p>
            </div>
            <div class="w-10 h-10 rounded-md bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-200/80 dark:border-blue-900/60">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 p-4 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-gray-400">Admin Operasional</p>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $totalAdmin }} Akun</h3>
                <p class="text-[11px] text-slate-400 dark:text-gray-500 mt-0.5">Akses kasir POS, order, belanja, resep, dan pengantaran</p>
            </div>
            <div class="w-10 h-10 rounded-md bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-gray-300 flex items-center justify-center border border-slate-200 dark:border-gray-700">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-gray-900 p-3 rounded-lg border border-slate-200 dark:border-gray-800 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 flex-1">
            <div class="relative flex-1 max-w-sm">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Cari nama atau email akun..."
                    class="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-800 dark:text-white placeholder-slate-400 font-medium"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select
                wire:model.live="roleFilter"
                class="px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-700 dark:text-gray-300 font-medium"
            >
                <option value="">Semua Peran (Role)</option>
                <option value="owner">Pemilik Usaha (Owner)</option>
                <option value="admin">Admin Operasional</option>
            </select>
        </div>

        @if($search || $roleFilter)
            <button
                type="button"
                wire:click="$set('search', ''); $set('roleFilter', '');"
                class="text-xs text-rose-600 hover:text-rose-700 font-medium hover:underline shrink-0 cursor-pointer"
            >
                Reset Filter
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-md text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-md text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Users Table -->
    <x-card noPadding="true">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-gray-800/80 text-slate-500 dark:text-gray-400 border-b border-slate-200 dark:border-gray-800 uppercase font-semibold text-[11px] tracking-wider">
                    <tr>
                        <th class="px-3.5 py-2.5">Pengguna & Akun</th>
                        <th class="px-3.5 py-2.5">Email / Login</th>
                        <th class="px-3.5 py-2.5">Hak Akses (Role)</th>
                        <th class="px-3.5 py-2.5">Terdaftar Sejak</th>
                        <th class="px-3.5 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800 text-slate-700 dark:text-gray-300 text-[13px] leading-5">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-gray-800/40 transition-colors">
                            <!-- Pengguna -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-md {{ $u->role === 'owner' ? 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-800' : 'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700' }} font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900 dark:text-white flex items-center gap-1.5">
                                            <span>{{ $u->name }}</span>
                                            @if($u->id === auth()->id())
                                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-slate-400 dark:text-gray-500">ID: #{{ $u->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap font-medium text-slate-800 dark:text-gray-200">
                                {{ $u->email }}
                            </td>

                            <!-- Role -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap">
                                @if($u->role === 'owner')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-800">
                                        <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Owner (Pemilik Usaha)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700">
                                        Admin Operasional
                                    </span>
                                @endif
                            </td>

                            <!-- Terdaftar -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap text-slate-500 dark:text-gray-400 text-xs tabular-nums">
                                {{ $u->created_at->format('d M Y') }}
                                <span class="text-[10px] text-slate-400 dark:text-gray-500 block">{{ $u->created_at->diffForHumans() }}</span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap text-right space-x-1">
                                <button
                                    type="button"
                                    wire:click="openEditModal({{ $u->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-50 dark:bg-gray-800 hover:bg-slate-100 dark:hover:bg-gray-750 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-700 rounded-md font-medium text-xs transition-colors cursor-pointer"
                                    title="Edit atau Reset Password"
                                >
                                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit / Sandi
                                </button>

                                @if($u->id !== auth()->id())
                                    <button
                                        type="button"
                                        wire:click="delete({{ $u->id }})"
                                        wire:confirm="Yakin ingin menghapus akun {{ $u->name }}? Pengguna ini tidak akan bisa login lagi."
                                        class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-md transition-colors cursor-pointer"
                                        title="Hapus Akun"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3.5 py-8 text-center text-slate-400 dark:text-gray-500">
                                Belum ada akun yang sesuai dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-3.5 py-2.5 border-t border-slate-200 dark:border-gray-800">
                {{ $users->links() }}
            </div>
        @endif
    </x-card>

    <!-- MODAL FORM USER / ADMIN -->
    <div
        x-data="{ show: @entangle('showModal').live }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
        style="display: none;"
        @keydown.escape.window="show = false; $wire.closeModal()"
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
            @click="show = false; $wire.closeModal()"
        ></div>

        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-3"
            class="relative bg-white dark:bg-gray-900 rounded-lg max-w-md w-full p-5 shadow-xl border border-slate-200 dark:border-gray-800 text-slate-800 dark:text-slate-200 z-10"
        >
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 dark:border-gray-800">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                        {{ $isEditing ? 'Edit Akun & Reset Kata Sandi' : 'Tambah Akun Admin Baru' }}
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Kelola kredensial login dan hak akses operasional</p>
                </div>
                <button
                    type="button"
                    @click="show = false; $wire.closeModal()"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 p-1 rounded-md hover:bg-slate-100 dark:hover:bg-gray-800 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="save" class="mt-4 space-y-3.5 text-xs">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Nama Lengkap *</label>
                    <input
                        wire:model="name"
                        type="text"
                        placeholder="Misal: Siti Aminah (Admin Dapur)"
                        class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                        required
                    >
                    @error('name') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email Login -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Alamat Email Login *</label>
                    <input
                        wire:model="email"
                        type="email"
                        placeholder="admin@sorasa.test"
                        class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                        required
                    >
                    @error('email') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Role Selection -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Peran / Hak Akses (Role) *</label>
                    <select
                        wire:model="role"
                        class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white font-medium"
                        required
                    >
                        <option value="admin">Admin Operasional (Kasir POS, Order, Belanja, Resep)</option>
                        <option value="owner">Pemilik Usaha / Owner (Akses Penuh & Kelola Admin)</option>
                    </select>
                    @error('role') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                        Kata Sandi {{ $isEditing ? '(Opsional: Isi hanya jika ingin mengganti)' : '*' }}
                    </label>
                    <input
                        wire:model="password"
                        type="password"
                        placeholder="{{ $isEditing ? 'Biarkan kosong jika tetap' : 'Minimal 6 karakter' }}"
                        class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                        {{ $isEditing ? '' : 'required' }}
                    >
                    @error('password') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-1">Ulangi Kata Sandi</label>
                    <input
                        wire:model="password_confirmation"
                        type="password"
                        placeholder="Ulangi kata sandi di atas"
                        class="w-full px-2.5 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md focus:bg-white dark:focus:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 text-slate-900 dark:text-white"
                        {{ $isEditing ? '' : 'required' }}
                    >
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-gray-800">
                    <button
                        type="button"
                        @click="show = false; $wire.closeModal()"
                        class="px-3 py-1.5 text-xs font-medium text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-md hover:bg-slate-50 dark:hover:bg-gray-750 cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-3.5 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-xs transition-colors disabled:opacity-50 cursor-pointer"
                    >
                        {{ $isEditing ? 'Simpan Perubahan' : 'Buat Akun' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
