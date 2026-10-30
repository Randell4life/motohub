<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['parts', 'part_listings'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('part_type', 30)->nullable()->index();
                $table->string('motorcycle_brand', 100)->nullable()->index();
                $table->string('motorcycle_model', 120)->nullable()->index();
                $table->unsignedSmallInteger('motorcycle_year')->nullable()->index();
                $table->unsignedInteger('engine_cc')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        foreach (['parts', 'part_listings'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn([
                    'part_type',
                    'motorcycle_brand',
                    'motorcycle_model',
                    'motorcycle_year',
                    'engine_cc',
                ]);
            });
        }
    }
};
