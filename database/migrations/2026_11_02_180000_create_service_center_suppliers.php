<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_center_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete();
            $table->string('name', 180);
            $table->string('contact_person', 160)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('email', 180)->nullable();
            $table->string('tax_id', 80)->nullable();
            $table->string('address', 255)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['service_center_id', 'is_active']);
        });

        Schema::create('service_center_supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('service_center_suppliers')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method', 30)->default('cash');
            $table->string('reference', 120)->nullable();
            $table->string('note', 255)->nullable();
            $table->timestamp('paid_at');
            $table->timestamps();
            $table->index(['service_center_id', 'paid_at']);
            $table->index(['supplier_id', 'paid_at']);
        });

        Schema::table('service_center_inventory_movements', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->after('inventory_item_id')
                ->constrained('service_center_suppliers')->nullOnDelete();
            $table->string('supplier_reference', 120)->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('service_center_inventory_movements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supplier_id');
            $table->dropColumn('supplier_reference');
        });
        Schema::dropIfExists('service_center_supplier_payments');
        Schema::dropIfExists('service_center_suppliers');
    }
};
