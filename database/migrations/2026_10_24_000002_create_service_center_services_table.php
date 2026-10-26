<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_center_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained('service_centers')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('price_type', 20)->default('fixed'); // fixed, from, quote
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['service_center_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_center_services');
    }
};
