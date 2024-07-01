<?php

namespace App\Models;

use App\Models\Schools\School;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolStudent extends Model
{
    protected $table = 'school_student';

    protected $fillable = [
        'school_id',
        'student_id',
        'active',
    ];

    public $timestamps = false;

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
