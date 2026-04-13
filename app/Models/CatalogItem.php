<?php

namespace App\Models;

use Database\Factories\CatalogItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogItem extends Model
{
    /** @use HasFactory<CatalogItemFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'target_category_id',
        'kind',
        'title',
        'image_path',
        'alt_text',
        'sort_order',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function targetCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'target_category_id');
    }
}
