<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Livewire\BasePage;
use App\Livewire\Filters;
use App\Livewire\Forms\CandidateForm;
use App\Models\Events\Event;
use App\Models\Events\Versions\Version;
use App\Models\Students\VoicePart;
use App\Models\UserConfig;
use App\Services\CalcSeniorYearService;
use App\Services\CoTeachersService;
use App\Services\MakeCandidateRecordsService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
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
    public array $heights = [];
    public int $schoolId = 0;
    public int $seniorYear = 0;
    public array $shirtSizes = [];
    public bool $showFormAdd = false;
    public int $showFormEdit = 0;
    public int $versionId = 0;

    public Filters $filters;

    const  STATUSES = [
        'active' => 'active',
        'eligible' => 'eligible',
        'no-app' => 'no-app',
        'pre-registered' => 'pre-registered',
        'prohibited' => 'prohibited',
        'registered' => 'registered',
        'removed' => 'removed',
        'withdrew' => 'withdrew'
    ];

    public function mount(): void
    {
        parent::mount();
        $service = new CalcSeniorYearService();

        $this->versionId = $this->dto['id'];
        $this->version = Version::find($this->versionId);
        $this->event = $this->version->event;

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
        $this->filterMethods[] = 'candidateGrades';
    }

    public function render()
    {
        //ensure that all eligible students have a record
        $this->makeCandidateRecords();

        return view('livewire..events.versions.participations.candidates-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
                'statuses' => self::STATUSES,
            ]);
    }

    public function process(): void
    {
        dd($this->auditionFiles);
    }

    public function selectCandidate(int $candidateId): void
    {
        $this->form->setCandidate($candidateId);
    }

    public function updatedAuditionFiles($value, $key): void
    {
        $fileName = $this->makeFileName($key);

        $this->auditionFiles[$key]->storePubliclyAs('recordings', $fileName, 's3');

        //save new logo if $this->form->sysId
        $this->form->auditionFiles[$key] = 'recordings/'.$fileName;
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
        $ensembles = $this->event->eventEnsembles;

        $voiceParts = $ensembles->flatMap(function ($ensemble) {
            return explode(',', $ensemble->voice_part_ids);
        })->unique();

//        foreach($ensembles AS $ensemble){
//
//            $voiceParts = array_merge($voiceParts, explode(',', $ensemble->voice_part_ids));
//        }
//
//        $unique = array_unique($voiceParts);

        return VoicePart::query()
            ->whereIn('id', $voiceParts)
            ->whereNot('descr', 'ALL')
            ->orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();
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
