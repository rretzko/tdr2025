<?php

namespace App\Models\Events\Versions\Scoring;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\Students\Student;
use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'candidate_id',
        'student_id',
        'school_id',
        'score_category_id',
        'score_category_order_by',
        'score_factor_id',
        'score_factor_order_by',
        'judge_id',
        'judge_order_by',
        'voice_part_id',
        'voice_part_order_by',
        'score',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function scoreCategory(): BelongsTo
    {
        return $this->belongsTo(ScoreCategory::class);
    }

    public function scoreFactor(): BelongsTo
    {
        return $this->belongsTo(ScoreFactor::class);
    }

    public function voicePart(): BelongsTo
    {
        return $this->belongsTo(VoicePart::class);
    }
}
