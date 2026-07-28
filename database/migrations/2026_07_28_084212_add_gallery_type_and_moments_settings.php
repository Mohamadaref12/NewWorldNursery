<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->string('type', 20)->default('instagram')->after('id');
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('moments_label')->nullable()->after('gallery_cta');
            $table->string('moments_title')->nullable()->after('moments_label');
            $table->string('moments_cta')->nullable()->after('moments_title');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['moments_label', 'moments_title', 'moments_cta']);
        });
    }
};
