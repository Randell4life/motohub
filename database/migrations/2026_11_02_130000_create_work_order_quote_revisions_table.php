<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_order_quote_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->unsignedInteger('revision_number');
            $table->string('type', 24)->default('initial');
            $table->string('status', 24)->default('pending');
            $table->text('diagnosis')->nullable();
            $table->text('work_description')->nullable();
            $table->decimal('labor_cost', 10, 2)->default(0);
            $table->decimal('parts_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->decimal('labor_increment', 10, 2)->default(0);
            $table->decimal('parts_increment', 10, 2)->default(0);
            $table->json('additions')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            $table->unique(['work_order_id', 'revision_number']);
            $table->index(['work_order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_quote_revisions');
    }
};
