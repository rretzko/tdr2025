<?php

namespace App\Models\Events\Versions;

use App\Models\Events\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Version extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'short_name',
        'senior_class_of',
        'status',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
