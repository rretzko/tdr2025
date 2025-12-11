<?php

namespace App\Models\Events\Versions\Scoring;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Events\Versions\Room;

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

    public function getOrderByAttribute(): int
    {
        $orderBys = [
            'head judge' => 1,
            'judge 2' => 2,
            'judge 3' => 3,
            'judge 4' => 4,
        ];

        return $orderBys[$this->judge_type];
    }

    public function progress(string $status): array
    {
        $room = $this->room;
        $countRegistrants = $room->getCountRegistrants();
        $candidateIds = $room->getRegistrantIds();
        $maxScoreCount = $room->getMaxScoreCount() / $room->judges->count();  //3 = 6 / 2

        //ex. getCountCompleted()
        $statusMethod = 'getCount'.ucwords($status);

        $statusCount = $this->$statusMethod($candidateIds, $maxScoreCount);
        $statusPct = floor(($statusCount / $countRegistrants) * 100).'%';

        return ['count' => $statusCount, 'pct' => $statusPct];
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

    private function getCountCompleted(array $candidateIds, int $maxScoreCount): int
    {
        return Score::query()
            ->whereIn('candidate_id', $candidateIds)
            ->whereIn('judge_id', [$this->id])
            ->selectRaw('COUNT(score) AS scoreCount')
            ->groupBy('candidate_id')
            ->having('scoreCount', $maxScoreCount)
            ->count();
    }

    private function getCountPending(array $candidateIds, int $maxScoreCount): int
    {
        return (count($candidateIds)
            - $this->getCountCompleted($candidateIds, $maxScoreCount)
            - $this->getCountWip($candidateIds, $maxScoreCount));
    }

    private function getCountWip(array $candidateIds, int $maxScoreCount): int
    {
        return Score::query()
            ->whereIn('candidate_id', $candidateIds)
            ->whereIn('judge_id', [$this->id])
            ->selectRaw('COUNT(score) AS scoreCount')
            ->groupBy('candidate_id')
            ->having('scoreCount', '<', $maxScoreCount)
            ->count();
    }


}
