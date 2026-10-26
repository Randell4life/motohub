<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('part_sales', function (Blueprint $table) {
            $table->foreignId('part_id')
                ->nullable()
                ->constrained('parts')
                ->nullOnDelete();

            $table->string('part_title')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->timestamp('sold_at')->nullable()->index();
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('part_sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('part_id');

            $table->dropColumn([
                'part_title',
                'quantity',
                'unit_price',
                'unit_cost',
                'total_amount',
                'total_cost',
                'sold_at',
                'notes',
            ]);
        });
    }
};