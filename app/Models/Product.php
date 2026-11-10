<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    // A limited drop whose end time has already passed
    public function dropEnded(): bool
    {
        return $this->is_limited
            && $this->drop_ends_at !== null
            && $this->drop_ends_at->isPast();
    }

    // A limited drop that is still running
    public function dropLive(): bool
    {
        return $this->is_limited
            && $this->drop_ends_at !== null
            && $this->drop_ends_at->isFuture();
    }
}