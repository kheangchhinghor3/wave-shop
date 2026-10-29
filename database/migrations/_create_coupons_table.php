<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type');                                  // 'percent' or 'fixed'
            $table->decimal('value', 8, 2);                          // 10 = 10% or $10
            $table->decimal('min_order', 8, 2)->default(0);          // minimum cart total
            $table->unsignedInteger('usage_limit')->nullable();      // empty = unlimited
            $table->unsignedInteger('times_used')->default(0);
            $table->timestamp('expires_at')->nullable();             // empty = never expires
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};