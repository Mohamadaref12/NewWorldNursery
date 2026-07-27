<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('hero_eyebrow')->nullable()->after('youtube_url');
            $table->string('about_label')->nullable()->after('about_cta');
            $table->string('about_highlight')->nullable()->after('about_label');
            $table->string('locations_label')->nullable()->after('about_highlight');
            $table->string('locations_title_highlight')->nullable()->after('locations_title');
            $table->string('programs_label')->nullable()->after('locations_subtitle');
            $table->string('programs_title_highlight')->nullable()->after('programs_title');
            $table->string('gallery_label')->nullable()->after('programs_subtitle');
            $table->string('gallery_title_highlight')->nullable()->after('gallery_title');
            $table->string('contact_label')->nullable()->after('gallery_cta');
            $table->string('contact_title_highlight')->nullable()->after('contact_title');
        });

        Schema::table('features', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->string('icon_color')->default('#D4EDDA')->after('icon');
            $table->string('icon_image')->nullable()->after('icon_color');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->string('country')->nullable()->after('city');
            $table->string('badge_color')->default('#2E9E94')->after('country');
            $table->string('working_hours')->nullable()->after('email');
            $table->string('visit_url')->nullable()->after('map_url');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('color');
            $table->string('icon_color')->default('#C8E6C9')->after('icon');
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('child_age')->nullable()->after('program');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_eyebrow',
                'about_label',
                'about_highlight',
                'locations_label',
                'locations_title_highlight',
                'programs_label',
                'programs_title_highlight',
                'gallery_label',
                'gallery_title_highlight',
                'contact_label',
                'contact_title_highlight',
            ]);
        });

        Schema::table('features', function (Blueprint $table) {
            $table->dropColumn(['description', 'icon_color', 'icon_image']);
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['country', 'badge_color', 'working_hours', 'visit_url']);
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['icon', 'icon_color']);
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn('child_age');
        });
    }
};
