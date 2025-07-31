<?php

namespace App\Models\Libraries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibPerusalCopy extends Model
{
    use HasFactory;

    protected $fillable = [
        'library_id',
        'teacher_id',
        'book',
        'cassette',
        'cd',
        'digital',
        'dvd',
        'medley',
        'octavo',
        'vinyl',
        'useItemId', //for location value
    ];
}
