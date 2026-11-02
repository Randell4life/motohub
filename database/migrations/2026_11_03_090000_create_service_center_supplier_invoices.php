<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tableName = 'service_center_supplier_invoices';

        // A previous attempt may have created the table before MySQL rejected
        // Laravel's automatically generated (too long) unique-index name.
        if (! Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
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
            });
        }

        $existingIndexes = collect(Schema::getIndexes($tableName))->pluck('name')->all();

        if (! in_array('sci_supplier_invoice_no_uq', $existingIndexes, true)) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unique(['supplier_id', 'invoice_number'], 'sci_supplier_invoice_no_uq');
            });
        }

        if (! in_array('sci_center_issue_date_idx', $existingIndexes, true)) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->index(['service_center_id', 'issue_date'], 'sci_center_issue_date_idx');
            });
        }

        if (! in_array('sci_center_due_date_idx', $existingIndexes, true)) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->index(['service_center_id', 'due_date'], 'sci_center_due_date_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_center_supplier_invoices');
    }
};
