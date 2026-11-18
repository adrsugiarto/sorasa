@php
    $user = auth()->user();
@endphp
<x-ui.dropdown-menu>
    <x-ui.dropdown-menu.trigger class="dropdown-toggle flex items-center text-slate-700 dark:text-gray-400 cursor-pointer">
        <span class="me-2.5 h-8 w-8 overflow-hidden rounded-md bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-bold flex items-center justify-center text-xs border border-blue-200 dark:border-blue-800">
            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
        </span>

        <div class="hidden sm:block text-left me-1.5">
            <span class="block text-xs font-semibold text-slate-900 dark:text-white">{{ $user->name ?? 'Pengguna' }}</span>
            <span class="block text-[10px] font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">{{ $user->role ?? 'admin' }}</span>
        </div>

        <x-svg.chevron-down class="size-3.5 text-slate-400 duration-200 dark:text-gray-400" x-bind:class="open ? 'rotate-180' : 'rotate-0'" />
    </x-ui.dropdown-menu.trigger>

    <x-ui.dropdown-menu.content align="end" :side-offset="8" class="w-60 p-2 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 shadow-lg rounded-lg">
        <div class="px-2.5 py-2 border-b border-slate-100 dark:border-gray-800 mb-1.5">
            <span class="block text-xs font-bold text-slate-900 dark:text-white">{{ $user->name ?? 'Pengguna' }}</span>
            <span class="mt-0.5 block text-[11px] text-slate-500 dark:text-gray-400">{{ $user->email ?? '' }}</span>
            <span class="mt-1.5 inline-block px-2 py-0.5 text-[10px] font-semibold uppercase rounded-md bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                {{ $user->role === 'owner' ? '👑 Pemilik Usaha (Owner)' : 'Admin Operasional' }}
            </span>
        </div>

        <x-ui.dropdown-menu.item to="{{ route('admin.settings') }}">
            <x-svg.gear class="size-4 me-2 text-slate-400" />
            <span class="text-xs">Profil & Pengaturan Usaha</span>
        </x-ui.dropdown-menu.item>

        @if($user && $user->isOwner())
            <x-ui.dropdown-menu.item to="{{ route('admin.users') }}">
                <x-svg.user-circle class="size-4 me-2 text-blue-600" />
                <span class="text-xs">Kelola Akun Admin</span>
            </x-ui.dropdown-menu.item>
        @endif

        <x-ui.dropdown-menu.separator />

        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>
        <x-ui.dropdown-menu.item variant="destructive" x-on:click.capture="document.getElementById('logout-form').submit()">
            <x-svg.log-out class="size-4 me-2" />
            <span class="text-xs">Keluar (Sign Out)</span>
        </x-ui.dropdown-menu.item>
    </x-ui.dropdown-menu.content>
</x-ui.dropdown-menu>
