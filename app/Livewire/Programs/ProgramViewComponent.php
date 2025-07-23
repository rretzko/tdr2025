<?php

namespace App\Livewire\Programs;

use App\Imports\EnsembleMembersImport;
use App\Livewire\BasePage;
use App\Livewire\Forms\ProgramSelectionForm;
use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Members\Member;
use App\Models\Libraries\Items\Components\Artist;
use App\Models\Libraries\Items\Components\Voicing;
use App\Models\Libraries\Items\LibItem;
use App\Models\Libraries\Library;
use App\Models\Programs\Program;
use App\Models\Programs\ProgramAddendum;
use App\Models\Programs\ProgramSelection;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\Students\VoicePart;
use App\Models\UserFilter;
use App\Services\Programs\AssignSectionOpenerAndClosersService;
use App\Services\Programs\EnsembleMemberRosterService;
use App\Services\Programs\ProgramSelectionService;
use App\Services\ReorderConcertSelectionsService;
use App\Traits\MakeUniqueEmailTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Enums\Difficulty;
use App\Enums\Level;


class ProgramViewComponent extends BasePage
{

    use WithFileUploads;
    use WithPagination;
    use MakeUniqueEmailTrait;

    //enums
    public Difficulty $difficulty;
    public Level $level;

    public Program $program;
    public ProgramSelectionForm $form;
    public array $artistTypes = [];
    public bool $displayEnsembleStudentRoster = false;
    public bool $displayNewStudentMemberForm = false;
    public bool $displayUploadStudentMembersForm = false;
    public string $ensembleName = '';
    public string $ensembleNameError = '';
    public array $ensembleVoicings = [];
    public array $ensembles = [];
    public $ensembleStudentRoster;
    public $fileUploadMessage = '';
    public array $levels = [];
    public int|null $nextProgramId = 0;
    public int|null $previousProgramId = 0;
    public array $resultsArranger = [];
    public array $resultsChoreographer = [];
    public array $resultsComposer = [];
    public array $resultsMusic = [];
    public array $resultsWam = [];
    public array $resultsWords = [];
    public Collection|string $resultsSelectionTitle;
    public int $schoolId = 0;
    public string $schoolName = '';
    public string $schoolYearLong = '';
    public string $selectionTitle = '';
    public int $teacherId = 0;
    public $uploadedFileContainer; //used as container for uploaded file
    public int $uploadedMaxFileSize = 400000; //4MB
    public bool $uploadedMaxFileSizeExceeded = false;
    public string $uploadedMaxFileSizeExceededMessage = 'This file exceeds the 4MB file size limit.';
    public string $uploadTemplateUrl = '';
    public array $voicings = [];

    public function mount(): void
    {
        parent::mount();

        $this->difficulty = Difficulty::Easy;
        $this->level = Level::HighSchool;

        $this->artistTypes = [
            'composer',
            'arranger',
            'wam',
            'music',
            'words',
            'choreographer'
        ];
        $this->program = Program::find($this->dto['programId']);
        $this->ensembles = $this->getEnsembles();
        $this->schoolId = $this->program->school_id;
        $this->schoolName = School::find($this->schoolId)->name;
        $this->teacherId = $this->getTeacherId();
        $this->form->schoolId = $this->schoolId;
        $this->form->libraryId = $this->getLibraryId();
        $this->form->organizedBy = $this->program->organized_by;

        //prev/next buttons
        $this->nextProgramId = $this->calcNextPrevProgramId(true);
        $this->previousProgramId = $this->calcNextPrevProgramId(false);

        //form defaults
        if (count($this->ensembles)) {
            $this->form->ensembleId = array_key_first($this->ensembles);
            $this->form->programId = $this->dto['programId'];
            $this->ensembleVoicings = $this->getEnsembleVoicings();
            $this->form->voicePartId = array_key_first($this->ensembleVoicings);
            $this->form->gradeClassOf = $this->program->school_year; //default value
            $this->form->schoolYear = $this->program->school_year;
            $this->ensembleName = Ensemble::find(array_key_first($this->ensembles))->name;
            $this->form->ensembleName = '';
            $this->form->teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        }

        $this->calcNextPerformanceOrderBy();

        $this->schoolYearLong = $this->getSchoolYearLong();

        $this->uploadTemplateUrl = \Storage::disk('s3')->url('templates/ensembleStudentRosterTemplate.csv');

        $this->voicings = $this->getVoicings();
    }

