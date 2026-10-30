<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->uuid('public_token')->nullable()->unique();
            $table->foreignId('service_center_bay_id')->nullable()->constrained('service_center_bays')->nullOnDelete();
            $table->foreignId('service_center_technician_id')->nullable()->constrained('service_center_technicians')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_center_technician_id');
            $table->dropConstrainedForeignId('service_center_bay_id');
            $table->dropUnique(['public_token']);
            $table->dropColumn('public_token');
        });
    }
};
