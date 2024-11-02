<?php

namespace App\Livewire;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\PageView;
use App\Models\Schools\School;
use App\Models\Students\Student;
use App\Models\UserConfig;
use App\Models\UserSort;
use App\Services\CoTeachersService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class BasePage extends Component
{
    use WithPagination;

    public array $dto;
    public array $filterMethods = [];
    public Filters $filters;
    public string $firstTimer = 'false';
    public bool $hasFilters = false;
    public bool $hasSearch = false;
    public string $header = 'header';
    public string $pageInstructions = "no instructions found...";
    public array $participatingSchools = [];
    public array $participatingClassOfs = [];
    public array $participatingVoiceParts = [];
    public int $recordsPerPage = 15;
    public School $school;
    public int $schoolCount = 0;
    public string $schoolName = '';
    public array $schools = [];
    public string $search = '';
    public bool $showSuccessIndicator = false;
    public bool $sortAsc = true;
    public string $sortCol = '';
    public string $sortColLabel = '';
    public string $successMessage = '';
    protected $userSort;
    public const AWSBUCKET = 'https://auditionsuite-production.s3.amazonaws.com/';

    public const ENSEMBLETABS = ['ensembles', 'members', 'assets', 'inventory'];

    protected const SHIRTSIZES = [
        '2xs' => '2xs',
        'xs' => 'xs',
        'sm' => 'sm',
        'med' => 'med',
        'lg' => 'lg',
        'xl' => 'xl',
        '2xl' => '2xl',
        '3xl' => '3xl',
        '4xl' => '4xl',
    ];

    public function mount(): void
    {
        $this->participatingSchools = $this->getParticipatingSchools();
        $this->participatingClassOfs = $this->getParticipatingClassOfs();
        $this->participatingVoiceParts = $this->getParticipatingVoiceParts();

        $this->header = $this->dto['header'];
        $this->pageInstructions = $this->dto['pageInstructions'];
        $this->setFirstTimer($this->dto['header']);

        /** @since 2024-10-05 13:08 */
        $this->schools = $this->getSchools();
        /** @deprecated
         * $this->schools = auth()->user()->teacher->schools
         * ->sortBy('name')
         * ->pluck('name', 'id')
         * ->toArray();
         */

        //$this->schoolCount is used to determine if the schools filter should be displayed
        $this->schoolCount = count($this->schools);

        $this->school = ($this->schoolCount === 1)
            ? School::find(array_key_first($this->schools))
            : new School();

        //caution check
        if ($this->school->id && ($this->school->id != UserConfig::getValue('schoolId'))) {
            UserConfig::setProperty('schoolId', $this->school->id);
        }

        $this->schoolName = ($this->school->id)
            ? $this->school->name
            : '';

        $this->filters->init($this->dto['header']);

        $this->recordsPerPage = UserConfig::query()
            ->where('user_id', auth()->id())
            ->where('header', $this->dto['header'])
            ->where('property', 'recordsPerPage')
            ->value('value') ?? 15;

        $this->userSort = UserSort::query()
            ->where('user_id', auth()->id())
            ->where('header', $this->dto['header'])
            ->first();
    }

    public function getSavedSortColumn(string $default): string
    {
        return UserSort::query()
            ->where('user_id', auth()->id())
            ->where('header', $this->dto['header'])
            ->value('column') ?? $default;
    }

    public function getSavedSortAsc(): bool
    {
        return (bool) UserSort::query()
            ->where('user_id', auth()->id())
            ->where('header', $this->dto['header'])
            ->value('asc') ?? true;
    }

    public function getSchools(): array
    {
        return auth()->user()->teacher->schools()
            ->with('schoolTeacher')
            ->wherePivot('active', 1)
            ->orderBy('schools.name')
            ->pluck('schools.name', 'schools.id')
            ->toArray();
    }

    public function refreshSchools(): void
    {
        $this->schools = $this->getSchools();
    }

    public function updatedRecordsPerPage(): void
    {
        UserConfig::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'header' => $this->dto['header'],
                'property' => 'recordsPerPage',
            ],
            [
                'value' => $this->recordsPerPage,
            ]
        );
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    protected function saveSortParameters(): void
    {
        UserSort::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'header' => $this->dto['header']
            ],
            [
                'column' => $this->sortCol,
                'asc' => $this->sortAsc,
                'label' => $this->sortColLabel,
            ]
        );
    }


    protected function setFirstTimer($header): void
    {
        $pageView = PageView::firstOrCreate(
            [
                'header' => $header,
                'user_id' => auth()->id(),
            ],
            [
                'view_count' => 0,
            ]
        );

        $this->firstTimer = ($pageView->view_count) ? 'false' : 'true';

        $pageView->update([
            'view_count' => ($pageView->view_count + 1)
        ]);
    }

    protected function getHeader(array $dto): void
    {
        $this->header = $dto['header'];
    }

    protected function getPageInstructions(array $dto): void
    {
        $this->pageInstructions = $dto['instructions'];
    }

    public function getParticipatingClassOfs(): array
    {
        return DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->where('version_id', UserConfig::getValue('versionId'))
            ->where('status', 'registered')
            ->distinct('students.class_of')
            ->orderBy('students.class_of')
            ->select('students.class_of')
            ->pluck('students.class_of', 'students.class_of')
            ->toArray();
    }

    public function getParticipatingSchools(): array
    {
        return DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->where('version_id', UserConfig::getValue('versionId'))
            ->where('status', 'registered')
            ->distinct('school_id')
            ->select(DB::raw('LEFT(schools.name,10) AS shortName'), 'schools.id')
            ->pluck('shortName', 'schools.id')
            ->toArray();
    }

    public function getParticipatingVoiceParts(): array
    {
        return DB::table('candidates')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->where('version_id', UserConfig::getValue('versionId'))
            ->where('status', 'registered')
            ->distinct('school_id')
            ->select('voice_parts.id', 'voice_parts.abbr', 'voice_parts.order_by')
            ->orderBy('voice_parts.order_by')
            ->pluck('voice_parts.abbr', 'voice_parts.id')
            ->toArray();
    }

    /**
     * Placeholder for troubleshooting
     * @return void
     */
    protected function troubleShooting()
    {
        $res = DB::table('candidates')
            ->join('schools', 'schools.id', '=', 'candidates.school_id')
            ->where('version_id', UserConfig::getValue('versionId'))
            ->where('status', 'registered')
            ->distinct('school_id')
            ->select(DB::raw('LEFT(schools.name,10) AS shortName'), 'schools.id')
            ->pluck('shortName', 'schools.id')
            ->toArray();
        dd($res);
    }


}
