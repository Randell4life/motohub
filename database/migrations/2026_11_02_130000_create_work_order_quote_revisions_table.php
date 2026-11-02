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

        // Preserve quotes that were sent before revision history was introduced.
        DB::table('work_orders')
            ->whereNotNull('quote_sent_at')
            ->orderBy('id')
            ->chunkById(200, function ($orders) {
                foreach ($orders as $order) {
                    DB::table('work_order_quote_revisions')->insert([
                        'work_order_id' => $order->id,
                        'revision_number' => 1,
                        'type' => 'initial',
                        'status' => in_array($order->customer_approval_status, ['approved', 'declined'], true)
                            ? $order->customer_approval_status
                            : 'pending',
                        'diagnosis' => $order->diagnosis,
                        'work_description' => $order->work_description,
                        'labor_cost' => $order->quoted_labor_cost ?? 0,
                        'parts_cost' => $order->quoted_parts_cost ?? 0,
                        'total_cost' => $order->quoted_total_cost ?? 0,
                        'labor_increment' => $order->quoted_labor_cost ?? 0,
                        'parts_increment' => $order->quoted_parts_cost ?? 0,
                        'additions' => json_encode([]),
                        'sent_at' => $order->quote_sent_at,
                        'responded_at' => $order->customer_approved_at ?? $order->customer_declined_at,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_quote_revisions');
    }
};
