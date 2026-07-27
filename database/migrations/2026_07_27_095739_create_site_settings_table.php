<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('New World Nursery');
            $table->string('top_bar_phone')->nullable();
            $table->string('top_bar_email')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_cta_primary')->default('ENQUIRE NOW');
            $table->string('hero_cta_secondary')->default('VISIT A NURSERY');
            $table->string('about_title')->nullable();
            $table->text('about_content')->nullable();
            $table->string('about_image')->nullable();
            $table->string('about_cta')->default('READ MORE');
            $table->string('locations_title')->default('Find us across the region');
            $table->string('locations_subtitle')->nullable();
            $table->string('programs_title')->default('Learning by age & stage');
            $table->string('programs_subtitle')->nullable();
            $table->string('gallery_title')->default('Follow Our Journey');
            $table->string('gallery_subtitle')->nullable();
            $table->string('gallery_cta')->default('ENROLL WITH US NOW');
            $table->string('contact_title')->default('Talk with Our Team');
            $table->string('contact_subtitle')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('contact_website')->nullable();
            $table->text('footer_about')->nullable();
            $table->string('newsletter_title')->default('Come see New World in action');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
