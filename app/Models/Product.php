<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'collection',
        'is_limited',
        'is_featured',
        'drop_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_limited' => 'boolean',
            'is_featured' => 'boolean',
            'drop_ends_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
