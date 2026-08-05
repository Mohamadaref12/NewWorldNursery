<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'location_id',
        'locale',
        'name',
        'city',
        'country',
        'address',
        'working_hours',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
