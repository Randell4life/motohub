<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_offers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transport_request_id')
                ->constrained('transport_requests')
                ->cascadeOnDelete();

            $table->foreignId('partner_id')
                ->nullable()
                ->constrained('partners')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('price', 10, 2);
            $table->unsignedSmallInteger('estimated_days')->default(1);
            $table->text('message')->nullable();

            $table->string('status')->default('pending');
            $table->timestamp('accepted_at')->nullable();

            $table->timestamps();

            $table->index(['transport_request_id', 'status']);
            $table->index(['partner_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_offers');
    }
};
