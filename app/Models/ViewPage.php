<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewPage extends Model
{
    protected $fillable = [
        'controller',
        'method',
        'page_name',
        'header',
    ];
}
