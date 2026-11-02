<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_center_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category', 40)->default('other');
            $table->string('description', 180);
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->string('reference', 120)->nullable();
            $table->timestamps();

            $table->index(['service_center_id', 'expense_date']);
            $table->index(['service_center_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_center_expenses');
    }
};
