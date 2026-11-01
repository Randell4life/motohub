<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('service_center_bookings', function (Blueprint $table) {
            $table->unsignedInteger('points_used')->default(0);
            $table->decimal('points_discount', 10, 2)->default(0);
        });
    }
    public function down(): void
    {
        Schema::table('service_center_bookings', function (Blueprint $table) {
            $table->dropColumn(['points_used', 'points_discount']);
        });
    }
};
