<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // მომხმარებლის ავატარი
        if (! Schema::hasColumn('users', 'avatar_path')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('avatar_path')->nullable();
            });
        }

        // Community პოსტის ფოტო
        if (! Schema::hasColumn('community_posts', 'image_path')) {
            Schema::table('community_posts', function (Blueprint $table) {
                $table->string('image_path')->nullable();
            });
        }

        // შეტყობინებების ცხრილი
        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index([
                    'notifiable_type',
                    'notifiable_id',
                    'read_at',
                ]);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::drop('notifications');
        }

        if (Schema::hasColumn('community_posts', 'image_path')) {
            Schema::table('community_posts', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }

        if (Schema::hasColumn('users', 'avatar_path')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('avatar_path');
            });
        }
    }
};
