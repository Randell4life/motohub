<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // თუ ცხრილი უკვე არსებობს, ხელახლა ნუ შევქმნით.
        if (Schema::hasTable('rental_bookings')) {
            return;
        }

        Schema::create('rental_bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rental_scooter_id')
                ->constrained('rental_scooters')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->date('start_date');
            $table->date('end_date');

            $table->unsignedInteger('days');

            $table->decimal('daily_rate', 10, 2);
            $table->decimal('total_price', 12, 2);

            $table->string('customer_name');
            $table->string('phone', 30);
            $table->text('notes')->nullable();

            $table->string('status', 20)->default('pending');
            $table->text('admin_note')->nullable();

            $table->timestamps();

$table->index(
    ['rental_scooter_id', 'status', 'start_date', 'end_date'],
    'rental_booking_availability_idx'
);

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_bookings');
    }
};