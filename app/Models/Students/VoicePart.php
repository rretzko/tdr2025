<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Model;

class VoicePart extends Model
{
    protected $fillable = [
        'descr',
        'abbr',
        'order_by',
    ];
}
