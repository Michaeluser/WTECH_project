<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favourite extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Статичный метод — возвращает массив id товаров в избранном
    // Работает для залогиненных (по user_id) и гостей (по session_id)
    // Вызывается из любого контроллера: Favourite::getIds()
    public static function getIds(): array
    {
        if (auth()->check()) {
            return static::where('user_id', auth()->id())
                ->pluck('product_id')
                ->toArray();
        }

        return static::where('session_id', session()->getId())
            ->pluck('product_id')
            ->toArray();
    }
}
