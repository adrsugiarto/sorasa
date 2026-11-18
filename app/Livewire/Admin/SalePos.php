<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\OpenPo;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Kasir POS — SORASA')]
class SalePos extends Component
{
    // Filter & catalog
    public string $search = '';

    public string $selectedCategory = '';

    // Order configuration
    public ?int $open_po_id = null;

    public ?int $customer_id = null;

    public string $customerSearch = '';

    /**
     * Shopping Cart items.
     * Array format: [product_id => ['product_id' => X, 'nama_produk' => Y, 'harga_jual' => Z, 'qty' => N, 'subtotal' => M]]
     *
     * @var array<int, array{product_id: int, nama_produk: string, harga_jual: float, qty: int, subtotal: float}>
     */
    public array $cart = [];

    // Financial adjustments
    public float|int|string $diskon = 0;

    public float|int|string $ongkos_kirim = 0;

    public ?string $keterangan_pesanan = '';

    // Delivery options
    public bool $customDelivery = false;

    public string $nama_penerima = '';

    public string $no_telepon_penerima = '';

    public string $alamat_lengkap = '';

    public ?string $patokan_lokasi = '';

    public ?string $link_google_maps = '';

    public string $tanggal_pengiriman = '';

    public ?string $jam_pengiriman = '';

    // Payment options
    public string $pembayaran_awal = 'belum_bayar'; // belum_bayar, dp, lunas

    public float|int|string $nominal_bayar = 0;

    public string $metode_pembayaran = 'Transfer BCA';

    public ?string $catatan_pembayaran = '';

    // Quick Add Customer Modal
    public bool $showQuickCustomerModal = false;

    public string $quick_nama = '';

    public string $quick_no_whatsapp = '';

    public string $quick_alamat = '';

    public string $quick_kategori = 'Reguler';

    // Success Invoice Modal
    public bool $showSuccessModal = false;

    public ?int $lastSaleId = null;

    public ?string $lastOrderNumber = null;

    /**
     * Mount component.
     */
    public function mount(): void
    {
        $activePo = OpenPo::where('status', 'open')->first();
        if ($activePo) {
            $this->open_po_id = $activePo->id;
            $this->tanggal_pengiriman = $activePo->tanggal_pengiriman->format('Y-m-d');
        } else {
            $this->tanggal_pengiriman = Carbon::today()->addDays(2)->format('Y-m-d');
        }
    }

    /**
     * When open_po_id changes, sync tanggal_pengiriman.
     */
    public function updatedOpenPoId(int $val): void
    {
        $po = OpenPo::find($val);
        if ($po) {
            $this->tanggal_pengiriman = $po->tanggal_pengiriman->format('Y-m-d');
        }
    }

    /**
     * When customer is selected, auto fill delivery details if not custom.
     */
    public function updatedCustomerId($val = null): void
    {
        $id = $val ? (int) $val : null;
        if (! $id) {
            if (! $this->customDelivery) {
                $this->nama_penerima = '';
                $this->no_telepon_penerima = '';
                $this->alamat_lengkap = '';
            }

            return;
        }

        $customer = Customer::find($id);
        if ($customer && ! $this->customDelivery) {
            $this->nama_penerima = $customer->nama;
            $this->no_telepon_penerima = $customer->no_whatsapp;
            $this->alamat_lengkap = $customer->alamat;
        }
    }

    /**
     * Explicit customer selector method.
     */
    public function selectCustomer($id = null): void
    {
        $this->customer_id = $id ? (int) $id : null;
        $this->updatedCustomerId($this->customer_id);
    }

    /**
     * Add product to cart.
     */
    public function addToCart(int $productId): void
    {
        $product = Product::query()->findOrFail($productId);

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
            $this->cart[$productId]['subtotal'] = $this->cart[$productId]['qty'] * $this->cart[$productId]['harga_jual'];
        } else {
            $this->cart[$productId] = [
                'product_id' => $product->id,
                'nama_produk' => $product->nama_produk,
                'harga_jual' => (float) $product->harga_jual,
                'qty' => 1,
                'subtotal' => (float) $product->harga_jual,
            ];
        }

