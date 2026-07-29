<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instagram_connections', function (Blueprint $table) {
            $table->id();
            $table->string('instagram_user_id');
            $table->string('username')->nullable();
            $table->string('page_id')->nullable();
            $table->string('page_name')->nullable();
            $table->text('access_token');
            $table->timestamp('token_expires_at')->nullable();
            $table->unsignedInteger('sync_limit')->default(12);
            $table->timestamp('last_synced_at')->nullable();
            $table->string('last_sync_status')->nullable();
            $table->text('last_sync_message')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->string('instagram_media_id')->nullable()->unique()->after('type');
            $table->string('permalink')->nullable()->after('alt');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropColumn(['instagram_media_id', 'permalink']);
        });

        Schema::dropIfExists('instagram_connections');
    }
};