    public function render()
    {
        return view('livewire..programs.program-view-component',
            [
                'selections' => $this->getSelectionsTable(),
                'ensembleStudentMembers' => $this->getEnsembleStudentMembers()
            ]);
    }

    public function addConcertSelection(): void
    {
        $added = $this->form->add();

        if ($added) {

            //refresh the voicings array
            if (strlen($this->form->voicingDescr)) {
                $this->voicings = $this->getVoicings();
            }

            $this->resetFormToAdd();
            new AssignSectionOpenerAndClosersService($this->program->id);
        }
    }

    public function addOneStudent(): void
    {
        $this->reset('displayEnsembleStudentRoster', 'displayUploadStudentMembersForm');
        $this->displayNewStudentMemberForm = true;
    }

    public function changeProgramId(int $programId): void
    {
        $this->redirect('/program/view/'.$programId);
    }

    /*
     * validate input
     * search for existing student
     *  if not found, create
     *  if found, insert
     * clear variables on $this and $this->form
     * reset defaults
     * display ensemble roster
     */
    public function clickAddNewMember(): void
    {
        /*
         * validate input
         * search for existing student
         *  if not found, create
         *  if found, insert
         * clear variables on $this and $this->form
         * reset defaults
         * persist current form display
         */
        $added = $this->form->addNewEnsembleMember();

        if ($added) {
            $this->form->resetStudentMemberVars();
            $this->reset('displayNewStudentMemberForm');
            $this->displayEnsembleStudentRoster = true;
            $this->resetStudentFilters();
        }
    }

    public function clickAddNewMemberStay(): void
    {
        $this->clickAddNewMember();

        //undo previous settings and persist current page
        $this->reset('displayEnsembleStudentRoster');
        $this->displayNewStudentMemberForm = true;

    }

    public function clickImportNewMembers(): void
    {
        $this->reset('fileUploadMessage', 'uploadedMaxFileSizeExceeded');

        //check size
        $fileSize = $this->uploadedFileContainer->getSize();
        Log::info('fileSize: '.$fileSize);
        //early exit if fileSize exceeds maxFileSIze
        if ($fileSize > $this->uploadedMaxFileSize) {
            $this->uploadedMaxFileSizeExceeded = true;
        } else {
            Log::info('fileSize is good.');
            //store the file on a s3 disk
            $s3Path = 'ensembles/memberships';
            $fileName = 'test'.rand(1000, 3000).'.csv';
            Log::info('fileName: '.$fileName);
            $storedFileName = $this->uploadedFileContainer->storePubliclyAs($s3Path, $fileName, 's3');
            Log::info('storedFileName: '.$storedFileName);
            if ($storedFileName) {
                try {
                    Excel::import(
                        new EnsembleMembersImport,
                        $storedFileName,
                        's3',
                        \Maatwebsite\Excel\Excel::CSV);
                    Log::info('Import completed successfully, continuing...');
                } catch (\Exception $e) {
                    Log::error('Excel import failed: '.$e->getMessage());
                }
                $this->reset('uploadedFileContainer');
                $this->resetStudentFilters();
                $this->displayEnsembleStudentRoster();
            } else {
                Log::info('No file was uploaded.');
            }
        }


    }

    public function clickArtist(string $type, int $artistId)
    {
        $artist = Artist::find($artistId);
    }

    public function clickSelection(int $selectionId): void
    {
        $this->form->setVars($selectionId);

        new AssignSectionOpenerAndClosersService($this->program->id);
    }

    public function clickTitle(int $libItemId): void
    {
        $isEnsemble = Program::find($this->form->programId)->isOrganizedByEnsemble();

        $programSelection = ProgramSelection::create(
            [
                'program_id' => $this->form->programId,
                'lib_item_id' => $libItemId,
                'ensemble_id' => $isEnsemble ? $this->form->ensembleId : null,
                'act_id' => $isEnsemble ? null : $this->form->actId,
                'order_by' => $this->form->performanceOrderBy,
            ]
        );

        $this->clickSelection($programSelection->id);
    }

