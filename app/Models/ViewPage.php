<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'controller',
        'method',
        'page_name',
        'header',
    ];
}
