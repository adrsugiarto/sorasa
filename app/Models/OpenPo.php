<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpenPo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'open_pos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_po',
        'kode_po',
        'tanggal_buka',
        'tanggal_tutup',
        'tanggal_pengiriman',
        'status',
        'is_published',
        'catatan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_buka' => 'date',
            'tanggal_tutup' => 'date',
            'tanggal_pengiriman' => 'date',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Get purchases associated with this Open PO batch.
     *
     * @return HasMany<Purchase, $this>
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get other expenses associated with this Open PO batch.
     *
     * @return HasMany<OtherExpense, $this>
     */
    public function otherExpenses(): HasMany
    {
        return $this->hasMany(OtherExpense::class);
    }

    /**
     * Get sales associated with this Open PO batch.
     *
     * @return HasMany<Sale, $this>
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
