<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // თუ ცხრილი არსებობს, მას არ შევეხოთ.
        if (Schema::hasTable('rental_scooters')) {
            return;
        }

        Schema::create('rental_scooters', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->string('brand');
            $table->string('model');

            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedSmallInteger('engine_cc')->nullable();

            $table->decimal('daily_rate', 10, 2);

            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();

            $table->string('status', 20)->default('active');

            $table->timestamps();

            $table->index(['status', 'daily_rate']);
        });
    }

    public function down(): void
    {
        // არსებულ მონაცემებს შემთხვევით რომ არ ვავნოთ,
        // ეს შესწორების migration ცხრილს ავტომატურად არ წაშლის.
    }
};