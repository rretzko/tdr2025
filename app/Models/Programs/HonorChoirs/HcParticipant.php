<?php

namespace App\Models\Programs\HonorChoirs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HcParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\\App\Models\HonorChoirs\HcParticipantFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'full_name',
        'hc_event_id',
        'instrument_id',
        'instrument_name',
        'last_name',
        'school_id',
        'school_name',
    ];
}
