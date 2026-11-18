import ApexCharts from 'apexcharts';
import registerUi from './ui';

window.ApexCharts = ApexCharts;

document.addEventListener('alpine:init', () => {
    if (window.Alpine) {
        registerUi(window.Alpine);
    }
});
