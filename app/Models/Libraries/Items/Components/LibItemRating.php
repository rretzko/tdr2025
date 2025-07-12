<?php

namespace App\Models\Libraries\Items\Components;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibItemRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'library_id',
        'lib_item_id',
        'teacher_id',
        'rating',
        'level',
        'difficulty',
        'comments',
    ];
}
