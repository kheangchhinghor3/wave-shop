<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order',
        'usage_limit',
        'times_used',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value'      => 'decimal:2',
            'min_order'  => 'decimal:2',
            'expires_at' => 'datetime',
            'is_active'  => 'boolean',
        ];
    }

    // Money taken off a given subtotal (never more than the subtotal itself)
    public function discountFor(float $subtotal): float
    {
        $discount = $this->type === 'percent'
            ? $subtotal * ((float) $this->value / 100)
            : (float) $this->value;

        return round(min($discount, $subtotal), 2);
    }

    // Returns null if the coupon can be used, or a message explaining why not
    public function problemFor(float $subtotal): ?string
    {
        if (! $this->is_active) {
            return 'This coupon is not active.';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'This coupon has expired.';
        }

        if ($this->usage_limit !== null && $this->times_used >= $this->usage_limit) {
            return 'This coupon has reached its usage limit.';
        }

        if ($subtotal < (float) $this->min_order) {
            return 'This coupon needs a minimum order of $' . number_format($this->min_order, 2) . '.';
        }

        return null;
    }
}