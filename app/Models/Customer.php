<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nama',
        'no_whatsapp',
        'alamat',
        'kategori_pelanggan',
        'catatan',
    ];

    /**
     * Get the user account for the customer if linked.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all sales transactions for this customer.
     *
     * @return HasMany<Sale, $this>
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Generate normalized WhatsApp link.
     */
    protected function waLink(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $phone = preg_replace('/[^0-9]/', '', (string) $this->no_whatsapp);
                if (str_starts_with($phone, '0')) {
                    $phone = '62'.substr($phone, 1);
                }

                return 'https://wa.me/'.$phone;
            }
        );
    }
}
