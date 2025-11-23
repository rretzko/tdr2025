<?php

namespace App\Models\Events\Versions;

use App\Models\Events\Event;
use App\Models\Events\EventEnsemble;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Schools\School;
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
        'supervisor_email_preferred',
        'supervisor_name_preferred',
        'supervisor_phone_preferred',
        'supervisor_email_required',
        'supervisor_name_required',
        'supervisor_phone_required',
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
            ->where('version_event_ensemble_orders.version_id', $this->id)
            ->orderBy('version_event_ensemble_orders.order_by')
            ->select('event_ensembles.*', 'version_event_ensemble_orders.order_by')
            ->get();
    }

    public function getCoregistrationManagerAddressBySchoolCounty(int $schoolId): bool|string
    {
        if (!$this->hasCoregistrationManager()) {
            return false;
        }

        $school = School::find($schoolId);
        $countyId = $school->county_id;
        //get versionParticipantId of coregistration manager assigned to county_id for $this->id
        $versionParticipantId = VersionCountyAssignment::query()
            ->where('version_id', $this->id)
            ->where('county_id', $countyId)
            ->value('version_participant_id');

        if (is_null($versionParticipantId)) {
            return "$school->name has an 'Unknown' county assignment.";
        }

        $versionParticipant = VersionParticipant::find($versionParticipantId);
        $user = User::find($versionParticipant->user_id);
        $mailingAddressCsv = CoregistrationManagerMailingAddress::query()
            ->where('version_id', $this->id)
            ->where('version_participant_id', $versionParticipantId)
            ->value('mailing_address');

        return $user->name . ', ' . $mailingAddressCsv;
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

    public function hasCoregistrationManager(): bool
    {
        return VersionRole::query()
            ->where('version_id', $this->id)
            ->where('role', 'coregistration manager')
            ->exists();
    }

    /**
     * @param array $ids //include or suppress version_participant.ids
     * @param bool $suppress //if false, limit results to ids in array $ids
     * @return array
     */
    public function participantsArray(array $ids, bool $suppress = true): array
    {
        $query = VersionParticipant::query()
            ->join('users', 'version_participants.user_id', '=', 'users.id')
            ->where('version_id', $this->id);

        $suppress
            ? $query->whereNotIn('version_participants.id', $ids)
            : $query->whereIn('version_participants.id', $ids);

        return $query->select('users.name', 'version_participants.id',
                DB::raw("CONCAT(users.last_name,', ',users.first_name,' ',users.middle_name) AS alphaName")
            )
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get()
            ->toArray();
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function getScoreCategoriesWithColSpanArrayAttribute(): array
    {
        /**
         * @todo rework this workflow to manage an event (i.e. MACDA)
         * @todo hat reconfigures categories to include some old plus new categories
         * @todo resulting in overcount of categories and colSpan values
         */
        //workaround

        if($this->event_id === 25){ //MACDA
            $categories = ScoreCategory::whereIn('id',[19,14])->get();
        }else{

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
        }

        $categorySpans = [];
        foreach ($categories as $category) {
            $colSpan = ScoreFactor::query()
                ->where('score_category_id', $category->id)
                ->where(function ($query) use ($category) {
                    $query->where('version_id', $this->id)
                        ->orWhere('event_id', $this->event_id);
                })
                ->count('id');

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
