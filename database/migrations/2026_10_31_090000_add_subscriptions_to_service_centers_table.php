<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_centers', function (Blueprint $table) {
            $table->string('subscription_plan', 20)->default('start')->after('approval_status');
            $table->string('subscription_status', 24)->default('active')->after('subscription_plan');
            $table->decimal('subscription_price', 10, 2)->default(0)->after('subscription_status');
            $table->timestamp('subscription_started_at')->nullable()->after('subscription_price');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_started_at');
            $table->index(['subscription_plan', 'subscription_status'], 'sc_subscription_plan_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('service_centers', function (Blueprint $table) {
            $table->dropIndex('sc_subscription_plan_status_idx');
            $table->dropColumn([
                'subscription_plan',
                'subscription_status',
                'subscription_price',
                'subscription_started_at',
                'subscription_ends_at',
            ]);
        });
    }
};
