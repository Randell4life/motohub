<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_centers', function (Blueprint $table) {
            $table->string('subscription_requested_plan', 20)->nullable()->after('subscription_ends_at');
            $table->string('subscription_request_note', 500)->nullable()->after('subscription_requested_plan');
            $table->timestamp('subscription_requested_at')->nullable()->after('subscription_request_note');
        });
    }

    public function down(): void
    {
        Schema::table('service_centers', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_requested_plan',
                'subscription_request_note',
                'subscription_requested_at',
            ]);
        });
    }
};
