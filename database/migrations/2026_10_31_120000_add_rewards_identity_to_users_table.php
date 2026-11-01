<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 20)->nullable()->unique();
            $table->foreignId('referred_by_user_id')->nullable()->after('referral_code')->constrained('users')->nullOnDelete();
        });

        Schema::create('reward_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('points');
            $table->string('type', 32);
            $table->string('description', 180);
            $table->string('action_key', 120)->unique();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('reward_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('points');
            $table->decimal('value_gel', 10, 2);
            $table->string('reward_type', 32);
            $table->string('status', 24)->default('pending');
            $table->text('notes')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        Schema::create('reward_settings', function (Blueprint $table) {
            $table->string('key', 80)->primary();
            $table->string('value', 255);
            $table->timestamps();
        });

        DB::table('reward_settings')->insert([
            ['key' => 'points_per_gel', 'value' => '100', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'minimum_redemption_points', 'value' => '500', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'signup_points', 'value' => '100', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'first_post_points', 'value' => '150', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'referral_signup_points', 'value' => '100', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'referral_first_post_points', 'value' => '150', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_settings');
        Schema::dropIfExists('reward_redemptions');
        Schema::dropIfExists('reward_transactions');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('referred_by_user_id');
            $table->dropUnique(['referral_code']);
            $table->dropColumn('referral_code');
        });
    }
};
