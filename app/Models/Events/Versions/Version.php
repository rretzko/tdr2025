<?php

namespace App\Models\Events\Versions;

use App\Models\Events\Event;
use App\Models\Events\EventEnsemble;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Version extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'epayment_student',
        'epayment_teacher',
        'epayment_vendor',
        'event_id',
        'fee_participation',
        'fee_on_site_registration',
        'fee_registration',
        'height',
        'name',
        'participation_contract',
        'pitch_files_student',
        'pitch_files_teacher',
        'school_county',
        'short_name',
        'senior_class_of',
        'shirt_size',
        'student_home_address',
        'status',
        'teacher_phone_mobile',
        'teacher_phone_work',
        'upload_type',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Return eventEnsembles collection ordered by version ordering
     * @return Collection
     */
    public function eventEnsembles(): Collection
    {
        return EventEnsemble::query()
            ->join('version_event_ensemble_orders', 'event_ensembles.id', '=',
                'version_event_ensemble_orders.event_ensemble_id')
            ->where('event_ensembles.event_id', $this->event_id)
            ->orderBy('version_event_ensemble_orders.order_by')
            ->select('event_ensembles.*', 'version_event_ensemble_orders.order_by')
            ->get();
    }

    public function getVersionManager(): User
    {
        $versionRole = VersionRole::query()
            ->where('version_id', $this->id)
            ->where('role', 'event manager')
            ->orderBy('id')
            ->first();

        $versionParticipant = VersionParticipant::find($versionRole->version_participant_id);

        return User::find($versionParticipant->user_id);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function getScoreCategoriesWithColSpanArrayAttribute(): array
    {
        $categories = ScoreCategory::query()
            ->where('version_id', $this->id)
            ->orderBy('order_by')
            ->get();

        if ($categories->isEmpty()) {
            $categories = ScoreCategory::query()
                ->where('event_id', $this->event_id)
                ->orderBy('order_by')
                ->get();
        }

        $categorySpans = [];
        foreach ($categories as $category) {
            $colSpan = ScoreFactor::query()
                ->where('version_id', $this->id)
                ->where('score_category_id', $category->id)
                ->count('id');
            if (!$colSpan) {
                $colSpan = ScoreFactor::query()
                    ->where('event_id', $this->event_id)
                    ->where('score_category_id', $category->id)
                    ->count('id');
            }

            $categorySpans[] = [
                'descr' => $category->descr,
                'colspan' => $colSpan,
            ];
        }

        return $categorySpans;
    }

    public function getScoreFactorsAttribute(): Collection
    {
        $scoreFactors = ScoreFactor::query()
            ->where('version_id', $this->id)
            ->orderBy('order_by')
            ->get();

        if ($scoreFactors->isEmpty()) {
            $scoreFactors = ScoreFactor::query()
                ->where('event_id', $this->event_id)
                ->orderBy('order_by')
                ->get();
        }

        return $scoreFactors;
    }

    public function showPitchFiles(string $type = ''): bool
    {
        return match ($type) {
            'student' => (bool) $this->pitch_files_student,
            'teacher' => (bool) $this->pitch_files_teacher,
            '' => (bool) ($this->pitch_files_student || $this->pitch_files_teacher),
        };
    }

    public function versionParticipants(): HasMany
    {
        return $this->hasMany(VersionParticipant::class);
    }

    public function versionPitchFiles(): HasMany
    {
        return $this->hasMany(VersionPitchFile::class)
            ->orderBy('order_by');
    }
}
