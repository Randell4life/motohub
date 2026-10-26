
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_bookings', function (Blueprint $table) {
            $table->id();

            $table->string('service_type');
            $table->string('motorcycle_brand')->nullable();
            $table->string('motorcycle_model')->nullable();
            $table->unsignedSmallInteger('motorcycle_year')->nullable();

            $table->text('problem_description');

            $table->string('customer_name');
            $table->string('phone');
            $table->string('email')->nullable();

            $table->date('preferred_date')->nullable();
            $table->string('status')->default('new');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_bookings');
    }
};
