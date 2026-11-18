@php
    $user = auth()->user();
@endphp
<x-ui.dropdown-menu>
    <x-ui.dropdown-menu.trigger class="dropdown-toggle flex items-center text-gray-700 dark:text-gray-400 cursor-pointer">
        <span class="me-3 h-10 w-10 overflow-hidden rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-200 font-bold flex items-center justify-center text-sm border border-amber-300 dark:border-amber-700">
            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
        </span>

        <div class="hidden sm:block text-left me-2">
            <span class="block text-xs font-bold text-gray-900 dark:text-white">{{ $user->name ?? 'Pengguna' }}</span>
            <span class="block text-[10px] font-semibold uppercase text-amber-600 dark:text-amber-400">{{ $user->role ?? 'admin' }}</span>
        </div>

        <x-svg.chevron-down class="size-4 text-gray-500 duration-200 dark:text-gray-400" x-bind:class="open ? 'rotate-180' : 'rotate-0'" />
    </x-ui.dropdown-menu.trigger>

    <x-ui.dropdown-menu.content align="end" :side-offset="12" class="w-64 p-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-xl rounded-2xl">
        <div class="px-3 py-2 border-b border-gray-100 dark:border-gray-800 mb-2">
            <span class="block text-sm font-bold text-gray-900 dark:text-white">{{ $user->name ?? 'Pengguna' }}</span>
            <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">{{ $user->email ?? '' }}</span>
            <span class="mt-1.5 inline-block px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-amber-50 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                {{ $user->role === 'owner' ? '👑 Pemilik Usaha (Owner)' : 'Admin Operasional' }}
            </span>
        </div>

        <x-ui.dropdown-menu.item to="{{ route('admin.settings') }}">
            <x-svg.gear class="size-5 me-2 text-gray-500" />
            Profil & Pengaturan Usaha
        </x-ui.dropdown-menu.item>

        @if($user && $user->isOwner())
            <x-ui.dropdown-menu.item to="{{ route('admin.users') }}">
                <x-svg.user-circle class="size-5 me-2 text-amber-600" />
                Kelola Akun Admin
            </x-ui.dropdown-menu.item>
        @endif

        <x-ui.dropdown-menu.separator />

        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>
        <x-ui.dropdown-menu.item variant="destructive" x-on:click.capture="document.getElementById('logout-form').submit()">
            <x-svg.log-out class="size-5 me-2" />
            Keluar (Sign Out)
        </x-ui.dropdown-menu.item>
    </x-ui.dropdown-menu.content>
</x-ui.dropdown-menu>
