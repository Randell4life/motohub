<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_center_technicians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 160);
            $table->string('phone', 40)->nullable();
            $table->string('specialty', 160)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['service_center_id', 'is_active']);
        });

        Schema::create('service_center_bays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['service_center_id', 'name']);
        });

        Schema::create('service_center_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete();
            $table->string('sku', 80)->nullable();
            $table->string('name', 180);
            $table->string('brand', 120)->nullable();
            $table->string('category', 120)->nullable();
            $table->string('fitment', 255)->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('min_quantity')->default(1);
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->decimal('sale_price', 12, 2)->default(0);
            $table->string('location', 120)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['service_center_id', 'name']);
        });

        Schema::create('service_center_inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained('service_center_inventory')->cascadeOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20);
            $table->unsignedInteger('quantity');
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->string('note', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('service_center_work_order_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained('service_center_inventory')->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total', 12, 2);
            $table->timestamps();
            $table->unique(['work_order_id', 'inventory_item_id']);
        });

        Schema::create('service_center_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method', 30)->default('cash');
            $table->string('reference', 120)->nullable();
            $table->text('note')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('paid_at');
            $table->timestamps();
            $table->index(['service_center_id', 'paid_at']);
        });

        Schema::create('service_center_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('channel', 30)->default('in_app');
            $table->string('event', 80);
            $table->string('recipient', 180)->nullable();
            $table->text('message');
            $table->string('status', 30)->default('logged');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_center_activity_logs');
        Schema::dropIfExists('service_center_payments');
        Schema::dropIfExists('service_center_work_order_parts');
        Schema::dropIfExists('service_center_inventory_movements');
        Schema::dropIfExists('service_center_inventory');
        Schema::dropIfExists('service_center_bays');
        Schema::dropIfExists('service_center_technicians');
        Schema::table('work_orders', function (Blueprint $table) {
            if (Schema::hasColumn('work_orders', 'service_center_bay_id')) $table->dropConstrainedForeignId('service_center_bay_id');
            if (Schema::hasColumn('work_orders', 'public_token')) $table->dropColumn('public_token');
        });
    }
};
