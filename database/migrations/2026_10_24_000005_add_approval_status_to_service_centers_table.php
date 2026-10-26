<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_centers', function (Blueprint $table) {
            $table->string('approval_status', 20)
                ->default('pending')
                ->after('is_verified');

            $table->index('approval_status', 'sc_approval_status_idx');
        });

        // Preserve visibility for previously verified active workshops.
        // Unverified existing workshops go into the admin review queue.
        DB::table('service_centers')
            ->where('is_verified', true)
            ->where('is_active', true)
            ->update(['approval_status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('service_centers', function (Blueprint $table) {
            $table->dropIndex('sc_approval_status_idx');
            $table->dropColumn('approval_status');
        });
    }
};
