<?php

namespace App\Livewire\Admin;

use App\Models\OpenPo;
use App\Models\OtherExpense;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Dashboard Operasional — SORASA')]
class Dashboard extends Component
{
    public string $selectedOpenPoId = '';

    public string $period = 'all'; // 'all', 'this_month', 'last_month', 'custom'

    public ?string $startDate = null;

    public ?string $endDate = null;

    /**
     * @var array<string, mixed>
     */
    public array $chartFinancial = [];

    /**
     * @var array<string, mixed>
     */
    public array $chartTrend = [];

    public function updatedPeriod(): void
    {
        if ($this->period === 'this_month') {
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($this->period === 'last_month') {
            $this->startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
            $this->endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        } elseif ($this->period === 'all') {
            $this->startDate = null;
            $this->endDate = null;
        }
    }

    public function resetFilters(): void
    {
        $this->selectedOpenPoId = '';
        $this->period = 'all';
        $this->startDate = null;
        $this->endDate = null;
    }

    /**
     * Render dashboard view with reactive metrics, chart data, and top products.
     */
    public function render(): View
    {
        $openPos = OpenPo::query()->latest('id')->get(['id', 'nama_po', 'kode_po', 'status']);
        $activeOpenPo = OpenPo::query()->where('status', 'open')->latest('id')->first();

        // 1. Sales Query
        $salesQuery = Sale::query()->where('status_order', '!=', 'dibatalkan');
        if ($this->selectedOpenPoId) {
            $salesQuery->where('open_po_id', $this->selectedOpenPoId);
        }
        if ($this->startDate) {
            $salesQuery->whereDate('tanggal_pemesanan', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $salesQuery->whereDate('tanggal_pemesanan', '<=', $this->endDate);
        }

        $filteredSales = $salesQuery->with(['customer', 'items', 'payments'])->get();
        $totalOmzet = (float) $filteredSales->sum('total_tagihan');
        $totalOrders = $filteredSales->count();

        // 2. Purchases Query
        $purchasesQuery = Purchase::query();
        if ($this->selectedOpenPoId) {
            $purchasesQuery->where('open_po_id', $this->selectedOpenPoId);
        }
        if ($this->startDate) {
            $purchasesQuery->whereDate('tanggal_pembelian', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $purchasesQuery->whereDate('tanggal_pembelian', '<=', $this->endDate);
        }
        $totalPembelian = (float) $purchasesQuery->sum('total_pembelian');

        // 3. Other Expenses Query
        $expensesQuery = OtherExpense::query();
        if ($this->selectedOpenPoId) {
            $expensesQuery->where('open_po_id', $this->selectedOpenPoId);
        }
        if ($this->startDate) {
            $expensesQuery->whereDate('tanggal_pengeluaran', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $expensesQuery->whereDate('tanggal_pengeluaran', '<=', $this->endDate);
        }
        $totalBiayaLain = (float) $expensesQuery->sum('nominal');

        // 4. Surplus Usaha Sederhana: Omzet - Belanja Bahan - Biaya Lain
        $surplus = $totalOmzet - $totalPembelian - $totalBiayaLain;
        $surplusMargin = $totalOmzet > 0 ? round(($surplus / $totalOmzet) * 100, 1) : 0;

        // 5. Arus Kas & Piutang Belum Lunas
        $totalPaid = 0;
        $totalPiutang = 0;
        foreach ($filteredSales as $sale) {
            $paid = (float) $sale->payments->where('status_verifikasi', 'verified')->sum('nominal_bayar');
            $totalPaid += $paid;
            $rem = max(0.0, (float) $sale->total_tagihan - $paid);
            $totalPiutang += $rem;
        }

        // 6. Top Selling Products
        $saleIds = $filteredSales->pluck('id');
        $topProducts = SaleItem::query()
            ->whereIn('sale_id', $saleIds)
            ->select(
                'nama_produk_snapshot',
                DB::raw('SUM(jumlah_paket) as total_qty'),
                DB::raw('SUM(subtotal) as total_omzet')
            )
            ->groupBy('nama_produk_snapshot')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 7. Order Status Breakdown
        $ordersByStatus = [
            'menunggu_konfirmasi' => $filteredSales->where('status_order', 'menunggu_konfirmasi')->count(),
            'diproses' => $filteredSales->where('status_order', 'diproses')->count(),
            'siap_kirim' => $filteredSales->where('status_order', 'siap_kirim')->count(),
            'selesai' => $filteredSales->where('status_order', 'selesai')->count(),
        ];

        // 8. Financial Comparison Chart Data
        $chartFinancial = [
            'categories' => ['Penjualan (Omzet)', 'Pembelian Bahan', 'Biaya Operasional', 'Surplus Usaha'],
            'data' => [
                $totalOmzet,
                $totalPembelian,
                $totalBiayaLain,
                $surplus,
            ],
            'colors' => [
                '#d97706', // amber-600
                '#e11d48', // rose-600
                '#8b5cf6', // purple-500
                $surplus >= 0 ? '#059669' : '#dc2626', // emerald-600 or red-600
            ],
        ];

        // 9. Trend Chart Data (Per Batch PO or Daily)
        if ($this->selectedOpenPoId) {
            // Trend Daily inside the selected PO
            $trendData = Sale::query()
                ->where('open_po_id', $this->selectedOpenPoId)
                ->where('status_order', '!=', 'dibatalkan')
                ->select(DB::raw('tanggal_pemesanan, SUM(total_tagihan) as omzet_harian, COUNT(*) as pesanan_harian'))
                ->groupBy('tanggal_pemesanan')
                ->orderBy('tanggal_pemesanan')
                ->get();

            $trendCategories = $trendData->map(fn ($item) => Carbon::parse($item->tanggal_pemesanan)->format('d M'))->toArray();
            $trendSeriesOmzet = $trendData->map(fn ($item) => (float) $item->omzet_harian)->toArray();
            $trendSeriesCount = $trendData->map(fn ($item) => (int) $item->pesanan_harian)->toArray();
        } else {
            // Trend per Open PO Batch across history
            $allBatches = OpenPo::query()->orderBy('id')->get();
            $trendCategories = [];
            $trendSeriesOmzet = [];
            $trendSeriesCount = [];

            foreach ($allBatches as $batch) {
                $batchSales = Sale::where('open_po_id', $batch->id)->where('status_order', '!=', 'dibatalkan');
                $trendCategories[] = $batch->kode_po ?: $batch->nama_po;
                $trendSeriesOmzet[] = (float) $batchSales->sum('total_tagihan');
                $trendSeriesCount[] = (int) $batchSales->count();
            }
        }

        $chartTrend = [
            'categories' => $trendCategories,
            'seriesOmzet' => $trendSeriesOmzet,
            'seriesCount' => $trendSeriesCount,
        ];

        $this->chartFinancial = $chartFinancial;
        $this->chartTrend = $chartTrend;

        $this->dispatch('charts-updated', financial: $this->chartFinancial, trend: $this->chartTrend);

        return view('livewire.admin.dashboard', [
            'openPos' => $openPos,
            'activeOpenPo' => $activeOpenPo,
            'totalOmzet' => $totalOmzet,
            'totalPembelian' => $totalPembelian,
            'totalBiayaLain' => $totalBiayaLain,
            'surplus' => $surplus,
            'surplusMargin' => $surplusMargin,
            'totalPaid' => $totalPaid,
            'totalPiutang' => $totalPiutang,
            'totalOrders' => $totalOrders,
            'topProducts' => $topProducts,
            'ordersByStatus' => $ordersByStatus,
            'chartFinancial' => $chartFinancial,
            'chartTrend' => $chartTrend,
        ]);
    }
}
