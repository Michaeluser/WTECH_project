<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'email',
        'phone_number',
        'first_name',
        'last_name',
        'city',
        'postal_code',
        'street_address',
        'notes',
        'delivery_method',
        'payment_method',
        'subtotal',
        'delivery_price',
        'total',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