    public function hideEnsembleStudentRoster(bool $addingStudent = false): void
    {
        $this->reset('displayEnsembleStudentRoster', 'displayNewStudentMemberForm', 'displayUploadStudentMembersForm');

        ($addingStudent)
            ? $this->displayEnsembleStudentRoster = true
            : $this->reset('displayNewStudentMemberForm', 'displayUploadStudentMembersForm',
            'displayEnsembleStudentRoster',
            'ensembleName');
    }

    public function remove(int $programSelectionId): void
    {
        //remove any dependent program addendums
        ProgramAddendum::query()
            ->where('program_selection_id', $programSelectionId)
            ->delete();

        //remove the program selection
        ProgramSelection::find($programSelectionId)->delete();

        //reset program_selection->order_by to be in sequential order
        new ReorderConcertSelectionsService($this->program->id);

        //reset form variables for the next new selection
        $this->form->resetVars();

        //reset opener and closers
        new AssignSectionOpenerAndClosersService($this->program->id);
    }

    public function removeEnsembleMember(int $ensembleMemberId): void
    {
        Member::find($ensembleMemberId)->delete();
    }

    public function resetFormToAdd(): void
    {
        $this->form->resetVars();
        $this->reset('selectionTitle', 'resultsSelectionTitle', 'ensembleName');
        $this->calcNextPerformanceOrderBy();
    }

    public function setDisplayEnsembleStudentRoster(int $ensembleId): void
    {
        $this->form->ensembleId = $ensembleId;
        $this->ensembleName = Ensemble::find($ensembleId)->name;

        $service = new EnsembleMemberRosterService($ensembleId, $this->program->school_year);
        $this->ensembleStudentRoster = $service->getStudents();

        $this->displayEnsembleStudentRoster = true;
    }

    /**
     * Method activates when user enters a new ensemble name into ProgramSelectionForm::ensembleName field
     * if the ensemble name exists, return failure message to user, else
     * - make a new Ensemble with default values
     * - populate $form->ensembleId with new ensemble id
     * @return void
     */
    public function updatedEnsembleName(): void
    {
        $this->reset('ensembleNameError');

        if ($this->ensembleNameUnique()) {

            $teacherId = Teacher::where('user_id', auth()->id())->first()->id;

            $grades = GradesITeach::query()
                ->where('school_id', $this->schoolId)
                ->where('teacher_id', $teacherId)
                ->pluck('grade')
                ->toArray();

            $abbr = Ensemble::makeEnsembleNameAbbreviation($this->ensembleName);

            $ensemble = Ensemble::create(
                [
                    'name' => $this->ensembleName,
                    'short_name' => substr($this->ensembleName, 0, 16),
                    'school_id' => $this->schoolId,
                    'teacher_id' => $teacherId,
                    'abbr' => $abbr,
                    'description' => $this->ensembleName.' description',
                    'active' => 1,
                    'grades' => implode(',', $grades)
                ]
            );

            $this->form->ensembleId = $ensemble->id;
            $this->ensembles = $this->getEnsembles();
        } else {
            $this->ensembleNameError = 'The ensemble name <b>'.$this->ensembleName.'</b> already exists and has been selected above.';
            $this->form->ensembleId = $this->getDuplicateEnsembleNameId();
            $this->reset('ensembleName');
        }
    }

    public function updatedFormFirstName(): void
    {
        if ((!$this->form->email) && ($this->form->lastName)) {
            $this->form->email = $this->makeUniqueEmail($this->form->firstName, $this->form->lastName);
        }
    }

    public function updatedFormLastName(): void
    {
        if ((!$this->form->email) && ($this->form->firstName)) {
            $this->form->email = $this->makeUniqueEmail($this->form->firstName, $this->form->lastName);
        }
    }

    public function updatedSelectionTitle(): void
    {
        $this->resultsSelectionTitle = LibItem::query()
            ->join('lib_titles', 'lib_items.lib_title_id', '=', 'lib_titles.id')
            ->where('lib_titles.title', 'LIKE', '%'.$this->selectionTitle.'%')
            ->select('lib_items.*', 'lib_titles.title')
            ->orderBy('lib_titles.title')
            ->get();

        if ($this->resultsSelectionTitle->isEmpty()) {
            $this->resultsSelectionTitle = '<div>No selection found with a title like "'.$this->selectionTitle.'".</div>';
            $this->resultsSelectionTitle .= '<div>You can add this selection in the ';
            $this->resultsSelectionTitle .= '<a href="/libraries" class="text-blue-500">Libraries</a> ';
            $this->resultsSelectionTitle .= ' module.</div>';
        }

        $this->form->title = $this->selectionTitle;
    }

