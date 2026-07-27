<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'top_bar_phone',
        'top_bar_email',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'hero_eyebrow',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'hero_cta_primary',
        'hero_cta_secondary',
        'about_title',
        'about_content',
        'about_image',
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
        'contact_label',
        'contact_title',
        'contact_title_highlight',
        'contact_subtitle',
        'contact_email',
        'contact_phone',
        'contact_address',
        'contact_website',
        'footer_about',
        'newsletter_title',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }
}
