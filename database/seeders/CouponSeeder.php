<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::updateOrCreate(
            ['code' => 'WAVE10'],
            ['type' => 'percent', 'value' => 10, 'min_order' => 0, 'is_active' => true]
        );

        Coupon::updateOrCreate(
            ['code' => 'WELCOME5'],
            ['type' => 'fixed', 'value' => 5, 'min_order' => 20, 'is_active' => true]
        );

        Coupon::updateOrCreate(
            ['code' => 'KPOP20'],
            ['type' => 'percent', 'value' => 20, 'min_order' => 50, 'usage_limit' => 100, 'is_active' => true]
        );

        Coupon::updateOrCreate(
            ['code' => 'OLDCODE'],
            ['type' => 'percent', 'value' => 15, 'min_order' => 0, 'expires_at' => now()->subDay(), 'is_active' => true]
        );
    }
}