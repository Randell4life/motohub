<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_center_supplier_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('service_center_suppliers')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_number', 120);
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->string('file_path')->nullable();
            $table->string('notes', 1000)->nullable();
            $table->timestamps();
            $table->unique(['supplier_id', 'invoice_number']);
            $table->index(['service_center_id', 'issue_date']);
            $table->index(['service_center_id', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_center_supplier_invoices');
    }
};
