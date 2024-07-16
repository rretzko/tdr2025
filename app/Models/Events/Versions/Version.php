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
        'epayment_student',
        'epayment_teacher',
        'event_id',
        'fee_participation',
        'fee_on_site_registration',
        'fee_registration',
        'name',
        'pitch_files_student',
        'pitch_files_teacher',
        'short_name',
        'senior_class_of',
        'status',
        'upload_type',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}