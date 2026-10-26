<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_center_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained('service_centers')->cascadeOnDelete();
            $table->foreignId('service_center_service_id')->nullable()->constrained('service_center_services')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('service_name'); // preserve what was booked, even if service is edited later
            $table->decimal('price_at_booking', 10, 2)->nullable();
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->string('customer_name');
            $table->string('customer_phone', 40);
            $table->string('motorcycle')->nullable();
            $table->dateTime('scheduled_at');
            $table->text('notes')->nullable();
            $table->string('status', 24)->default('pending'); // pending, confirmed, declined, cancelled, completed
            $table->timestamps();

$table->index(
    ['service_center_id', 'scheduled_at', 'status'],
    'scb_center_time_status_idx'
);

$table->index(
    ['user_id', 'scheduled_at'],
    'scb_user_time_idx'
);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_center_bookings');
    }
};
