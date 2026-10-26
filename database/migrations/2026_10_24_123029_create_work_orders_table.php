<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();

            // შეკვეთის უნიკალური ნომერი
            $table->string('order_number')->unique();

            // არსებული ჯავშანი და სერვისცენტრი
            $table->foreignId('service_booking_id')
                ->nullable()
                ->unique()
                ->constrained('service_center_bookings')
                ->nullOnDelete();

            $table->foreignId('service_center_id')
                ->constrained('service_centers')
                ->cascadeOnDelete();

            // პასუხისმგებელი ხელოსანი
            $table->foreignId('assigned_technician_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // შეკვეთის სტატუსი
            $table->string('status', 32)->default('received');

            // მოტოციკლის მიღებისას არსებული მდგომარეობა
            $table->unsignedInteger('intake_odometer')->nullable();
            $table->text('intake_condition')->nullable();
            $table->json('intake_photos')->nullable();

            // დიაგნოსტიკა და შესრულებული სამუშაო
            $table->text('diagnosis')->nullable();
            $table->text('work_description')->nullable();
            $table->text('notes')->nullable();

            // ხარჯები
            $table->decimal('labor_cost', 10, 2)->default(0);
            $table->decimal('parts_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);

            // დასრულებისა და კლიენტისთვის გადაცემის დრო
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('handed_over_at')->nullable();

            $table->timestamps();

            $table->index(['service_center_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};