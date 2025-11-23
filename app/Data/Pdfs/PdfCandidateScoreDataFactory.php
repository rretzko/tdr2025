<?php

namespace App\Data\Pdfs;

use App\Models\Events\Event;
use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionConfigDate;
use App\Models\Pronoun;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\Students\Student;
use App\Models\Students\VoicePart;
use App\Models\User;
use App\Services\CalcGradeFromClassOfService;
use App\Services\ConvertToUsdService;
use App\Services\FullNameService;
use Illuminate\Support\Carbon;

class PdfCandidateScoreDataFactory
{
    private int $candidateId = 0;
    private array $dto = [];
    private Event $event;
    private School $school;
    private Student $student;
    private Teacher $teacher;
    private Version $version;
    private int $versionId;
    private User $user;

    public function __construct(private readonly Candidate $candidate)
    {
        $this->candidateId = $this->candidate->id;
        $this->school = School::find($this->candidate->school_id);
        $this->student = Student::find($this->candidate->student_id);
        $this->teacher = Teacher::find($this->candidate->teacher_id);
        $this->user = User::find($this->student->user_id);
        $this->version = Version::find($candidate->version_id);
        $this->versionId = (int) $this->version->id;
        $this->event = Event::find($this->version->event_id);

        $this->init();
    }

    private function init()
    {
        $this->dto['auditionFee'] = $this->getAuditionFee();
        $this->dto['auditionPeriod'] = $this->getAuditionPeriod();
        $this->dto['candidateVoicePartAbbr'] = $this->getCandidateVoicePartAbbr();
        $this->dto['candidateVoicePartDescr'] = $this->getCandidateVoicePartDescr();
        $this->dto['candidateFullName'] = $this->getCandidateFullName();
        $this->dto['candidateId'] = $this->candidateId;
        $this->dto['emergencyContactName'] = $this->getEmergencyContact('name');
        $this->dto['emergencyContactMobile'] = $this->getEmergencyContact('phone_mobile');
        $this->dto['ensembleNames'] = $this->getEnsembleNames();
        $this->dto['first'] = $this->user->first_name;
        $this->dto['fullName'] = $this->candidate->program_name;
        $this->dto['fullNameAlpha'] = $this->user->fullNameAlpha;
        $this->dto['grade'] = $this->getGrade();
        $this->dto['judgeCount'] = $this->getJudgeCount();
        $this->dto['logo'] = $this->getLogo();
        $this->dto['logoPdf'] = $this->getLogo();
        $this->dto['maxCount'] = $this->version->event->max_registrant_count;
        $this->dto['maxScoreFactorCount'] = $this->getMaxScoreFactorCount();
        $this->dto['organizationName'] = $this->event->organization;
        $this->dto['organization'] = $this->event->organization; //old new=organizationName
        $this->dto['participationFee'] = $this->getParticipationFee();
        $this->dto['postmarkDeadline'] = $this->getPostmarkDeadline();
        $this->dto['pronounObject'] = $this->getPronoun('object');
        $this->dto['pronounPersonal'] = $this->getPronoun('personal');
        $this->dto['pronounPossessive'] = $this->getPronoun('possessive');
        $this->dto['auditionResult'] = $this->getAuditionResult();
        $this->dto['schoolName'] = $this->school->name;
        $this->dto['schoolShortName'] = $this->school->shortName;
        $this->dto['scoreCategories'] = $this->getScoreCategories();
        $this->dto['scoreCategoryFactorCount'] = $this->getScoreCategoryFactorCounts();
        $this->dto['scoreFactorAbbrs'] = $this->getScoreFactorAbbrs();
        $this->dto['scores'] = $this->getScores();
        $this->dto['seniorClassOf'] = $this->version->senior_class_of;
        $this->dto['studentName'] = $this->getStudentName();
        $this->dto['teacherFullName'] = $this->teacher->user->name;
        $this->dto['versionShortName'] = $this->version->short_name;
        $this->dto['versionName'] = $this->version->name;

    }

    private function getAuditionFee(): string
    {
        $fee = $this->version->fee_registration;

        return ConvertToUsdService::penniesToUsd($fee);
    }

    private function getAuditionPeriod(): string
    {
        $vcds = VersionConfigDate::where('version_id', $this->version->id)->get();

        $openDate = $vcds->where('date_type', 'adjudication_open')
            ->first()
            ->version_date;

        $openMd = ($openDate)
            ? Carbon::parse($openDate)->format('F j')
            : '*** Open Date Not Found ***';

        $closeDate = $vcds->where('date_type', 'adjudication_close')
            ->first()
            ->version_date;

        $closeMd = ($closeDate)
            ? Carbon::parse($closeDate)->format('F j')
            : '*** Close Date Not Found ***';

        return $openMd.' - '.$closeMd;
    }

    private function getAuditionResult(): array
    {
        return AuditionResult::query()
            ->where('candidate_id', $this->candidateId)
            ->select('id', 'acceptance_abbr', 'total')
            ->first()
            ->toArray();
    }

