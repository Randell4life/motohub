<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_requests', function (Blueprint $table) {
            $table->id();

            $table->string('request_number')->unique();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('motorcycle_brand');
            $table->string('motorcycle_model');
            $table->unsignedSmallInteger('motorcycle_year')->nullable();

            $table->string('pickup_city');
            $table->string('pickup_address')->nullable();
            $table->string('destination_city');
            $table->string('destination_address')->nullable();

            $table->date('preferred_date')->nullable();
            $table->text('motorcycle_details')->nullable();

            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();

            $table->string('status')->default('pending');
            $table->text('customer_notes')->nullable();

            $table->timestamps();

            $table->index(['status', 'preferred_date']);
            $table->index(['pickup_city', 'destination_city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_requests');
    }
};
