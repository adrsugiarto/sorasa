@php
    use App\Helpers\MenuHelper;
    $menuGroups = MenuHelper::getMenuGroups();
    $currentPath = request()->path();
@endphp

<aside id="sidebar"
    class="fixed flex flex-col mt-0 top-0 px-5 start-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out max-xl:duration-200 max-xl:w-[290px] z-50 max-xl:z-101 ltr:border-r rtl:border-l border-gray-200 w-[90px] [.sidebar-expanded_&]:w-[290px] [.sidebar-expanded_&]:min-w-[290px] print:hidden no-print"
    x-data="{
        openSubmenus: {},
        init() {
            this.initializeActiveMenus();
        },
        initializeActiveMenus() {
            const currentPath = '{{ $currentPath }}';
            @foreach ($menuGroups as $groupIndex => $menuGroup)
                @foreach ($menuGroup['items'] as $itemIndex => $item)
                    @if (isset($item['subItems']))
                        @foreach ($item['subItems'] as $subItem)
                            if (currentPath === '{{ ltrim($subItem['path'], '/') }}' || window.location.pathname === '{{ $subItem['path'] }}') {
                                this.openSubmenus['{{ $groupIndex }}-{{ $itemIndex }}'] = true;
                            }
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        },
        toggleSubmenu(groupIndex, itemIndex) {
            const key = groupIndex + '-' + itemIndex;
            const newState = !this.openSubmenus[key];
            if (newState) {
                this.openSubmenus = {};
            }
            this.openSubmenus[key] = newState;
        },
        isSubmenuOpen(groupIndex, itemIndex) {
            const key = groupIndex + '-' + itemIndex;
            return this.openSubmenus[key] || false;
        },
        isActive(path) {
            return window.location.pathname === path || '{{ $currentPath }}' === path.replace(/^\//, '') || window.location.pathname.startsWith(path);
        }
    }"
    :class="{
        'w-[290px]': !$store.sidebar || $store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen,
        'w-[90px]': $store.sidebar && !$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen,
        'translate-x-0': $store.sidebar && $store.sidebar.isMobileOpen,
        'max-xl:-translate-x-full max-xl:rtl:translate-x-full': !$store.sidebar || !$store.sidebar.isMobileOpen
    }"
    @mouseenter="if ($store.sidebar && !$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="if ($store.sidebar) $store.sidebar.setHovered(false)">
    <!-- Logo Section -->
    <div class="py-5 flex items-center gap-2 border-b border-slate-100 dark:border-gray-800" :class="($store.sidebar && !$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'justify-center' : 'justify-between'">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-xs shrink-0">
                S
            </div>
            <div class="hidden [.sidebar-expanded_&]:block transition-opacity duration-200">
                <span class="text-base font-bold tracking-tight text-slate-900 dark:text-white">SORASA</span>
                <span class="block text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Point of Sale (POS)</span>
            </div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar pt-4 flex-1">
        <nav class="mb-4">
            <div class="flex flex-col gap-4">
                @foreach ($menuGroups as $groupIndex => $menuGroup)
                    <div>
                        <!-- Menu Group Title -->
                        <h2 class="mb-2 px-3 text-xs font-bold uppercase tracking-wider flex leading-5 text-slate-400 dark:text-slate-500"
                            :class="($store.sidebar && !$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                            'lg:justify-center' : 'justify-start'">
                            <template
                                x-if="!$store.sidebar || $store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                                <span>{{ $menuGroup['title'] }}</span>
                            </template>
                            <template x-if="$store.sidebar && !$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-gray-700"></span>
                            </template>
                        </h2>

                        <!-- Menu Items -->
                        <ul class="flex flex-col gap-1">
                            @foreach ($menuGroup['items'] as $itemIndex => $item)
                                <li>
                                    <!-- Simple Menu Item -->
                                    <a href="{{ $item['path'] }}" class="menu-item group"
                                        :class="[
                                            isActive('{{ $item['path'] }}') ? 'menu-item-active' :
                                            'menu-item-inactive',
                                            ($store.sidebar && !$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                            'xl:justify-center' :
                                            'justify-start'
                                        ]">

                                        <!-- Icon -->
                                        <span
                                            :class="isActive('{{ $item['path'] }}') ? 'menu-item-icon-active' :
                                                'menu-item-icon-inactive'">
                                            {!! MenuHelper::getIconSvg($item['icon']) !!}
                                        </span>

                                        <!-- Text -->
                                        <span
                                            x-show="!$store.sidebar || $store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                            class="menu-item-text flex items-center gap-2 font-medium">
                                            {{ $item['name'] }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </nav>

        <!-- Sidebar Widget -->
        <div x-data x-show="!$store.sidebar || $store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-transition class="mt-auto">
            @include('layouts.sidebar-widget')
        </div>
    </div>
</aside>
