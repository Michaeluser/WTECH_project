<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', //can be null in case session_id isn't null
        'session_id', //can be null in case user_id isn't null
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cartItem(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}