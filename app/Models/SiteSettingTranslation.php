<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteSettingTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'site_setting_id',
        'locale',
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

    public function siteSetting(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class);
    }
}
