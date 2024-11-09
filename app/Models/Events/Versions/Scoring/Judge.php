<?php

namespace App\Models\Events\Versions\Scoring;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Judge extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_id',
        'room_id',
        'user_id',
        'judge_type',
        'status_type',
    ];

    public function getCandidateScoreCount(int $candidateId): int
    {
        return Score::query()
            ->where('candidate_id', $candidateId)
            ->where('judge_id', $this->id)
            ->count('id');
    }

    public function getCandidateTotalScore(int $candidateId): int
    {
        return Score::query()
            ->where('candidate_id', $candidateId)
            ->where('judge_id', $this->id)
            ->sum('score');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }


}
