<?php

namespace App\Http\Resources;

use App\Support\ImageUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\SiteSetting */
class SiteSettingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'site_name' => $this->site_name,
            'top_bar_phone' => $this->top_bar_phone,
            'top_bar_email' => $this->top_bar_email,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'twitter_url' => $this->twitter_url,
            'youtube_url' => $this->youtube_url,
            'hero' => [
                'eyebrow' => $this->hero_eyebrow,
                'title' => $this->hero_title,
                'subtitle' => $this->hero_subtitle,
                'image' => ImageUrl::make($this->hero_image),
                'cta_primary' => $this->hero_cta_primary,
                'cta_secondary' => $this->hero_cta_secondary,
            ],
            'about' => [
                'label' => $this->about_label,
                'title' => $this->about_title,
                'highlight' => $this->about_highlight,
                'content' => $this->about_content,
                'image' => ImageUrl::make($this->about_image),
                'cta' => $this->about_cta,
            ],
            'locations' => [
                'label' => $this->locations_label,
                'title' => $this->locations_title,
                'title_highlight' => $this->locations_title_highlight,
                'subtitle' => $this->locations_subtitle,
            ],
            'programs' => [
                'label' => $this->programs_label,
                'title' => $this->programs_title,
                'title_highlight' => $this->programs_title_highlight,
                'subtitle' => $this->programs_subtitle,
            ],
            'gallery' => [
                'label' => $this->gallery_label,
                'title' => $this->gallery_title,
                'title_highlight' => $this->gallery_title_highlight,
                'subtitle' => $this->gallery_subtitle,
                'cta' => $this->gallery_cta,
            ],
            'moments' => [
                'label' => $this->moments_label,
                'title' => $this->moments_title,
                'cta' => $this->moments_cta,
            ],
            'contact' => [
                'label' => $this->contact_label,
                'title' => $this->contact_title,
                'title_highlight' => $this->contact_title_highlight,
                'subtitle' => $this->contact_subtitle,
                'email' => $this->contact_email,
                'phone' => $this->contact_phone,
                'address' => $this->contact_address,
                'website' => $this->contact_website,
            ],
            'footer_about' => $this->footer_about,
            'newsletter_title' => $this->newsletter_title,
        ];
    }
}
