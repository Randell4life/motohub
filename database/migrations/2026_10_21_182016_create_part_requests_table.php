
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('part_requests', function (Blueprint $table) {
            $table->id();

            $table->string('part_name');
            $table->string('motorcycle_brand')->nullable();
            $table->string('motorcycle_model')->nullable();
            $table->unsignedSmallInteger('motorcycle_year')->nullable();

            $table->string('customer_name');
            $table->string('phone');
            $table->string('email')->nullable();

            $table->text('details')->nullable();

            $table->string('status')->default('new');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part_requests');
    }
};
