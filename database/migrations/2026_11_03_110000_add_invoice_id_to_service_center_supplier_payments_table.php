<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = 'service_center_supplier_payments';

        if (! Schema::hasColumn($tableName, 'invoice_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('invoice_id')->nullable();

                $table->foreign('invoice_id', 'scsp_invoice_fk')
                    ->references('id')
                    ->on('service_center_supplier_invoices')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $tableName = 'service_center_supplier_payments';

        if (Schema::hasColumn($tableName, 'invoice_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign('scsp_invoice_fk');
                $table->dropColumn('invoice_id');
            });
        }
    }
};
