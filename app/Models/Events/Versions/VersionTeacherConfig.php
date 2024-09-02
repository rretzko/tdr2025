<?php

namespace App\Models\Events\Versions;

use Illuminate\Database\Eloquent\Model;

class VersionTeacherConfig extends Model
{
    protected $fillable = [
        'epayment_student',
        'teacher_id',
        'version_id',
    ];
}
