<?php

namespace App\Models\Libraries\Items\Components;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibDigital extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'lib_item_id',
        'url',
        'user_id',
    ];
}
