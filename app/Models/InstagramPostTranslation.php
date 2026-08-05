<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstagramPostTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'instagram_post_id',
        'locale',
        'alt',
    ];

    public function instagramPost(): BelongsTo
    {
        return $this->belongsTo(InstagramPost::class);
    }
}
