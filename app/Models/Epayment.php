<?php

namespace App\Models;

use App\Models\Events\Versions\TeacherPayment;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Epayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'school_id',
        'user_id',
        'fee_type',
        'candidate_id',
        'transaction_id',
        'amount',
        'comments',
    ];

    public function getTotalCollected(Version $version, int $schoolId): int
    {
        $ePaymentAmount = Epayment::query()
            ->where('version_id', $version->id)
            ->where('school_id', $schoolId)
            ->sum('amount');

        $teacherPaymentAmount = TeacherPayment::query()
            ->where('version_id', $version->id)
            ->where('school_id', $schoolId)
            ->where('fee_type', 'registration')
            ->sum('amount');

        return ($ePaymentAmount + $teacherPaymentAmount);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
