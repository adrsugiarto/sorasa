<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sale extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomor_order',
        'open_po_id',
        'customer_id',
        'tanggal_pemesanan',
        'subtotal',
        'diskon',
        'ongkos_kirim',
        'total_tagihan',
        'status_pembayaran',
        'status_order',
        'order_source',
        'keterangan_pesanan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pemesanan' => 'date',
            'subtotal' => 'decimal:2',
            'diskon' => 'decimal:2',
            'ongkos_kirim' => 'decimal:2',
            'total_tagihan' => 'decimal:2',
        ];
    }

    /**
     * Get the open PO batch associated with this sale.
     *
     * @return BelongsTo<OpenPo, $this>
     */
    public function openPo(): BelongsTo
    {
        return $this->belongsTo(OpenPo::class);
    }

    /**
     * Get the customer who made this purchase.
     *
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the order line items.
     *
     * @return HasMany<SaleItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the delivery information for this order.
     *
     * @return HasOne<Delivery, $this>
     */
    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }

    /**
     * Get all payments recorded for this order.
     *
     * @return HasMany<Payment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Total amount paid so far.
     */
    protected function totalPaid(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                return (float) $this->payments()
                    ->where('status_verifikasi', 'verified')
                    ->sum('nominal_bayar');
            }
        );
    }

    /**
     * Remaining unpaid balance.
     */
    protected function remainingBalance(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                $rem = (float) $this->total_tagihan - $this->total_paid;

                return max(0.0, $rem);
            }
        );
    }
}
