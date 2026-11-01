<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_prizes', function (Blueprint $table) {
            $table->id();
            $table->string('period_type', 10);
            $table->date('period_start');
            $table->unsignedTinyInteger('rank');
            $table->foreignId('winner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('awarded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 120);
            $table->text('description')->nullable();
            $table->unsignedInteger('points_awarded')->default(0);
            $table->timestamp('awarded_at')->nullable();
            $table->timestamps();

            $table->unique(['period_type', 'period_start', 'rank'], 'reward_prizes_period_rank_unique');
            $table->index(['winner_user_id', 'awarded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_prizes');
    }
};
