<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->decimal('quoted_labor_cost', 10, 2)->nullable()->after('total_cost');
            $table->decimal('quoted_parts_cost', 10, 2)->nullable()->after('quoted_labor_cost');
            $table->decimal('quoted_total_cost', 10, 2)->nullable()->after('quoted_parts_cost');
            $table->string('customer_approval_status', 24)->default('not_requested')->after('quoted_total_cost');
            $table->timestamp('quote_sent_at')->nullable()->after('customer_approval_status');
            $table->timestamp('customer_approved_at')->nullable()->after('quote_sent_at');
            $table->timestamp('customer_declined_at')->nullable()->after('customer_approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'quoted_labor_cost',
                'quoted_parts_cost',
                'quoted_total_cost',
                'customer_approval_status',
                'quote_sent_at',
                'customer_approved_at',
                'customer_declined_at',
            ]);
        });
    }
};
