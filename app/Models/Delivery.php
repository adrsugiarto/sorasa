<?php

namespace App\Models;

use App\Livewire\Admin\DeliveryManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deliveries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'tanggal_pengiriman',
        'jam_pengiriman',
        'nama_penerima',
        'no_telepon_penerima',
        'alamat_lengkap',
        'patokan_lokasi',
        'link_google_maps',
        'status_pengantaran',
        'catatan_kurir',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pengiriman' => 'date',
        ];
    }

    /**
     * Get the formatted courier text for WhatsApp.
     */
    public function getFormatKurirAttribute(): string
    {
        return DeliveryManager::formatCourierText($this);
    }

    /**
     * Get the sale order associated with this delivery.
     *
     * @return BelongsTo<Sale, $this>
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
