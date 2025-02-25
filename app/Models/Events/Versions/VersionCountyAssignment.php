<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersionCountyAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'county_id',
        'version_id',
        'user_id',
    ];
}
