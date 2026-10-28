<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('part_listings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            $table->string('condition', 20); // new ან used
            $table->string('part_number')->nullable();
            $table->string('compatible_motorcycle')->nullable();
            $table->text('description')->nullable();

            $table->decimal('price', 12, 2);
            $table->string('image_path')->nullable();

            // pending, published, rejected, draft
            $table->string('status', 20)->default('pending');

            $table->timestamps();

            $table->index(['condition', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part_listings');
    }
};
