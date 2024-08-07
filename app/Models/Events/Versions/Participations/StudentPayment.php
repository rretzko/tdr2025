<?php

namespace App\Models\Events\Versions\Participations;

use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'candidate_id',
        'comments',
        'payment_type', //cash, check, epayment
        'school_id',
        'student_id',
        'transaction_id',
        'version_id',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