        $this->syncPaymentNominal();
    }

    /**
     * Increment item qty in cart.
     */
    public function incrementQty(int $productId): void
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
            $this->cart[$productId]['subtotal'] = $this->cart[$productId]['qty'] * $this->cart[$productId]['harga_jual'];
            $this->syncPaymentNominal();
        }
    }

    /**
     * Set specific item qty in cart.
     */
    public function updateQty(int $productId, int $qty): void
    {
        if (isset($this->cart[$productId])) {
            if ($qty > 0) {
                $this->cart[$productId]['qty'] = $qty;
                $this->cart[$productId]['subtotal'] = $qty * $this->cart[$productId]['harga_jual'];
            } else {
                unset($this->cart[$productId]);
            }
            $this->syncPaymentNominal();
        }
    }

    /**
     * Decrement item qty in cart.
     */
    public function decrementQty(int $productId): void
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] > 1) {
                $this->cart[$productId]['qty']--;
                $this->cart[$productId]['subtotal'] = $this->cart[$productId]['qty'] * $this->cart[$productId]['harga_jual'];
            } else {
                unset($this->cart[$productId]);
            }
            $this->syncPaymentNominal();
        }
    }

    /**
     * Remove item from cart.
     */
    public function removeFromCart(int $productId): void
    {
        unset($this->cart[$productId]);
        $this->syncPaymentNominal();
    }

    /**
     * Clear all items in cart.
     */
    public function clearCart(): void
    {
        $this->cart = [];
        $this->diskon = 0;
        $this->ongkos_kirim = 0;
        $this->nominal_bayar = 0;
    }

    /**
     * Subtotal property.
     */
    public function getSubtotalProperty(): float
    {
        $sub = 0;
        foreach ($this->cart as $item) {
            $sub += ($item['qty'] * $item['harga_jual']);
        }

        return $sub;
    }

    /**
     * Total tagihan property.
     */
    public function getTotalTagihanProperty(): float
    {
        $total = $this->subtotal - (float) $this->diskon + (float) $this->ongkos_kirim;

        return max(0, $total);
    }

    /**
     * Synchronize payment nominal when payment option changes.
     */
    public function updatedPembayaranAwal(string $val): void
    {
        if ($val === 'lunas') {
            $this->nominal_bayar = $this->totalTagihan;
        } elseif ($val === 'dp') {
            // Suggest 50% DP rounded
            $this->nominal_bayar = round($this->totalTagihan * 0.5, -3);
        } else {
            $this->nominal_bayar = 0;
        }
    }

    /**
     * Sync payment nominal on cart/adjustment change.
     */
    public function syncPaymentNominal(): void
    {
        if ($this->pembayaran_awal === 'lunas') {
            $this->nominal_bayar = $this->totalTagihan;
        }
    }

    /**
     * Open quick add customer modal.
     */
    public function openQuickCustomerModal(?string $prefill = null): void
    {
        $this->quick_nama = $prefill ?: '';
        $this->quick_no_whatsapp = '';
        $this->quick_alamat = '';
        $this->quick_kategori = 'Reguler';
        $this->showQuickCustomerModal = true;
    }

    /**
     * Close quick add customer modal.
     */
    public function closeQuickCustomerModal(): void
    {
        $this->showQuickCustomerModal = false;
    }

    /**
     * Save newly added customer and select them.
     */
    public function saveQuickCustomer(): void
    {
        $this->validate([
            'quick_nama' => ['required', 'string', 'max:255'],
            'quick_no_whatsapp' => ['required', 'string', 'max:25'],
            'quick_alamat' => ['required', 'string', 'max:1000'],
            'quick_kategori' => ['required', 'in:Reguler,Reseller,VIP,Keluarga/Teman'],
        ]);

        $phone = preg_replace('/[^0-9]/', '', $this->quick_no_whatsapp);

        $customer = Customer::query()->create([
            'nama' => trim($this->quick_nama),
            'no_whatsapp' => $phone ?: $this->quick_no_whatsapp,
            'alamat' => trim($this->quick_alamat),
            'kategori_pelanggan' => $this->quick_kategori,
            'catatan' => 'Didaftarkan dari Kasir POS',
        ]);

        $this->customer_id = $customer->id;
        $this->nama_penerima = $customer->nama;
        $this->no_telepon_penerima = $customer->no_whatsapp;
        $this->alamat_lengkap = $customer->alamat;

        $this->showQuickCustomerModal = false;
        session()->flash('success', "Pelanggan {$customer->nama} berhasil didaftarkan dan dipilih!");
    }

    /**
     * Process checkout and save order.
     */
    public function checkout(): void
    {
        $this->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'open_po_id' => ['nullable', 'exists:open_pos,id'],
            'cart' => ['required', 'array', 'min:1'],
            'diskon' => ['numeric', 'min:0'],
            'ongkos_kirim' => ['numeric', 'min:0'],
            'nama_penerima' => ['required', 'string', 'max:255'],
            'no_telepon_penerima' => ['required', 'string', 'max:25'],
            'alamat_lengkap' => ['required', 'string', 'max:1000'],
            'tanggal_pengiriman' => ['required', 'date'],
        ]);

        DB::transaction(function () {
            // Generate unique invoice number: INV-YYYYMMDD-XXXX
            $datePrefix = Carbon::today()->format('Ymd');
            $countToday = Sale::whereDate('created_at', Carbon::today())->count() + 1;
            $orderNumber = 'INV-'.$datePrefix.'-'.str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);

            // Determine payment status
            $nominalBayar = (float) $this->nominal_bayar;
            $totalTagihan = $this->totalTagihan;

            $statusPembayaran = 'belum_bayar';
            if ($nominalBayar >= $totalTagihan && $totalTagihan > 0) {
                $statusPembayaran = 'lunas';
            } elseif ($nominalBayar > 0) {
                $statusPembayaran = 'dp';
            }

            // 1. Create Sale
            $sale = Sale::query()->create([
                'nomor_order' => $orderNumber,
                'open_po_id' => $this->open_po_id,
                'customer_id' => $this->customer_id,
                'tanggal_pemesanan' => Carbon::today()->format('Y-m-d'),
                'subtotal' => $this->subtotal,
                'diskon' => (float) $this->diskon,
                'ongkos_kirim' => (float) $this->ongkos_kirim,
                'total_tagihan' => $totalTagihan,
                'status_pembayaran' => $statusPembayaran,
                'status_order' => 'diproses',
                'order_source' => 'admin',
                'keterangan_pesanan' => $this->keterangan_pesanan,
            ]);

            // 2. Create Sale Items
            foreach ($this->cart as $item) {
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'nama_produk_snapshot' => $item['nama_produk'],
                    'jumlah_paket' => $item['qty'],
                    'harga_jual' => $item['harga_jual'],
                    'subtotal' => $item['qty'] * $item['harga_jual'],
                ]);
            }

            // 3. Create Delivery
            $sale->delivery()->create([
                'tanggal_pengiriman' => $this->tanggal_pengiriman,
                'jam_pengiriman' => $this->jam_pengiriman,
                'nama_penerima' => trim($this->nama_penerima),
                'no_telepon_penerima' => trim($this->no_telepon_penerima),
                'alamat_lengkap' => trim($this->alamat_lengkap),
                'patokan_lokasi' => $this->patokan_lokasi,
                'link_google_maps' => $this->link_google_maps,
                'status_pengantaran' => 'dijadwalkan',
                'catatan_kurir' => null,
            ]);

            // 4. Create Payment if any paid
            if ($nominalBayar > 0) {
                $jenisBayar = $nominalBayar >= $totalTagihan ? 'pelunasan' : 'dp';
                $sale->payments()->create([
                    'tanggal_bayar' => Carbon::today()->format('Y-m-d'),
                    'jenis_pembayaran' => $jenisBayar,
                    'nominal_bayar' => $nominalBayar,
                    'metode_pembayaran' => $this->metode_pembayaran,
                    'bukti_bayar' => null,
                    'status_verifikasi' => 'verified',
                    'catatan' => $this->catatan_pembayaran ?: 'Pembayaran awal di Kasir POS',
                ]);
            }

            $this->lastSaleId = $sale->id;
            $this->lastOrderNumber = $sale->nomor_order;
        });

        $this->showSuccessModal = true;
    }

    /**
     * Start new transaction.
     */
    public function resetPos(): void
    {
        $this->clearCart();
        $this->customer_id = null;
        $this->keterangan_pesanan = '';
        $this->customDelivery = false;
        $this->nama_penerima = '';
        $this->no_telepon_penerima = '';
        $this->alamat_lengkap = '';
        $this->patokan_lokasi = '';
        $this->link_google_maps = '';
        $this->jam_pengiriman = '';
        $this->pembayaran_awal = 'belum_bayar';
        $this->nominal_bayar = 0;
        $this->showSuccessModal = false;
        $this->lastSaleId = null;
        $this->lastOrderNumber = null;
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->when($this->search, function ($q) {
                $q->where('nama_produk', 'like', '%'.$this->search.'%')
                    ->orWhere('kode_produk', 'like', '%'.$this->search.'%');
            })
            ->when($this->selectedCategory, function ($q) {
                $q->where('kategori', $this->selectedCategory);
            })
            ->orderBy('kategori')
            ->orderBy('nama_produk')
            ->get();

        $customers = Customer::query()
            ->orderBy('nama')
            ->get();

        $openPos = OpenPo::query()
            ->whereIn('status', ['open', 'draft', 'closed'])
            ->latest('tanggal_buka')
            ->get();

        $categories = Product::where('is_active', true)->distinct()->pluck('kategori');

        return view('livewire.admin.sale-pos', [
            'products' => $products,
            'customers' => $customers,
            'openPos' => $openPos,
            'categories' => $categories,
        ]);
    }
}