    private function getCandidateVoicePartAbbr()
    {
        return VoicePart::find($this->candidate->voice_part_id)
            ->abbr;
    }

    private function getCandidateVoicePartDescr(): string
    {
        return VoicePart::find($this->candidate->voice_part_id)
            ->descr;
    }

    private function getCandidateFullName(): string
    {
        return FullNameService::getName($this->user);
    }

    private function getEmergencyContact(string $property): string
    {
        $emergencyContacts = $this->student->emergencyContacts ?? collect();

        if ($emergencyContacts->count()) {
            return $emergencyContacts->first()->$property;
        }

        return 'none found';
    }

    private function getEnsembleNames(): array
    {
        return $this->event->eventEnsembles->pluck('name')->toArray();
    }

    private function getGrade(): string
    {
        $classOf = $this->student->class_of;

        $service = new CalcGradeFromClassOfService();

        return $service->getGrade($classOf);
    }

    private function getJudgeCount(): int
    {
        return VersionConfigAdjudication::query()
            ->where('version_id', $this->version->id)
            ->first()
            ->judge_per_room_count;
    }

    private function getLogo(): string
    {
        return 'logos/nj-mea-logo.jpg';
    }

    private function getMaxScoreFactorCount(): int
    {
        $query = ScoreFactor::query();

        return (ScoreFactor::where('version_id', $this->versionId)->exists())
            ? $query->where('version_id', $this->versionId)
                ->count('id')
            : $query->where('event_id', $this->version->event_id)
                ->count('id');
    }

    private function getParticipationFee(): string
    {
        $fee = $this->version->fee_participation;

        return ConvertToUsdService::penniesToUsd($fee);
    }

    private function getPostmarkDeadline(): string
    {
        $postmarkDeadlineDate = VersionConfigDate::query()
            ->where('version_id', $this->version->id)
            ->where('date_type', 'postmark_deadline')
            ->first();

        return ($postmarkDeadlineDate)
            ? Carbon::parse($postmarkDeadlineDate->version_date)->format('F j, Y')
            : '*** Postmark Deadline Not Found ***';
    }

    private function getPronoun(string $column): string
    {
        return Pronoun::find($this->user->pronoun_id)->$column;
    }

    private function getScoreCategories(): array
    {
        $query = ScoreCategory::query()
            ->select('descr', 'order_by')
            ->orderBy('order_by');

        /** WORKAROUND */
        //@todo rewire this for versions which mix old and new categories, i.e. MACDA
        if($this->versionId === 85){
            return $query->whereIn('id', [19,14])
                ->pluck('descr')
                ->toArray();
        }

        return (ScoreCategory::where('version_id', $this->versionId)->exists())
            ? $query->where('version_id', $this->versionId)
                ->pluck('descr')
                ->toArray()
            : $query->where('event_id', $this->version->event_id)
                ->pluck('descr')
                ->toArray();
    }

    private function getScoreCategoryFactorCount(ScoreCategory $scoreCategory): int
    {
        $query = ScoreFactor::where('score_category_id', $scoreCategory->id);

        return (ScoreFactor::where('version_id', $this->versionId)->exists())
            ? $query->where('version_id', $this->versionId)
                ->count('id')
            : $query->where('event_id', $this->version->event_id)
                ->count('id');
    }

    private function getScoreCategoryFactorCounts(): array
    {
        $a = [];
        $query = ScoreCategory::get();

        $scoreCategories = (ScoreCategory::where('version_id', $this->versionId)->exists())
            ? $query->where('version_id', $this->versionId)
            : $query->where('event_id', $this->version->event_id);

        foreach ($scoreCategories as $scoreCategory) {

            $a[$scoreCategory->descr] = $this->getScoreCategoryFactorCount($scoreCategory);
        }

        return $a;
    }

    private function getScoreFactorAbbrs(): array
    {
        $query = ScoreFactor::query()
            ->select('order_by', 'abbr')
            ->orderBy('order_by');

        return (ScoreFactor::where('version_id', $this->versionId)->exists())
            ? $query->where('version_id', $this->versionId)
                ->get()
                ->toArray()
            : $query->where('event_id', $this->version->event_id)
                ->get()
                ->toArray();
    }

    private function getScores(): array
    {
        return Score::query()
            ->where('candidate_id', $this->candidateId)
            ->select('id', 'score',
                'score_category_order_by', 'score_factor_order_by', 'judge_order_by')
            ->orderBy('judge_order_by')
            ->orderBy('score_category_order_by')
            ->orderBy('score_factor_order_by')
            ->pluck('score')
            ->toArray();

    }

    /**
     * synonym for candidateFullName()
     * @return string
     */
    private function getStudentName(): string
    {
        return $this->getCandidateFullName();
    }

    public function getDto(): array
    {
        return $this->dto;
    }


}
