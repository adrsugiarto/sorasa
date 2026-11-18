<?php

namespace App\Livewire\Admin;

use App\Models\Delivery;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Pengantaran & Kurir — SORASA')]
class DeliveryManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $selectedStatus = '';

    public ?string $filterDate = null;

    // Modal Edit Delivery
    public bool $showEditModal = false;

    public ?int $deliveryId = null;

    public string $nama_penerima = '';

    public string $no_telepon_penerima = '';

    public string $alamat_lengkap = '';

    public ?string $patokan_lokasi = '';

    public ?string $link_google_maps = '';

    public string $tanggal_pengiriman = '';

    public ?string $jam_pengiriman = '';

    public string $status_pengantaran = 'menunggu';

    public ?string $catatan_kurir = '';

    /**
     * Delivery statuses.
     *
     * @var array<int, string>
     */
    public array $deliveryStatuses = [
        'menunggu',
        'dijadwalkan',
        'dalam_pengiriman',
        'terkirim',
    ];

    /**
     * Reset pagination when search changes.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when status changes.
     */
    public function updatingSelectedStatus(): void
    {
        $this->resetPage();
    }

    /**
     * Quick update delivery status from dropdown.
     */
    public function updateStatus(int $id, string $status): void
    {
        $deliv = Delivery::query()->findOrFail($id);
        $deliv->status_pengantaran = $status;
        $deliv->save();

        session()->flash('success', "Status pengantaran untuk {$deliv->nama_penerima} berhasil diubah menjadi ".strtoupper(str_replace('_', ' ', $status)).'.');
    }

    /**
     * Open edit delivery modal.
     */
    public function openEditModal(int $id): void
    {
        $d = Delivery::query()->findOrFail($id);
        $this->deliveryId = $d->id;
        $this->nama_penerima = $d->nama_penerima;
        $this->no_telepon_penerima = $d->no_telepon_penerima;
        $this->alamat_lengkap = $d->alamat_lengkap;
        $this->patokan_lokasi = $d->patokan_lokasi;
        $this->link_google_maps = $d->link_google_maps;
        $this->tanggal_pengiriman = $d->tanggal_pengiriman->format('Y-m-d');
        $this->jam_pengiriman = $d->jam_pengiriman;
        $this->status_pengantaran = $d->status_pengantaran;
        $this->catatan_kurir = $d->catatan_kurir;

        $this->showEditModal = true;
    }

    /**
     * Close edit modal.
     */
    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->deliveryId = null;
    }

    /**
     * Save updated delivery details.
     */
    public function save(): void
    {
        $this->validate([
            'nama_penerima' => ['required', 'string', 'max:255'],
            'no_telepon_penerima' => ['required', 'string', 'max:25'],
            'alamat_lengkap' => ['required', 'string', 'max:1000'],
            'tanggal_pengiriman' => ['required', 'date'],
            'status_pengantaran' => ['required', 'in:menunggu,dijadwalkan,dalam_pengiriman,terkirim'],
        ]);

        $deliv = Delivery::query()->findOrFail($this->deliveryId);
        $deliv->update([
            'nama_penerima' => trim($this->nama_penerima),
            'no_telepon_penerima' => trim($this->no_telepon_penerima),
            'alamat_lengkap' => trim($this->alamat_lengkap),
            'patokan_lokasi' => $this->patokan_lokasi,
            'link_google_maps' => $this->link_google_maps,
            'tanggal_pengiriman' => $this->tanggal_pengiriman,
            'jam_pengiriman' => $this->jam_pengiriman,
            'status_pengantaran' => $this->status_pengantaran,
            'catatan_kurir' => $this->catatan_kurir,
        ]);

        session()->flash('success', 'Data pengantaran berhasil diperbarui.');
        $this->closeEditModal();
    }

    /**
     * Generate courier copy text for WhatsApp.
     */
    public static function formatCourierText(Delivery $d): string
    {
        $itemsText = '';
        if ($d->sale && $d->sale->items) {
            foreach ($d->sale->items as $it) {
                $itemsText .= "- {$it->jumlah_paket}x {$it->nama_produk_snapshot}\n";
            }
        }

        $jam = $d->jam_pengiriman ? " ({$d->jam_pengiriman})" : '';
        $patokan = $d->patokan_lokasi ? "\nPatokan: {$d->patokan_lokasi}" : '';
        $gmaps = $d->link_google_maps ? "\nGoogle Maps: {$d->link_google_maps}" : '';
        $catatan = $d->catatan_kurir ? "\nCatatan Kurir: {$d->catatan_kurir}" : '';

        return "📦 *PENGANTARAN SORASA*\n"
            ."No. Order: *{$d->sale?->nomor_order}*\n"
            ."Jadwal Kirim: {$d->tanggal_pengiriman->format('d M Y')}{$jam}\n"
            ."-------------------------\n"
            ."*Penerima:* {$d->nama_penerima}\n"
            ."*No. HP:* {$d->no_telepon_penerima}\n"
            ."*Alamat:* {$d->alamat_lengkap}{$patokan}{$gmaps}\n"
            ."-------------------------\n"
            ."*Rincian Menu:* \n{$itemsText}"
            ."-------------------------{$catatan}";
    }

    /**
     * Render view.
     */
    public function render(): View
    {
        $query = Delivery::query()
            ->with(['sale.customer', 'sale.items', 'sale.openPo'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama_penerima', 'like', '%'.$this->search.'%')
                        ->orWhere('no_telepon_penerima', 'like', '%'.$this->search.'%')
                        ->orWhere('alamat_lengkap', 'like', '%'.$this->search.'%')
                        ->orWhereHas('sale', function ($s) {
                            $s->where('nomor_order', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->selectedStatus, function ($q) {
                $q->where('status_pengantaran', $this->selectedStatus);
            })
            ->when($this->filterDate, function ($q) {
                $q->whereDate('tanggal_pengiriman', $this->filterDate);
            });

        $deliveries = $query->orderBy('tanggal_pengiriman')->paginate(15);

        return view('livewire.admin.delivery-manager', [
            'deliveries' => $deliveries,
        ]);
    }
}
