<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryMethod extends Model
{
    
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
    ];

    public function order(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_method_id', 'id');
    }
}


