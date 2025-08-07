<?php

namespace App\Models\Libraries\Items\Components;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibItemDoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'library_id',
        'lib_item_id',
        'url',
        'user_id',
    ];
}
