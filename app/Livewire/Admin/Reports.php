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
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.admin')]
#[Title('Laporan Laba Rugi Sederhana — SORASA')]
class Reports extends Component
{
    public string $selectedOpenPoId = '';

    public string $period = 'all'; // 'all', 'this_month', 'last_month', 'custom'

    public ?string $startDate = null;

    public ?string $endDate = null;

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
     * Export simple profit & loss statement to CSV.
     */
    public function exportCsv(): StreamedResponse
    {
        $data = $this->calculateFinancials();
        $filename = 'Laporan_Laba_Rugi_SORASA_'.date('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['LAPORAN LABA RUGI SEDERHANA — SORASA KITCHEN & PASTRY']);
            fputcsv($handle, ['Periode / Batch', $data['filterLabel']]);
            fputcsv($handle, ['Tanggal Cetak', Carbon::now()->format('d/m/Y H:i').' WIB']);
            fputcsv($handle, []);

            // 1. PENDAPATAN
            fputcsv($handle, ['1. PENDAPATAN PENJUALAN (OMZET)', 'NOMINAL (RP)']);
            fputcsv($handle, ['   Subtotal Penjualan Menu (Bruto)', $data['totalSubtotal']]);
            fputcsv($handle, ['   Pendapatan Ongkos Kirim (+)', $data['totalOngkir']]);
            fputcsv($handle, ['   Potongan Diskon Pesanan (-)', -$data['totalDiskon']]);
            fputcsv($handle, ['   TOTAL OMZET BERSIH', $data['totalOmzet']]);
            fputcsv($handle, []);

            // 2. PEMBELIAN BAHAN
            fputcsv($handle, ['2. PEMBELIAN BAHAN BAKU (HABIS PAKAI)', 'NOMINAL (RP)']);
            foreach ($data['purchasesList'] as $pur) {
                fputcsv($handle, ['   '.$pur->nama_barang.' ('.$pur->jumlah.' '.$pur->satuan.')', $pur->total_pembelian]);
            }
            fputcsv($handle, ['   TOTAL PEMBELIAN BAHAN', $data['totalPembelian']]);
            fputcsv($handle, []);

            // 3. BIAYA OPERASIONAL LAIN
            fputcsv($handle, ['3. BIAYA OPERASIONAL LAIN', 'NOMINAL (RP)']);
            foreach ($data['expensesByCategory'] as $cat => $nominal) {
                fputcsv($handle, ['   Kategori: '.$cat, $nominal]);
            }
            fputcsv($handle, ['   TOTAL BIAYA OPERASIONAL', $data['totalBiayaLain']]);
            fputcsv($handle, []);

            // 4. SURPLUS / DEFISIT
            fputcsv($handle, ['4. HASIL USAHA BERSIH (SURPLUS / DEFISIT)', 'NOMINAL (RP)']);
            fputcsv($handle, ['   SURPLUS BERSIH (Omzet - Bahan - Biaya)', $data['surplus']]);
            fputcsv($handle, ['   Persentase Margin Surplus', $data['surplusMargin'].'%']);
            fputcsv($handle, []);

            // 5. STATUS KAS & PIUTANG
            fputcsv($handle, ['5. ARUS KAS & TRACKING PIUTANG', 'NOMINAL (RP)']);
            fputcsv($handle, ['   Total Kas Diterima (Lunas & DP)', $data['totalPaid']]);
            fputcsv($handle, ['   Sisa Piutang Belum Tertagih', $data['totalPiutang']]);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Compute financial aggregates based on current filter states.
     *
     * @return array<string, mixed>
     */
    protected function calculateFinancials(): array
    {
        // Filter label
        $filterLabel = 'Semua Periode';
        if ($this->selectedOpenPoId) {
            $po = OpenPo::find($this->selectedOpenPoId);
            $filterLabel = $po ? "Batch PO: [{$po->kode_po}] {$po->nama_po}" : 'Batch Tertentu';
        } elseif ($this->period === 'this_month') {
            $filterLabel = 'Bulan Ini ('.Carbon::now()->translatedFormat('F Y').')';
        } elseif ($this->period === 'last_month') {
            $filterLabel = 'Bulan Lalu ('.Carbon::now()->subMonth()->translatedFormat('F Y').')';
        } elseif ($this->startDate && $this->endDate) {
            $filterLabel = Carbon::parse($this->startDate)->format('d M Y').' s/d '.Carbon::parse($this->endDate)->format('d M Y');
        }

        // 1. Sales
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

        $sales = $salesQuery->with(['customer', 'items', 'payments'])->get();
        $totalSubtotal = (float) $sales->sum('subtotal');
        $totalOngkir = (float) $sales->sum('ongkos_kirim');
        $totalDiskon = (float) $sales->sum('diskon');
        $totalOmzet = (float) $sales->sum('total_tagihan');
        $orderCount = $sales->count();

        // 2. Purchases
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

        $purchasesList = $purchasesQuery->latest('tanggal_pembelian')->get();
        $totalPembelian = (float) $purchasesList->sum('total_pembelian');

        // 3. Other Expenses
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

        $expensesList = $expensesQuery->latest('tanggal_pengeluaran')->get();
        $totalBiayaLain = (float) $expensesList->sum('nominal');

        $expensesByCategory = [];
        foreach ($expensesList as $exp) {
            $cat = $exp->kategori_biaya ?: 'Lainnya';
            $expensesByCategory[$cat] = ($expensesByCategory[$cat] ?? 0) + (float) $exp->nominal;
        }

        // 4. Surplus
        $surplus = $totalOmzet - $totalPembelian - $totalBiayaLain;
        $surplusMargin = $totalOmzet > 0 ? round(($surplus / $totalOmzet) * 100, 1) : 0;

        // 5. Cash & Receivables
        $totalPaid = 0;
        $totalPiutang = 0;
        foreach ($sales as $sale) {
            $paid = (float) $sale->payments->where('status_verifikasi', 'verified')->sum('nominal_bayar');
            $totalPaid += $paid;
            $rem = max(0.0, (float) $sale->total_tagihan - $paid);
            $totalPiutang += $rem;
        }

        // 6. Sold Menu breakdown
        $saleIds = $sales->pluck('id');
        $menuBreakdown = SaleItem::query()
            ->whereIn('sale_id', $saleIds)
            ->select('nama_produk_snapshot', DB::raw('SUM(jumlah_paket) as total_qty'), DB::raw('SUM(subtotal) as total_nominal'))
            ->groupBy('nama_produk_snapshot')
            ->orderByDesc('total_nominal')
            ->get();

        return [
            'filterLabel' => $filterLabel,
            'totalSubtotal' => $totalSubtotal,
            'totalOngkir' => $totalOngkir,
            'totalDiskon' => $totalDiskon,
            'totalOmzet' => $totalOmzet,
            'orderCount' => $orderCount,
            'purchasesList' => $purchasesList,
            'totalPembelian' => $totalPembelian,
            'expensesList' => $expensesList,
            'expensesByCategory' => $expensesByCategory,
            'totalBiayaLain' => $totalBiayaLain,
            'surplus' => $surplus,
            'surplusMargin' => $surplusMargin,
            'totalPaid' => $totalPaid,
            'totalPiutang' => $totalPiutang,
            'menuBreakdown' => $menuBreakdown,
        ];
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        $openPos = OpenPo::query()->latest('id')->get(['id', 'nama_po', 'kode_po', 'status']);
        $financials = $this->calculateFinancials();

        return view('livewire.admin.reports', array_merge([
            'openPos' => $openPos,
        ], $financials));
    }
}
