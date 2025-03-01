<?php

namespace App\Models\Libraries\Items;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'lib_subtitle_id',
        'lib_title_id',
    ];
}
