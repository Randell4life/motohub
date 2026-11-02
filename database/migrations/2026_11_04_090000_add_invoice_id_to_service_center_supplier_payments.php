<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $table = 'service_center_supplier_payments';

        if (! Schema::hasColumn($table, 'invoice_id')) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedBigInteger('invoice_id')->nullable()->after('supplier_id');
            });
        }

        $foreignKeys = collect(Schema::getForeignKeys($table));
        if (! $foreignKeys->contains(fn (array $key) => in_array('invoice_id', $key['columns'] ?? [], true))) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->foreign('invoice_id', 'scsp_invoice_fk')
                    ->references('id')->on('service_center_supplier_invoices')->nullOnDelete();
            });
        }

        $indexes = collect(Schema::getIndexes($table))->pluck('name')->all();
        if (! in_array('scsp_invoice_paid_idx', $indexes, true)) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->index(['invoice_id', 'paid_at'], 'scsp_invoice_paid_idx');
            });
        }
    }

    public function down(): void
    {
        $table = 'service_center_supplier_payments';
        if (Schema::hasColumn($table, 'invoice_id')) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropForeign('scsp_invoice_fk');
                $blueprint->dropIndex('scsp_invoice_paid_idx');
                $blueprint->dropColumn('invoice_id');
            });
        }
    }
};
