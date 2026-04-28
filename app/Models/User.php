<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'phone_number',
        'sex',
        'birthday',
        'bic_card',
        'swift_card',
    ];

    // Аксессор: $user->name возвращает "Имя Фамилия"
    // Нужен потому что шаблоны используют auth()->user()->name
    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }
    
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function reviwes(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
