<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [

        'user_id',

        'invoice_number',

        'customer_name',

        'customer_email',

        'customer_whatsapp',

        'total_price',

        'status',

        'notes',
    ];

    protected $casts = [

        'total_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(
            OrderItem::class
        );
    }

    public function payment(): HasOne
    {
        return $this->hasOne(
            Payment::class
        );
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format(

            $this->total_price,

            0,

            ',',

            '.'
        );
    }
}