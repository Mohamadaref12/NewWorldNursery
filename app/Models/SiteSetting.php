<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use App\Traits\InteractsWithEnArTranslations;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use InteractsWithEnArTranslations;
    use Translatable;

    public array $translatedAttributes = [
        'site_name',
        'hero_eyebrow',
        'hero_title',
        'hero_subtitle',
        'hero_cta_primary',
        'hero_cta_secondary',
        'about_title',
        'about_content',
        'about_cta',
        'about_label',
        'about_highlight',
        'locations_label',
        'locations_title',
        'locations_title_highlight',
        'locations_subtitle',
        'programs_label',
        'programs_title',
        'programs_title_highlight',
        'programs_subtitle',
        'gallery_label',
        'gallery_title',
        'gallery_title_highlight',
        'gallery_subtitle',
        'gallery_cta',
        'moments_label',
        'moments_title',
        'moments_cta',
        'contact_label',
        'contact_title',
        'contact_title_highlight',
        'contact_subtitle',
        'contact_address',
        'footer_about',
        'newsletter_title',
    ];

    protected $fillable = [
        'top_bar_phone',
        'top_bar_email',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'hero_image',
        'about_image',
        'contact_email',
        'contact_phone',
        'contact_website',
    ];

    public function translationModelClass(): string
    {
        return SiteSettingTranslation::class;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->localizedDisplayValue('site_name', 'Site Settings');
    }

    public static function current(): self
    {
        $settings = static::query()->with('translations')->first();

        if ($settings) {
            return $settings;
        }

        $settings = static::query()->create([]);
        $settings->load('translations');

        return $settings;
    }
}
