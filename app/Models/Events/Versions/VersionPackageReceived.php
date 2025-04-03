<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersionPackageReceived extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'school_id',
        'received',
        'user_id',
    ];
}
