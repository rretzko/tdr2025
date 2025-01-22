<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Livewire\BasePage;
use App\Livewire\Forms\CandidateForm;
use App\Models\Events\Event;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\Schools\Teacher;
use App\Models\Schools\Teachers\Coteacher;
use App\Models\Students\Student;
use App\Models\UserConfig;
use App\Services\CalcGradeFromClassOfService;
use App\Services\CoTeachersService;
use App\Services\PathToRegistrationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CandidatesRecordingsTableComponent extends BasePage
{
    public CandidateForm $form;
    public array $ensembleVoiceParts = [];
    public Event $event;
    public string $pathToRegistration = '';
    public int $schoolId = 0;
    public Version $version;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->schoolId = UserConfig::getValue('schoolId');
        $this->version = Version::find($this->dto['versionId']);
        $this->versionId = $this->version->id;
        $this->event = $this->version->event;

        $this->sortCol = 'voice_parts.order_by';
    }

    public function render()
    {
        return view('livewire..events.versions.participations.candidates-recordings-table-component',
            [
                'rows' => $this->getRows(),
            ]);
    }

    public function selectCandidate(int $candidateId): void
    {
        $this->form->setCandidate($candidateId);

        //return a <ul></ul> string of registration requirements, completed and pending
        $this->pathToRegistration = PathToRegistrationService::getPath($candidateId);

        //set audition voicing to grade-specific options matching the selected Candidate's grade
        $this->ensembleVoiceParts = $this->setEnsembleVoiceParts($candidateId);
    }

    private function getRows(): Collection
    {
        $coTeacherIds = CoTeachersService::getCoTeachersIds();
        $schoolIds = $this->getSchoolIds();

//        $this->troubleShooting($coTeacherIds, $eligibleClassOfs, $schoolIds, $this->versionId);
        return DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->leftJoin('recordings AS scales', 'candidates.id', '=', 'scales.candidate_id')
            ->leftJoin('recordings AS solo', 'candidates.id', '=', 'solo.candidate_id')
            ->leftJoin('recordings AS quintet', 'candidates.id', '=', 'quintet.candidate_id')
            ->where('candidates.version_id', $this->versionId)
            ->whereIn('candidates.teacher_id', $coTeacherIds)
            ->whereIn('candidates.school_id', $schoolIds)
            ->where(function ($query) {
                $query->where('scales.file_type', 'scales')
                    ->orWhereNull('scales.file_type');
            })
            ->where(function ($query) {
                $query->where('solo.file_type', 'solo')
                    ->orWhereNull('solo.file_type');
            })
            ->where(function ($query) {
                $query->where('quintet.file_type', 'quintet')
                    ->orWhereNull('quintet.file_type');
            })
            ->select('candidates.id AS candidateId', 'candidates.status',
                'users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix_name',
                'voice_parts.abbr AS voicePart', 'voice_parts.order_by', 'voice_parts.descr AS voicePartDescr',
                'scales.url AS scalesUrl', 'scales.file_type AS scalesFileType',
                'solo.url AS soloUrl', 'solo.file_type AS soloFileType',
                'quintet.url AS quintetUrl', 'quintet.file_type AS quintetFileType',
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('users.last_name', 'asc') //secondary sort ALWAYS applied
            ->orderBy('users.first_name', 'asc') //tertiary sort ALWAYS applied
            ->get();
    }

    private function getSchoolIds(): array
    {
        $schoolIds = [$this->schoolId];

        $myTeacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $coTeacherSchoolIds = Coteacher::query()
            ->where('coteacher_id', $myTeacherId)
            ->distinct()
            ->pluck('school_id')
            ->toArray();

        return array_merge($schoolIds, $coTeacherSchoolIds);
    }

    private function setEnsembleVoiceParts(int $candidateId): array
    {
        $candidate = Candidate::find($candidateId);
        $student = Student::find($candidate->student_id);
        $classOf = $student->class_of;

        $service = new CalcGradeFromClassOfService();
        $grade = $service->getGrade($classOf);
        $voiceParts = [];

        foreach ($this->event->voicePartsByGrade($grade) as $voicePart) {
            $voiceParts[$voicePart->id] = $voicePart->descr;
        }

        return $voiceParts;
    }
}
