<?php

namespace App\Models\Libraries\Items\Components;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibTitle extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'title'];
}
