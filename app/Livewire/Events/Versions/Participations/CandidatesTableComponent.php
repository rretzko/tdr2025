<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Data\Pdfs\PdfApplicationDataFactory;
use App\Http\Controllers\Pdfs\ApplicationPdfController;
use App\Livewire\BasePage;
use App\Livewire\Filters;
use App\Livewire\Forms\CandidateForm;
use App\Models\Events\Event;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Obligation;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionTeacherConfig;
use App\Models\Schools\Teachers\Supervisor;
use App\Models\Schools\Teacher;
use App\Models\Schools\Teachers\Coteacher;
use App\Models\Students\Student;
use App\Models\UserConfig;
use App\Services\CalcGradeFromClassOfService;
use App\Services\CalcSeniorYearService;
use App\Services\CandidateStatusService;
use App\Services\CandidateSummaryTableService;
use App\Services\CoTeachersService;
use App\Services\EventEnsemblesVoicePartsArrayService;
use App\Services\FindPdfPathService;
use App\Services\MakeCandidateRecordsService;
use App\Services\PathToRegistrationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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
    public string $ePaymentVendor = '';
    public array $ensembleVoiceParts = [];
    public array $eventGrades = [];
    public bool $hasSupervisorReqs = false;
    public bool $hasTeacherPhoneReqs = false;
    public bool $height = false;
    public array $heights = [];
    public bool $obligationAccepted = false;
    public string $pathToRegistration = '';
    public int $schoolId = 0;
    public int $seniorYear = 0;
    public bool $shirtSize = false;
    public array $shirtSizes = [];
    public bool $showFormAdd = false;
    public int $showFormEdit = 0;
    public bool $showRegistrationPath = false;
    public bool $studentHomeAddress = false;
    public array $supervisorPreferredInfoTypes = [];
    public array $supervisorRequiredInfoTypes = [];
    public bool $supervisorInfoRequired = false;
    public bool $supervisorInfoPreferred = false;
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
        $this->ePaymentVendor = ucwords($this->version->epayment_vendor);
        $this->event = $this->version->event;
        $this->height = $this->version->height;
        $this->shirtSize = $this->version->shirt_size;
        $this->studentHomeAddress = $this->version->student_home_address;

        $this->teachers = $this->getTeachers();

        $this->ensembleVoiceParts = $this->getEnsembleVoiceParts();
        $this->eventGrades = $this->getEventGrades();
        $this->hasFilters = true;
        $this->heights = $this->getHeights();
        $this->schoolId = $this->school->id ?: (int) UserConfig::getValue('schoolId');
        $this->seniorYear = $service->getSeniorYear();
        $this->shirtSizes = self::SHIRTSIZES;
        $this->sortCol = 'users.last_name';

        //ensure that any new/transferred student(s) have a candidate record
        new MakeCandidateRecordsService($this->versionId);

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
            ->first() ?? new VersionTeacherConfig();
        $this->teacherEpaymentStudent = $vtc->epayment_student ?? false;
        $this->teacherEpaymentStudentLastUpdated = Carbon::parse($vtc->updated_at)->diffForHumans();

        //check for obligation acceptance
        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $this->obligationAccepted = (bool) Obligation::query()
            ->where('version_id', $this->versionId)
            ->where('teacher_id', $teacherId)
            ->whereNotNull('accepted')
            ->first();

        //check for teacher phone requirements
        $this->hasTeacherPhoneReqs = $this->checkTeacherPhoneRequirements();

        //check for supervisor info requirement
        $this->checkSupervisorInfoRequirement();
        $this->checkSupervisorInfoPreferred();
        $this->hasSupervisorReqs = $this->checkSupervisorRequirements();

        if (array_key_exists('candidateId', $this->dto)) {
            $this->selectCandidate($this->dto['candidateId']);
        }
    }

    public function render()
    {
        $this->saveSortParameters();

        $this->filters->setFilter('candidateGradesSelectedIds', $this->dto['header']);
        $this->filters->setFilter('candidateStatusesSelectedIds', $this->dto['header']);

        //ensure that missing application requirements are logged
        $this->form->missingApplicationRequirements();

        return view('livewire..events.versions.participations.candidates-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows(), //->paginate($this->recordsPerPage),
                'summaryTable' => $this->getRowsSummaryTable(),
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

            $this->form->status = CandidateStatusService::getStatus($this->form->candidate);
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

            $this->form->status = CandidateStatusService::getStatus($this->form->candidate);
        }
    }

    public function selectCandidate(int $candidateId): void //819164
    {
        $this->form->setCandidate($candidateId);

        //return a <ul></ul> string of registration requirements, completed and pending
        $this->pathToRegistration = PathToRegistrationService::getPath($candidateId);

        //set audition voicing to grade-specific options matching the selected Candidate's grade
        $this->ensembleVoiceParts = $this->setEnsembleVoiceParts($candidateId);
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

    public function updatedFormGrade(): void
    {
        $this->ensembleVoiceParts = $this->setEnsembleVoiceParts($this->form->candidate->id);
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

    public function updatedForm($value, $key): void
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

    /**
     * Remove rows from $this->supervisorPreferredInfoTypes
     * if a matching value exists in the database
     */
    private function checkCurrentStateOfSupervisorPreferreds(): void
    {
        $schoolId = UserConfig::getValue('schoolId');
        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $supervisor = Supervisor::where('school_id', $schoolId)->where('teacher_id', $teacherId)->first();

        //early exit because there is no $supervisor to check
        if (!$supervisor) {
            return;
        }

        //if there are rows in $this->supervisorInfoPreferred
        if ($this->supervisorInfoPreferred) {

            //check each $type to see if any value has been entered
            foreach ($this->supervisorPreferredInfoTypes as $key => $type) {

                $property = 'supervisor_'.$type;

                //if there is a value, remove that row from the array
                if (strlen($supervisor->$property)) {
                    unset($this->supervisorPreferredInfoTypes[$key]);
                }
            }
        }
    }

    /**
     * Remove rows from $this->supervisorRequiredInfoTypes
     * if a matching value exists in the database
     */
    private function checkCurrentStateOfSupervisorRequirements(): void
    {
        $schoolId = UserConfig::getValue('schoolId');
        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $supervisor = Supervisor::where('school_id', $schoolId)->where('teacher_id', $teacherId)->first();

        //early exit because there is no $supervisor to check
        if (!$supervisor) {
            return;
        }

        //if there are rows in $this->supervisorInfoRequired
        if ($this->supervisorInfoRequired) {

            //check each $type to see if any value has been entered
            foreach ($this->supervisorRequiredInfoTypes as $key => $type) {

                $property = 'supervisor_'.$type;

                //if there is a value, remove that row from the array
                if (strlen($supervisor->$property)) {
                    unset($this->supervisorRequiredInfoTypes[$key]);
                }
            }
        }
    }

    private function checkSupervisorInfoRequirement()
    {
        $requirements = [
            'name' => $this->version->supervisor_name_required,
            'email' => $this->version->supervisor_email_required,
            'phone' => $this->version->supervisor_phone_required,
        ];

        foreach ($requirements as $type => $isRequired) {
            if ($isRequired) {
                $this->supervisorInfoRequired = true;
                $this->supervisorRequiredInfoTypes[] = $type;
            }
        }

        $this->checkCurrentStateOfSupervisorRequirements();
    }

    private function checkSupervisorInfoPreferred()
    {
        $preferences = [
            'name' => $this->version->supervisor_name_preferred,
            'email' => $this->version->supervisor_email_preferred,
            'phone' => $this->version->supervisor_phone_preferred,
        ];

        foreach ($preferences as $type => $isPreferred) {
            if ($isPreferred) {
                $this->supervisorInfoPreferred = true;
                $this->supervisorPreferredInfoTypes[] = $type;
            }
        }

        $this->checkCurrentStateOfSupervisorPreferreds();
    }

    /**
     * Return true if the user has completed the required supervisor information
     * @return bool
     */
    private function checkSupervisorRequirements(): bool
    {
        //early exit
        if (!$this->supervisorInfoRequired) {
            return true;
        }

        if (!$this->supervisorRequiredInfoTypes) {
            return true;
        }

        return false;
    }

    private function checkTeacherPhoneRequirements(): bool
    {
        $mobilePhone = false;
        if (($this->version->teacher_phone_mobile && strlen(auth()->user()->phoneMobile())) ||
            (!$this->version->teacher_phone_mobile)) {
            $mobilePhone = true;
        }

        $workPhone = false;
        if (($this->version->teacher_phone_work && strlen(auth()->user()->phoneWork())) ||
            (!$this->version->teacher_phone_work)) {
            $workPhone = true;
        }

        return ($mobilePhone && $workPhone);
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

    private function getEligibleClassOfs(): array
    {
        $event = $this->version->event;
        return $event->classOfs;
    }

    private function getEnsembleVoiceParts(): array
    {
        $service = new CalcGradeFromClassOfService();
//        $grade = $service->getGrade($this->)
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
            $heights[$inches] = "$inches\" ($feet'$remainingInches\")";
        }

        return $heights;
    }

    private function getRows(): \Illuminate\Support\Collection //Builder
    {
        $coTeacherIds = CoTeachersService::getCoTeachersIds();
        $schoolIds = $this->getSchoolIds();
        $eligibleClassOfs = $this->getEligibleClassOfs();

//        $this->troubleShooting($coTeacherIds, $eligibleClassOfs, $schoolIds);

        return DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users AS tusers', 'tusers.id', '=', 'teachers.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->join('student_teacher', 'student_teacher.student_id', '=', 'students.id')
            ->join('school_student', 'school_student.student_id', '=', 'students.id')
            ->leftJoin('signatures AS studentSignature', 'candidates.id', '=', 'studentSignature.candidate_id')
            ->leftJoin('signatures AS guardianSignature', 'candidates.id', '=', 'guardianSignature.candidate_id')
            ->leftJoin('signatures AS teacherSignature', 'candidates.id', '=', 'teacherSignature.candidate_id')
            ->where('candidates.version_id', $this->versionId)
            ->whereIn('candidates.teacher_id', $coTeacherIds)
            ->whereIn('candidates.school_id', $schoolIds)
            ->whereIn('students.class_of', $eligibleClassOfs)
            ->whereIn('student_teacher.teacher_id', $coTeacherIds)
            ->whereIn('school_student.school_id', $schoolIds)
            ->where('school_student.active', 1)
            ->tap(function ($query) {
                $this->filters->filterCandidatesByClassOfs($query);
                $this->filters->filterCandidatesByStatuses($query, $this->search);
            })
            ->select('candidates.id AS candidateId', 'candidates.ref', 'candidates.status',
                'candidates.program_name', 'candidates.emergency_contact_id',
                'users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix_name',
                'students.class_of',
                'voice_parts.abbr AS voicePart',
                DB::raw('
                CASE
                WHEN (studentSignature.signed = 1 AND guardianSignature.signed = 1)
                OR
                (teacherSignature.signed = 1)
                THEN true
                ELSE false
                END AS hasSignature
                ')
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('users.last_name', 'asc') //secondary sort ALWAYS applied
            ->orderBy('users.first_name', 'asc') //tertiary sort ALWAYS applied
            ->get();
    }

    private function getRowsSummaryTable(): array
    {
        $service = new CandidateSummaryTableService($this->schoolId, $this->versionId);

        return $service->getRows();
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

    private function getTeachers(): array
    {
        $coTeacherIds = CoTeachersService::getCoTeachersIds();

        $teachers = [];
        foreach ($coTeacherIds as $teacherId) {

            $teachers[$teacherId] = Teacher::find($teacherId)->user->name;
        }

        return $teachers;
    }

    private function makeFileName(string $uploadType): string
    {
        //ex: 661234_scales_63.mp3
        $fileName = $this->form->candidate->id;
        $fileName .= '_';
        $fileName .= str_replace(' ', '_', $uploadType);
        $fileName .= '_'.$this->form->voicePartId;
        $fileName .= '.';
        $fileName .= pathInfo($this->auditionFiles[$uploadType]->getClientOriginalName(), PATHINFO_EXTENSION);

        return $fileName;
    }

    private function makeNewCandidates(): void
    {
        $service = new \App\Services\MakeCandidateRecordsService($this->versionId);
    }

    /**
     * $this->ensembleVoiceParts is initialized in mount();
     * Re-set that array based on the candidate's grade and the available event ensemble voice parts
     * @param  int  $candidateId
     * @return array
     */
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
