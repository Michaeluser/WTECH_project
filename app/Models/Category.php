<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'catalog_mode',
        'catalog_slider_caption',
        'catalog_advice_title',
        'catalog_advice_subtitle',
        'catalog_advice_text',
        'nav_order',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function navUrl(): string
    {
        if ($this->catalog_mode === 'landing') {
            return route('catalog.show', $this);
        }

        return route('categories.show', $this);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function catalogItems(): HasMany
    {
        return $this->hasMany(CatalogItem::class)->orderBy('sort_order');
    }
}
