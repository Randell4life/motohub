<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motorcycle_service_records', function (Blueprint $table) {
            $table->foreignId('motorcycle_id')
                ->after('id')
                ->constrained('motorcycles')
                ->cascadeOnDelete();

            $table->date('service_date');
            $table->unsignedInteger('mileage')->nullable();
            $table->string('category', 100);
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('motorcycle_service_records', function (Blueprint $table) {
            $table->dropForeign(['motorcycle_id']);

            $table->dropColumn([
                'motorcycle_id',
                'service_date',
                'mileage',
                'category',
                'title',
                'description',
                'cost',
                'notes',
            ]);
        });
    }
};