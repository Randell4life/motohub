<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // The users_name_unique index is created by the earlier migration.
    }

    public function down(): void
    {
        // Keep the index managed by the earlier migration.
    }
};