import ApexCharts from 'apexcharts';
import registerUi from './ui';

window.ApexCharts = ApexCharts;

function initGlobalAlpineStores(Alpine) {
    if (!Alpine) return;

    registerUi(Alpine);

    if (!Alpine.store('theme')) {
        Alpine.store('theme', {
            init() {
                const savedTheme = localStorage.getItem('theme');
                this.theme = savedTheme === 'dark' ? 'dark' : 'light';
                this.updateTheme();
            },
            theme: 'light',
            resolvedTheme: 'light',
            set(value) {
                value = value === 'dark' ? 'dark' : 'light';
                this.theme = value;
                localStorage.setItem('theme', value);
                this.updateTheme();
                window.dispatchEvent(new CustomEvent('theme-changed', { detail: value }));
            },
            toggle() {
                this.set(this.resolvedTheme === 'dark' ? 'light' : 'dark');
            },
            updateTheme() {
                const html = document.documentElement;
                const isDark = this.theme === 'dark';
                if (isDark) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
                this.resolvedTheme = isDark ? 'dark' : 'light';
                html.setAttribute('data-color-scheme', this.resolvedTheme);
                html.dataset['theme'] = this.resolvedTheme;
                html.style.colorScheme = this.resolvedTheme;
            }
        });
    }

    if (!Alpine.store('sidebar')) {
        Alpine.store('sidebar', {
            isExpanded: true,
            isMobileOpen: false,
            isHovered: false,

            init() {
                const savedState = localStorage.getItem('sidebarExpanded');
                if (window.innerWidth >= 1280) {
                    this.isExpanded = savedState === null ? true : savedState === 'true';
                } else {
                    this.isExpanded = false;
                }
                this.isMobileOpen = false;

                window.addEventListener('resize', () => {
                    this.handleResize();
                });
            },

            handleResize() {
                if (window.innerWidth < 1280) {
                    if (this.isMobileOpen) {
                        this.isMobileOpen = false;
                    }
                } else {
                    this.isMobileOpen = false;
                    const savedState = localStorage.getItem('sidebarExpanded');
                    this.isExpanded = savedState === null ? true : savedState === 'true';
                }
            },

            toggleExpanded() {
                this.isExpanded = !this.isExpanded;
                this.isMobileOpen = false;
                if (window.innerWidth >= 1280) {
                    localStorage.setItem('sidebarExpanded', this.isExpanded);
                }
            },

            toggleMobileOpen() {
                this.isMobileOpen = !this.isMobileOpen;
            },

            setMobileOpen(val) {
                this.isMobileOpen = val;
            },

            setHovered(val) {
                if (window.innerWidth >= 1280 && !this.isExpanded) {
                    this.isHovered = val;
                }
            }
        });
    }
}

if (window.Alpine) {
    initGlobalAlpineStores(window.Alpine);
} else {
    document.addEventListener('alpine:init', () => {
        if (window.Alpine) {
            initGlobalAlpineStores(window.Alpine);
        }
    });
}

document.addEventListener('livewire:navigated', () => {
    if (window.Alpine) {
        initGlobalAlpineStores(window.Alpine);
    }
});
