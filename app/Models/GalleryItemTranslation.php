<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryItemTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'gallery_item_id',
        'locale',
        'alt',
    ];

    public function galleryItem(): BelongsTo
    {
        return $this->belongsTo(GalleryItem::class);
    }
}
