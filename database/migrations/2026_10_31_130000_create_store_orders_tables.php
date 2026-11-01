<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('store_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number', 32)->unique();
            $table->string('status', 24)->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->unsignedInteger('points_used')->default(0);
            $table->decimal('points_discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('customer_name', 160);
            $table->string('phone', 40);
            $table->text('address');
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });
        Schema::create('store_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained('parts')->restrictOnDelete();
            $table->string('title');
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_order_items');
        Schema::dropIfExists('store_orders');
    }
};
