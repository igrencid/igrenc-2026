<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'promo_price',
        'promo_ends_at',
        'stock',
        'rarity',
        'image',
        'is_featured',
        'is_promo',
        'is_active',
        'requires_access_link',
        'access_link',
        'access_instruction',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'promo_price' => 'decimal:2',
        'promo_ends_at' => 'datetime',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'is_promo' => 'boolean',
        'is_active' => 'boolean',
        'requires_access_link' => 'boolean',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFinalPriceAttribute()
    {
        if (
            $this->is_promo &&
            $this->promo_price &&
            (! $this->promo_ends_at || $this->promo_ends_at->isFuture())
        ) {
            return $this->promo_price;
        }

        return $this->price;
    }
}