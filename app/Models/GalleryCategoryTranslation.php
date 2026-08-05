<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'gallery_category_id',
        'locale',
        'name',
        'slug',
    ];

    public function galleryCategory(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class);
    }
}
