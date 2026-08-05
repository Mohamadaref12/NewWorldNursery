<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'feature_id',
        'locale',
        'title',
        'description',
    ];

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }
}
