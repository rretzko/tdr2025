<?php

namespace App\Livewire\Programs;

use App\Livewire\BasePage;
use App\Livewire\Forms\ProgramSelectionForm;
use App\Models\Ensembles\Ensemble;
use App\Models\Libraries\Items\Components\Artist;
use App\Models\Libraries\Items\LibItem;
use App\Models\Programs\Program;
use App\Models\Programs\ProgramSelection;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\Students\VoicePart;
use App\Models\User;
use App\Services\Programs\EnsembleMemberRosterService;
use App\Services\Programs\ProgramSelectionService;
use App\Services\ReorderConcertSelectionsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;


class ProgramViewComponent extends BasePage
{
    public Program $program;
    public ProgramSelectionForm $form;
    public array $artistTypes = [];
    public bool $displayEnsembleStudentRoster = false;
    public bool $displayNewStudentMemberForm = true; //false;
    public bool $displayUploadStudentMembersForm = false;
    public int $ensembleId = 0;
    public string $ensembleName = '';
    public string $ensembleNameError = '';
    public array $ensembleVoicings = [];
    public array $ensembles = [];
    public array $ensembleStudentRosters = [];
    public int|null $nextProgramId = 0;
    public int|null $previousProgramId = 0;
    public array $resultsArranger = [];
    public array $resultsChoreographer = [];
    public array $resultsComposer = [];
    public array $resultsMusic = [];
    public array $resultsWam = [];
    public array $resultsWords = [];
    public Collection $resultsSelectionTitle;
    public int $schoolId = 0;
    public string $schoolName = '';
    public string $schoolYear = '';
    public array $ensembleStudentMembers = [];
    public string $selectionTitle = '';

    public function mount(): void
    {
        parent::mount();

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
        $this->form->schoolId = $this->schoolId;

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
        }

        $this->calcNextPerformanceOrderBy();
        $this->ensembleStudentMembers = $this->getEnsembleStudentMembers();

        $this->schoolYear = $this->getSchoolYearLong();
    }

    public function render()
    {
        return view('livewire..programs.program-view-component',
            [
                'selections' => $this->getSelectionsTable(),
            ]);
    }

    public function addConcertSelection(): void
    {
        $added = $this->form->add();

        if ($added) {
            $this->resetFormToAdd();
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

    public function clickAddNewMember(): void
    {
        $added = $this->form->addNewEnsembleMember();

        if ($added) {
            $this->form->resetStudentMemberVars();
            $this->reset('displayNewStudentMemberForm');
            $this->displayEnsembleStudentRoster = true;
        }
    }

    public function clickAddNewMemberStay(): void
    {
        /*
         * validate input
         * search for existing student
         *  if not found, create
         *  if found, insert
         * clear variables on $this and $this->form
         * reset defaults if needed
         * persist current form display
         */
    }

    public function clickArtist(string $type, int $artistId)
    {
        $artist = Artist::find($artistId);
    }

    public function clickSelection(int $selectionId): void
    {
        $this->form->setVars($selectionId);
    }

    public function clickTitle(int $libItemId): void
    {
        $programSelection = ProgramSelection::create(
            [
                'program_id' => $this->dto['programId'],
                'lib_item_id' => $libItemId,
                'ensemble_id' => $this->form->ensembleId,
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
            : $this->reset('ensembleId', 'ensembleName');
    }

    public function remove(int $programSelectionId): void
    {
        ProgramSelection::find($programSelectionId)->delete();

        //reset program_selection->order_by to be in sequential order
        new ReorderConcertSelectionsService($this->program->id);

        //reset form variables for the next new selection
        $this->form->resetVars();
    }

    public function resetFormToAdd(): void
    {
        $this->form->resetVars();
        $this->reset('selectionTitle', 'resultsSelectionTitle', 'ensembleName');
        $this->calcNextPerformanceOrderBy();
    }

    public function setDisplayEnsembleStudentRoster(int $ensembleId): void
    {
        $this->ensembleId = $ensembleId;
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
            $this->form->email = $this->makeUniqueEmail();
        }
    }

    public function updatedFormLastName(): void
    {
        if ((!$this->form->email) && ($this->form->firstName)) {
            $this->form->email = $this->makeUniqueEmail();
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

        $this->form->title = $this->selectionTitle;
    }

    public function updateProgramSelection(): void
    {
        $updated = $this->form->update();

        if ($updated) {
            $this->resetFormToAdd();
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
        return [];
    }

    public function getEnsembleVoicings(): array
    {
        return VoicePart::query()
            ->where('order_by', '>', 0)
            ->orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();
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

    private function makeUniqueEmail(): string
    {
        $domain = '@studentFolder.info';
        $firstInitial = strtolower($this->form->firstName[0]);
        $lastName = strtolower($this->form->lastName);
        $suffix = 0;

        do {
            $email = $firstInitial.$lastName;
            if ($suffix > 0) {
                $email .= $suffix;
            }
            $email .= $domain;
            $exists = User::query()->where('email', $email)->exists();
            $suffix++;
        } while ($exists);

        return $email;
    }
}
