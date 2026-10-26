<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->string('title')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->decimal('price', 12, 2)->nullable();
            $table->unsignedInteger('mileage')->nullable();
            $table->string('condition')->nullable();
            $table->string('location')->nullable();
            $table->string('image_path')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
        });

        // არსებული ჩანაწერებისთვის სათაურისა და slug-ის შევსება
        DB::table('motorcycles')
            ->whereNull('title')
            ->update([
                'title' => DB::raw("CONCAT(brand, ' ', model)"),
            ]);

        DB::table('motorcycles')
            ->whereNull('slug')
            ->update([
                'slug' => DB::raw("CONCAT('motorcycle-', id)"),
            ]);
    }

    public function down(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropConstrainedForeignId('partner_id');
            $table->dropColumn([
                'title',
                'slug',
                'price',
                'mileage',
                'condition',
                'location',
                'image_path',
                'status',
                'is_featured',
                'published_at',
            ]);
        });
    }
};