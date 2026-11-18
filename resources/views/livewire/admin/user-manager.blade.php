<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Kelola Akun Admin & Kredensial</h1>
            <p class="text-xs text-slate-500 mt-1">Khusus Pemilik Usaha (Owner): Atur akun login, hak akses, dan kata sandi admin operasional dapur</p>
        </div>
        <button
            type="button"
            wire:click="openCreateModal"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 text-white rounded-xl font-bold text-xs hover:bg-amber-700 shadow-2xs hover:shadow-xs transition-all active:scale-95 shrink-0"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Tambah Akun Baru
        </button>
    </div>

    <!-- KPI Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-amber-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Pemilik Usaha (Owner)</p>
                <h3 class="text-2xl font-black text-amber-950 mt-1 tabular-nums">{{ $totalOwner }} Akun</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Memiliki hak akses penuh sistem dan manajemen akun admin</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-indigo-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-indigo-700">Admin Operasional</p>
                <h3 class="text-2xl font-black text-indigo-950 mt-1 tabular-nums">{{ $totalAdmin }} Akun</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Akses kasir POS, order, belanja, resep, dan pengantaran</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center border border-indigo-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 flex-1">
            <div class="relative flex-1 max-w-sm">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Cari nama atau email akun..."
                    class="w-full pl-9 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-800 placeholder-slate-400"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select
                wire:model.live="roleFilter"
                class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 text-slate-700 font-medium"
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
                class="text-xs text-rose-600 font-medium hover:underline shrink-0"
            >
                Reset Filter
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs flex items-center justify-between">
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
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Pengguna & Akun</th>
                        <th class="px-5 py-3.5">Email / Login</th>
                        <th class="px-5 py-3.5">Hak Akses (Role)</th>
                        <th class="px-5 py-3.5">Terdaftar Sejak</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Pengguna -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl {{ $u->role === 'owner' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-indigo-100 text-indigo-800 border border-indigo-200' }} font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                            <span>{{ $u->name }}</span>
                                            @if($u->id === auth()->id())
                                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] text-slate-400">ID: #{{ $u->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-5 py-4 whitespace-nowrap font-medium text-slate-800">
                                {{ $u->email }}
                            </td>

                            <!-- Role -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($u->role === 'owner')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-extrabold bg-amber-50 text-amber-800 border border-amber-300">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Owner (Pemilik Usaha)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-800 border border-indigo-200">
                                        Admin Operasional
                                    </span>
                                @endif
                            </td>

                            <!-- Terdaftar -->
                            <td class="px-5 py-4 whitespace-nowrap text-slate-500 text-xs">
                                {{ $u->created_at->format('d M Y') }}
                                <span class="text-[10px] text-slate-400 block">{{ $u->created_at->diffForHumans() }}</span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 whitespace-nowrap text-right space-x-1.5">
                                <button
                                    type="button"
                                    wire:click="openEditModal({{ $u->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-amber-50 hover:text-amber-800 text-slate-700 rounded-lg font-bold text-xs transition-colors"
                                    title="Edit atau Reset Password"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit / Sandi
                                </button>

                                @if($u->id !== auth()->id())
                                    <button
                                        type="button"
                                        wire:click="delete({{ $u->id }})"
                                        wire:confirm="Yakin ingin menghapus akun {{ $u->name }}? Pengguna ini tidak akan bisa login lagi."
                                        class="p-1.5 text-slate-400 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors"
                                        title="Hapus Akun"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                                Belum ada akun yang sesuai dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-5 py-3 border-t border-slate-200">
                {{ $users->links() }}
            </div>
        @endif
    </x-card>

    <!-- MODAL FORM USER / ADMIN -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in-95">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">
                            {{ $isEditing ? 'Edit Akun & Reset Kata Sandi' : 'Tambah Akun Admin Baru' }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Kelola kredensial login dan hak akses operasional</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="mt-4 space-y-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input
                            wire:model="name"
                            type="text"
                            placeholder="Misal: Siti Aminah (Admin Dapur)"
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium"
                            required
                        >
                        @error('name') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Login -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email Login *</label>
                        <input
                            wire:model="email"
                            type="email"
                            placeholder="admin@sorasa.test"
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium"
                            required
                        >
                        @error('email') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Peran / Hak Akses (Role) *</label>
                        <select
                            wire:model="role"
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium"
                            required
                        >
                            <option value="admin">Admin Operasional (Kasir POS, Order, Belanja, Resep)</option>
                            <option value="owner">Pemilik Usaha / Owner (Akses Penuh & Kelola Admin)</option>
                        </select>
                        @error('role') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Kata Sandi {{ $isEditing ? '(Opsional: Isi hanya jika ingin mengganti)' : '*' }}
                        </label>
                        <input
                            wire:model="password"
                            type="password"
                            placeholder="{{ $isEditing ? 'Biarkan kosong jika tetap' : 'Minimal 6 karakter' }}"
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600"
                            {{ $isEditing ? '' : 'required' }}
                        >
                        @error('password') <span class="text-[11px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Ulangi Kata Sandi</label>
                        <input
                            wire:model="password_confirmation"
                            type="password"
                            placeholder="Ulangi kata sandi di atas"
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600"
                            {{ $isEditing ? '' : 'required' }}
                        >
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-2xs hover:shadow-xs transition-all disabled:opacity-50"
                        >
                            {{ $isEditing ? 'Simpan Perubahan' : 'Buat Akun' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