    public function updateProgramSelection(): void
    {
        $updated = $this->form->update();

        if ($updated) {
            $this->resetFormToAdd();
            new AssignSectionOpenerAndClosersService($this->program->id);
        }
    }

    public function uploadStudents(): void
    {
        $this->reset('displayEnsembleStudentRoster', 'displayNewStudentMemberForm');
        $this->displayUploadStudentMembersForm = true;
    }

    private function calcNextPerformanceOrderBy(): void
    {
        $this->form->performanceOrderBy = ProgramSelection::where('program_id',
                $this->dto['programId'])->max('order_by') + 1;
    }

    private function calcNextPrevProgramId(bool $next): int|null
    {
        $dt = $this->program->performance_date;
        $operator = ($next) ? '>' : '<';

        $query = Program::query()
            ->where('performance_date', $operator, $dt);

        if ($next) {
            // Next program: earliest date greater than $dt
            $query->orderBy('performance_date', 'asc');
        } else {
            // Previous program: latest date less than $dt
            $query->orderBy('performance_date', 'desc');
        }

        return $query->value('id');
    }

    /**
     * Set vars to display the ensemble student roster
     */
    private function displayEnsembleStudentRoster(): void
    {
        $this->displayEnsembleStudentRoster = true;
        $this->displayUploadStudentMembersForm = false;
        $this->displayNewStudentMemberForm = false;
    }

    private function ensembleNameUnique(): bool
    {
        return !Ensemble::query()
            ->where('school_id', $this->schoolId)
            ->where('name', $this->ensembleName)
            ->exists();
    }

    private function getDuplicateEnsembleNameId(): int
    {
        return Ensemble::query()
            ->where('school_id', $this->schoolId)
            ->where('name', $this->ensembleName)
            ->value('id');
    }

    private function getEnsembles(): array
    {
        return Ensemble::query()
            ->where('school_id', $this->program->school_id)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    private function getEnsembleStudentMembers(): array
    {
        //early exit
        if (!$this->form->ensembleId) {
            return [];
        }

        return Member::query()
            ->join('students', 'students.id', '=', 'ensemble_members.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'ensemble_members.voice_part_id')
            ->where('ensemble_id', $this->form->ensembleId)
            ->where('school_year', $this->program->school_year)
            ->select('ensemble_members.*',
                'users.name', 'users.last_name', 'users.first_name as firstName',
//                DB::raw("CONCAT(users.name, ' (', voice_parts.abbr, '), ', (12 - (ensemble_members.school_year - students.class_of))) AS studentData")
                DB::raw("CONCAT(users.name, ' (', voice_parts.abbr, ') ', (12 - (students.class_of - ensemble_members.school_year))) AS studentData")
            )
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get()
            ->toArray();
    }

    public function getEnsembleVoicings(): array
    {
        return VoicePart::query()
            ->where('order_by', '>', 0)
            ->orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();
    }

    private function getLibraryId(): int
    {
        return Library::query()
            ->where('school_id', $this->schoolId)
            ->where('teacher_id', $this->teacherId)
            ->value('id');
    }

    private function getSchoolYearLong(): string
    {
        $end = $this->program->school_year;
        $start = $end - 1;

        return $start.' - '.$end;
    }

    private function getSelectionsTable(): string
    {
        $service = new ProgramSelectionService($this->dto['programId'], 'default');

        return $service->getTable();
    }

    private function getTeacherId(): int
    {
        return Teacher::query()
            ->where('user_id', auth()->id())
            ->value('id');
    }

    private function getVoicings(): array
    {
        return Voicing::query()
            ->where('category', 'choral')
            ->orderBy('descr')
            ->pluck('descr', 'id')
            ->toArray();
    }

    /**
     * When adding new members, member data (ex. classOf, voicePart, SchoolId)
     * may not match existing student filters and would not be included when the user
     * opens the Students module.
     * This method deletes any existing filters for the Students module which will then
     * be refreshed completely to include the new data.
     * @return void
     */
    private function resetStudentFilters(): void
    {
        UserFilter::query()
            ->where('user_id', auth()->id())
            ->where('header', 'students')
            ->delete();
    }
}
