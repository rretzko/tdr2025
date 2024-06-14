<?php

namespace App\Models\Ensembles\Members;

use App\Models\Ensembles\Ensemble;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $table = 'ensemble_members';

    protected $fillable = [
        'ensemble_id',
        'student_id',
        'school_year',
        'status',
    ];

    public function ensemble(): BelongsTo
    {
        return $this->belongsTo(Ensemble::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
