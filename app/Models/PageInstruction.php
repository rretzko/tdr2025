<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'header',
        'instructions',
    ];
}
