<?php

namespace App\Models\Events\Versions\Participations;

use App\Models\Events\Versions\Version;
use App\Models\Schools\Teacher;
use App\Models\Students\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'program_name',
        'ref',
        'school_id',
        'student_id',
        'status',
        'teacher_id',
        'version_id',
        'voice_part_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
