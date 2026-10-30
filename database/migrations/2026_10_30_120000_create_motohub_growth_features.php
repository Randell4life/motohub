<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motorcycle_maintenance_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('motorcycle_id')->constrained()->cascadeOnDelete();
            $table->string('title', 160);
            $table->date('due_date')->nullable();
            $table->unsignedInteger('due_mileage')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'completed_at', 'due_date']);
        });

        Schema::create('motorcycle_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('motorcycle_id')->constrained()->cascadeOnDelete();
            $table->string('category', 80);
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->string('vendor', 160)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'expense_date']);
        });

        Schema::create('motorcycle_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('motorcycle_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'motorcycle_id']);
        });

        Schema::create('part_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('source', 20);
            $table->unsignedBigInteger('part_id');
            $table->timestamps();
            $table->unique(['user_id', 'source', 'part_id']);
            $table->index(['source', 'part_id']);
        });

        Schema::create('moto_clubs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 140);
            $table->string('slug', 180)->unique();
            $table->string('city', 100)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            $table->index(['is_public', 'city']);
        });

        Schema::create('moto_club_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained('moto_clubs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 20)->default('member');
            $table->string('status', 20)->default('active');
            $table->timestamps();
            $table->unique(['club_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });

        Schema::create('moto_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained('moto_clubs')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title', 160);
            $table->text('description')->nullable();
            $table->string('location', 180);
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->timestamps();
            $table->index(['club_id', 'starts_at']);
        });

        Schema::create('moto_event_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('moto_events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('going');
            $table->timestamps();
            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moto_event_attendees');
        Schema::dropIfExists('moto_events');
        Schema::dropIfExists('moto_club_members');
        Schema::dropIfExists('moto_clubs');
        Schema::dropIfExists('part_favorites');
        Schema::dropIfExists('motorcycle_favorites');
        Schema::dropIfExists('motorcycle_expenses');
        Schema::dropIfExists('motorcycle_maintenance_reminders');
    }
};
