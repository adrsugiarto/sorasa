<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtherExpense extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'other_expenses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'open_po_id',
        'tanggal_pengeluaran',
        'kategori_biaya',
        'nama_pengeluaran',
        'nominal',
        'metode_pembayaran',
        'keterangan',
        'bukti_nota',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pengeluaran' => 'date',
            'nominal' => 'decimal:2',
        ];
    }

    /**
     * Get the open PO batch associated with this expense.
     *
     * @return BelongsTo<OpenPo, $this>
     */
    public function openPo(): BelongsTo
    {
        return $this->belongsTo(OpenPo::class);
    }
}
