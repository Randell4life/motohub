<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reward_catalog_items', function (Blueprint $table) {
            $table->id();
            $table->string('title', 160);
            $table->string('slug', 180)->unique();
            $table->text('description')->nullable();
            $table->string('image_url', 2048)->nullable();
            $table->unsignedInteger('points_cost');
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('reward_catalog_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('reward_catalog_items')->nullOnDelete();
            $table->string('item_title', 160);
            $table->string('customer_name', 160);
            $table->string('phone', 40);
            $table->text('address');
            $table->unsignedInteger('points_spent');
            $table->string('status', 24)->default('pending')->index();
            $table->text('admin_note')->nullable();
            $table->timestamp('fulfilled_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_catalog_orders');
        Schema::dropIfExists('reward_catalog_items');
    }
};
