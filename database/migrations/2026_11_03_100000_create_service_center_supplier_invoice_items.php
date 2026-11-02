<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_center_supplier_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('service_center_supplier_invoices')->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained('service_center_inventory')->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('received_quantity')->default(0);
            $table->decimal('unit_cost', 12, 2);
            $table->timestamps();
            $table->unique(['invoice_id', 'inventory_item_id']);
            $table->index(['invoice_id', 'received_quantity']);
        });

        Schema::table('service_center_inventory_movements', function (Blueprint $table) {
            $table->foreignId('supplier_invoice_item_id')->nullable()
                ->after('supplier_id')
                ->constrained('service_center_supplier_invoice_items')
                ->nullOnDelete();
            $table->index(['supplier_invoice_item_id', 'created_at'], 'inventory_movement_invoice_item_idx');
        });
    }

    public function down(): void
    {
        Schema::table('service_center_inventory_movements', function (Blueprint $table) {
            $table->dropIndex('inventory_movement_invoice_item_idx');
            $table->dropConstrainedForeignId('supplier_invoice_item_id');
        });

        Schema::dropIfExists('service_center_supplier_invoice_items');
    }
};
