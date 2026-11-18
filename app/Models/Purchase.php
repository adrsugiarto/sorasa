<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'open_po_id',
        'tanggal_pembelian',
        'nama_toko_supplier',
        'nama_barang',
        'jumlah',
        'satuan',
        'harga_satuan',
        'total_pembelian',
        'metode_pembayaran',
        'keterangan',
        'foto_nota',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pembelian' => 'date',
            'jumlah' => 'decimal:2',
            'harga_satuan' => 'decimal:2',
            'total_pembelian' => 'decimal:2',
        ];
    }

    /**
     * Get the open PO batch associated with this purchase.
     *
     * @return BelongsTo<OpenPo, $this>
     */
    public function openPo(): BelongsTo
    {
        return $this->belongsTo(OpenPo::class);
    }
}
