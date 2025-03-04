<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoregistrationManagerMailingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'mailing_address',
        'version_id',
        'version_participant_id'
    ];
}
