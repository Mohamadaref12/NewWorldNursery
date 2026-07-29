<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instagram_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_id')->nullable();
            $table->text('app_secret')->nullable();
            $table->unsignedInteger('sync_limit')->default(12);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instagram_settings');
    }
};
