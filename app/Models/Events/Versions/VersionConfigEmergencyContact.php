<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Model;

class VersionConfigEmergencyContact extends Model
{
    protected $fillable = [
        'ec_email',
        'ec_name',
        'ec_phone_mobile',
        'version_id',
    ];
}
