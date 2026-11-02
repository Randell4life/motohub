<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('service_center_technicians', function (Blueprint $table) {
            $table->string('pay_type', 30)->default('percentage_labor')->after('specialty');
            $table->decimal('pay_rate', 10, 2)->default(0)->after('pay_type');
        });

        Schema::create('service_center_technician_payroll', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_center_technician_id')->constrained('service_center_technicians')->cascadeOnDelete();
            $table->foreignId('work_order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('rate_type', 30);
            $table->decimal('rate_value', 10, 2);
            $table->decimal('labor_base', 12, 2)->default(0);
            $table->decimal('amount', 12, 2);
            $table->string('status', 20)->default('pending');
            $table->timestamp('earned_at');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['service_center_id', 'earned_at']);
            $table->index(['service_center_technician_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_center_technician_payroll');

        Schema::table('service_center_technicians', function (Blueprint $table) {
            $table->dropColumn(['pay_type', 'pay_rate']);
        });
    }
};
