<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motorcycle_service_records', function (Blueprint $table) {
            $table->foreignId('service_center_id')->nullable()->after('motorcycle_id')
                ->constrained('service_centers')->nullOnDelete();
            $table->string('verification_status', 20)->default('unverified')->after('notes');
            $table->timestamp('verified_at')->nullable()->after('verification_status');
            $table->foreignId('verified_by_user_id')->nullable()->after('verified_at')
                ->constrained('users')->nullOnDelete();
            $table->text('verification_note')->nullable()->after('verified_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('motorcycle_service_records', function (Blueprint $table) {
            $table->dropForeign(['service_center_id']);
            $table->dropForeign(['verified_by_user_id']);
            $table->dropColumn([
                'service_center_id',
                'verification_status',
                'verified_at',
                'verified_by_user_id',
                'verification_note',
            ]);
        });
    }
};
