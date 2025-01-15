<?php

namespace App\Models\Schools\Teachers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'supervisor_email',
        'supervisor_name',
        'supervisor_phone',
        'teacher_id'
    ];
}
