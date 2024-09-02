<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Livewire\BasePage;
use App\Livewire\Filters;
use App\Livewire\Forms\CandidateForm;
use App\Models\Events\Event;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionTeacherConfig;
use App\Models\Schools\Teacher;
use App\Models\UserConfig;
use App\Services\CalcSeniorYearService;
use App\Services\CoTeachersService;
use App\Services\EventEnsemblesVoicePartsArrayService;
use App\Services\MakeCandidateRecordsService;
use App\Services\PathToRegistrationService;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class CandidatesTableComponent extends BasePage
{
    use WithFileUploads;

    public CandidateForm $form;
    public Event $event;
    public Version $version;

    public array $auditionFiles = [];
    public array $ensembleVoiceParts = [];
    public array $eventGrades = [];
    public bool $height = false;
    public array $heights = [];
    public string $pathToRegistration = '';
    public int $schoolId = 0;
    public int $seniorYear = 0;
    public bool $shirtSize = false;
    public array $shirtSizes = [];
    public bool $showFormAdd = false;
    public int $showFormEdit = 0;
    public bool $showRegistrationPath = false;
    public bool $studentHomeAddress = false;
    public bool $teacherEpaymentStudent = false;
    public string $teacherEpaymentStudentLastUpdated = '';
    public array $teachers = [];
    public bool $versionEpaymentStudent = false;
    public int $versionId = 0;

    public Filters $filters;

    public function mount(): void
    {
        parent::mount();
        $service = new CalcSeniorYearService();

        $this->versionId = $this->dto['id'];
        $this->version = Version::find($this->versionId);
        $this->versionEpaymentStudent = $this->version->epayment_student;
        $this->event = $this->version->event;
        $this->height = $this->version->height;
        $this->shirtSize = $this->version->shirt_size;
        $this->studentHomeAddress = $this->version->student_home_address;

        $this->teachers = $this->getTeachers();

        $this->ensembleVoiceParts = $this->getEnsembleVoiceParts();
        $this->eventGrades = $this->getEventGrades();
        $this->hasFilters = true;
        $this->heights = $this->getHeights();
        $this->schoolId = (int) UserConfig::getValue('schoolId');
        $this->seniorYear = $service->getSeniorYear();
        $this->shirtSizes = self::SHIRTSIZES;
        $this->sortCol = 'users.last_name';

        $this->filters->candidateGradesSelectedIds = $this->filters->previousFilterExists('candidateGradesSelectedIds',
            $this->dto['header'])
            ? $this->filters->getPreviousFilterArray('candidateGradesSelectedIds', $this->dto['header'])
            : $this->filters->candidateGradesSelectedIds;

        //filterMethods
        $this->filterMethods = ['candidateGrades', 'candidateStatuses'];

        //pre-load empty model
        $this->form->candidate = new Candidate();

        //determine if teacher allows student ePayments
        $this->versionEpaymentStudent = $this->version->epayment_student;
        $vtc = VersionTeacherConfig::query()
            ->where('version_id', $this->versionId)
            ->where('teacher_id', Teacher::where('user_id', auth()->id())->first()->id)
            ->first();
        $this->teacherEpaymentStudent = $vtc->epayment_student ?? false;
        $this->teacherEpaymentStudentLastUpdated = Carbon::parse($vtc->updated_at)->diffForHumans();

    }

    public function render()
    {
        $this->saveSortParameters();

        $this->filters->setFilter('candidateGradesSelectedIds', $this->dto['header']);
        $this->filters->setFilter('candidateStatusesSelectedIds', $this->dto['header']);

        //ensure that all eligible students have a record
        $this->makeCandidateRecords();

        //ensure that missing application requirements are logged
        $this->form->missingApplicationRequirements();

        return view('livewire..events.versions.participations.candidates-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
                'teachers' => $this->teachers,
            ]);
    }

    public function recordingApprove(string $fileType): void
    {
        $this->reset('showSuccessIndicator', 'successMessage');

        if ($this->form->recordingApprove($fileType)) {

            $this->showSuccessIndicator = true;
            $this->successMessage = 'Recording accepted';

            $this->pathToRegistration = PathToRegistrationService::getPath($this->form->candidate->id);
        }
    }

    public function recordingReject(string $fileType): void
    {
        $this->reset('showSuccessIndicator', 'successMessage');

        $url = $this->form->recordings[$fileType]['url'];

        //if the db record has been deleted, delete the s3 storage file
        if ($this->form->recordingReject($fileType)) {

            //delete the file from s3 storage
            Storage::disk('s3')->delete($url);

            $this->showSuccessIndicator = true;
            $this->successMessage = 'Recording rejected';

            $this->pathToRegistration = PathToRegistrationService::getPath($this->form->candidate->id);
        }
    }

    public function selectCandidate(int $candidateId): void
    {
        $this->form->setCandidate($candidateId);

        $this->pathToRegistration = PathToRegistrationService::getPath($candidateId);
    }

    public function updatedAuditionFiles($value, $key): void
    {
        $this->reset('showSuccessIndicator', 'successMessage');

        $fileName = $this->makeFileName($key);

        $this->auditionFiles[$key]->storePubliclyAs('recordings', $fileName, 's3');

        //store the url reference for saving
        $this->form->recordings[$key]['url'] = 'recordings/'.$fileName;

        if ($this->form->recordingSave($key)) {

            $this->form->resetStatus();

            $this->showSuccessIndicator = true;
            $this->successMessage = ucwords($key).' recording saved.';

            $this->pathToRegistration = PathToRegistrationService::getPath($this->form->candidate->id);
        }
    }

    public function updatedTeacherEpaymentStudent(): void
    {
        $updated = VersionTeacherConfig::updateOrCreate(
            [
                'teacher_id' => Teacher::where('user_id', auth()->id())->first()->id,
                'version_id' => $this->versionId,
            ],
            [
                'epayment_student' => $this->teacherEpaymentStudent,
            ]
        );

        if ($updated) {
            //pause for 2 seconds to prevent display from reading : 0 seconds ago
            sleep(2);
            $this->teacherEpaymentStudentLastUpdated = Carbon::parse($updated->updated_at)->diffForHumans();
        }
    }

    public function updatedForm($value, $key)
    {
        $this->reset('showSuccessIndicator', 'successMessage');

        if ($this->form->updatedProperty($value, $key)) {

            //check status with every property update
            $this->form->resetStatus();

            $this->pathToRegistration = PathToRegistrationService::getPath($this->form->candidate->id);
            $this->showSuccessIndicator = true;
            $this->successMessage = Str::remove('Id', Str::headline($key)).' updated.';
        }
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name/program Name', 'sortBy' => 'name'],
            ['label' => 'status', 'sortBy' => 'status'],
            ['label' => 'grade', 'sortBy' => 'classOf'],
            ['label' => 'voice part', 'sortBy' => 'voicePart'],
        ];
    }

    private function getEnsembleVoiceParts(): array
    {
        $service = new EventEnsemblesVoicePartsArrayService($this->event->eventEnsembles);

        return $service->getArray();
    }

    private function getEventGrades(): array
    {
        $gradesString = $this->event->grades;

        $gradesArray = explode(',', $gradesString);

        return array_combine($gradesArray, $gradesArray);
    }

    private function getHeights(): array
    {
        $heights = [];

        for ($inches = 30; $inches < 94; $inches++) {
            $feet = floor($inches / 12);
            $remainingInches = $inches % 12;
            $heights[$inches] = "{$inches}\" ({$feet}'{$remainingInches}\")";
        }

        return $heights;
    }

    private function getRows(): Builder
    {
        $coTeacherIds = CoTeachersService::getCoTeachersIds();

        return DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users AS tusers', 'tusers.id', '=', 'teachers.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('candidates.version_id', $this->versionId)
            ->whereIn('candidates.teacher_id', $coTeacherIds)
            ->where('candidates.school_id', $this->schoolId)
            ->tap(function ($query) {
                $this->filters->filterCandidatesByClassOfs($query);
                $this->filters->filterCandidatesByStatuses($query, $this->search);
            })
            ->select('candidates.id AS candidateId', 'candidates.ref', 'candidates.status',
                'candidates.program_name',
                'users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix_name',
                'students.class_of',
                'voice_parts.abbr AS voicePart'
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('users.last_name', 'asc') //secondary sort ALWAYS applied
            ->orderBy('users.first_name', 'asc'); //tertiary sort ALWAYS applied
    }

    private function getTeachers(): array
    {
        $coTeacherIds = CoTeachersService::getCoTeachersIds();

        $teachers = [];
        foreach ($coTeacherIds as $teacherId) {

            $teachers[$teacherId] = Teacher::find($teacherId)->user->name;
        }

        return $teachers;
    }

    private function makeCandidateRecords()
    {
        $service = new MakeCandidateRecordsService($this->versionId);
    }

    private function makeFileName(string $uploadType): string
    {
        //ex: 661234_scales.mp3
        $fileName = $this->form->candidate->id;
        $fileName .= '_';
        $fileName .= $uploadType;
        $fileName .= '.';
        $fileName .= pathInfo($this->auditionFiles[$uploadType]->getClientOriginalName(), PATHINFO_EXTENSION);

        return $fileName;
    }

}
