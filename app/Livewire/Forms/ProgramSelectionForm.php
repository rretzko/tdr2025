<?php

namespace App\Livewire\Forms;

use App\Models\Libraries\Items\Components\LibItemRating;
use App\Models\Libraries\Library;
use App\Models\Libraries\LibStack;
use App\Models\Libraries\Items\LibItem;
use App\Models\Programs\Program;
use App\Models\Programs\ProgramAddendum;
use App\Models\Programs\ProgramSelection;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Services\Ensembles\AddNewEnsembleMemberService;
use App\Services\Libraries\CreateLibItemService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProgramSelectionForm extends Form
{
    public int|null $actId = null;
    public string $addendum1 = '';
    public string $addendum2 = '';
    public string $addendum3 = '';
    public string $arranger = '';
    public int $arrangerId = 0;
    public string $artistBlock = '';
    public array $artists = [];
    public string $bgColor = 'bg-gray-100';
    public string $choreographer = '';
    public bool $closer = false;
    public int $choreographerId = 0;
    #[Validate('required')]
    public string $comments = '';
    public string $composer = '';
    public int $composerId = 0;
    public string $difficulty = 'medium';
    public string $email = '';
    public int|null $ensembleId = null;
    public string $firstName = '';
    public string $gradeClassOf = '';
    public string $headerText = 'Add New Program Selection';
    public string $itemType = 'sheet music';
    public string $lastName = '';
    public string $level = 'high-school';

    //placeholder necessary for editProgramSelection.blade.php conditional
    public int $libItemId = 0;
    public int $libTitleId = 0;
    public int $libraryId = 0;
    public string $middleName = '';
    public string $music = '';
    public string $office = '';
    public bool $opener = false;
    public string $organizedBy = 'ensemble';
    public int $performanceOrderBy = 1;
    public int $programId = 0;
    public ProgramSelection $programSelection;
    public int $programSelectionId = 0;
    public int $rating = 1;
    public int $schoolId = 0;
    public int $schoolYear = 0;
    public int $teacherId = 0;
    public string $title = '';
    public string $voicing = ''; //synonym for voicingDescr
    public int $voicePartId = 0;
    public string $voicingDescr = '';
    public int $voicingId = 2;
    public string $wam = '';
    public int $wamId = 0;
    public string $words = '';
    public int $wordsId = 0;
    public int $sysId = 0;

    protected function rules(): array
    {
        return [
            'comments' => 'required',
            'email' => 'required|email',
            'firstName' => 'required',
            'gradeClassOf' => 'required',
            'lastName' => 'required',
            'voicePartId' => 'required',
        ];
    }

    public function add(): bool
    {
        $libItemId = $this->addLibItem();
        $this->addLibItemToLibStack($libItemId);

        $isEnsemble = Program::find($this->programId)->isOrganizedByEnsemble();

        $this->programSelection = ProgramSelection::create(
            [
                'program_id' => $this->programId,
                'lib_item_id' => $libItemId,
                'ensemble_id' => $isEnsemble ? $this->ensembleId : null,
                'act_id' => $isEnsemble ? null : $this->actId,
                'order_by' => $this->performanceOrderBy,
            ]
        );

        $this->programSelectionId = $this->programSelection->id;

        $this->updateProgramAddendums();

        $this->updateRatings($libItemId);

        return (bool) $this->programSelection;
    }

    /**
     * validate input
     * search for existing student
     *  if not found, create
     *  if found, insert
     * clear variables on $this and $this->form
     * reset defaults if needed
     * return to roster display
     */
    public function addNewEnsembleMember(): bool
    {
        $this->validate();

        $service = new AddNewEnsembleMemberService(
            $this->schoolId,
            $this->ensembleId,
            $this->schoolYear,
            $this->firstName,
            $this->middleName,
            $this->lastName,
            $this->email,
            $this->gradeClassOf,
            $this->voicePartId,
            $this->office,
        );

        return $service->added;
    }

    public function resetStudentMemberVars(): void
    {
        $this->email = '';
        $this->firstName = '';
        $this->lastName = '';
        $this->middleName = '';
        $this->gradeClassOf = '';
        $this->voicePartId = 0;
        $this->office = '';
    }

    public function resetVars(): void
    {
        $this->programSelection = new ProgramSelection();
        $this->sysId = 0;

        $this->artistBlock = '';
        $this->bgColor = 'bg-gray-100';
//        $this->ensembleId = 0;  //persist the currently selected ensembleId
        $this->headerText = 'Add Program Selection';
        $this->performanceOrderBy = ProgramSelection::where('program_id', $this->programId)->max('order_by') + 1;
        $this->programSelectionId = 0;
        $this->voicingDescr = '';

        $this->arranger = '';
        $this->choreographer = '';
        $this->composer = '';
        $this->music = '';
        $this->wam = '';
        $this->words = '';

        $this->addendum1 = '';
        $this->addendum2 = '';
        $this->addendum3 = '';

        $this->opener = false;
        $this->closer = false;

        $this->rating = 1;
        $this->level = 'high-school';
        $this->difficulty = 'medium';
        $this->comments = '';

        $program = Program::find($this->programId);
        $this->organizedBy = $program->organized_by;
        $this->actId = $program->isOrganizedByEnsemble() ? null : $this->actId;
    }

    public function setVars(int $programSelectionId): void
    {
        $this->sysId = $programSelectionId; //synonym
        $this->teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $this->programSelection = ProgramSelection::find($programSelectionId);
        $this->libItemId = $this->programSelection->lib_item_id;

        $this->artistBlock = $this->programSelection->artistBlock;
        $this->bgColor = 'bg-green-100';
        $this->ensembleId = $this->programSelection->ensemble_id;
        $this->headerText = 'Edit "<b>'.$this->programSelection->title.'"</b> Concert Selection';
        $this->performanceOrderBy = $this->programSelection->order_by;
        $this->programSelectionId = $programSelectionId;
        $this->voicingDescr = $this->programSelection->voicing;
        /** @todo reconcile coding difference between this and Libraries add/update item */
        $this->voicing = $this->voicingDescr;
        $this->opener = $this->programSelection->opener;
        $this->closer = $this->programSelection->closer;

        $this->setAddendumVars($programSelectionId);

        $this->setRatingVars($programSelectionId);

        if (empty($this->programSelection->comments)) {
            $this->comments = 'add new selection';
        }

        $this->organizedBy = Program::find($this->programSelection->program_id)->organized_by;
        $this->actId = ($this->organizedBy === 'act')
            ? $this->actId ?? 1
            : null;
    }

    public function update(): bool
    {
        $this->validate([
            'comments' => 'required',
        ]);

        $this->updateProgramAddendums();

        $this->updateRatings($this->libItemId);

        $isEnsemble = Program::find($this->programId)->isOrganizedByEnsemble();

        return $this->programSelection->update(
            [
                'act_id' => $isEnsemble ? null : $this->actId,
                'ensemble_id' => $isEnsemble ? $this->ensembleId : null,
                'order_by' => $this->performanceOrderBy,
                'opener' => $this->opener,
                'closer' => $this->closer,
            ]
        );
    }

    private function addLibItemToLibStack(int $libItemId): void
    {
        $library = Library::where('school_id', $this->schoolId)
            ->where('teacher_id', $this->teacherId)
            ->first();

        //create a library if none exists
        if (!$library) {
            $school = School::find($this->schoolId);
            $library = Library::create([
                'school_id' => $this->schoolId,
                'teacher_id' => $this->teacherId,
                'name' => $school->name.' Choral Library',
            ]);
        }

        LibStack::create([
            'library_id' => $library->id,
            'lib_item_id' => $libItemId,
        ]);
    }

    private function setAddendumVars(int $programSelectionId): void
    {
        $addendums = ProgramAddendum::where('program_selection_id', $programSelectionId)->get();
        foreach ($addendums as $key => $addendum) {
            $index = $key + 1;
            $var = 'addendum'.$index;
            $this->$var = $addendum->addendum;
        }
    }

    private function setArtistsArray(): void
    {
        $this->artists['arranger'] = $this->arranger;
        $this->artists['choreographer'] = $this->choreographer;
        $this->artists['composer'] = $this->composer;
        $this->artists['music'] = $this->music;
        $this->artists['wam'] = $this->wam;
        $this->artists['words'] = $this->words;
    }

    private function setRatingVars(int $programSelectionId): void
    {
        $libItemId = ProgramSelection::find($programSelectionId)->lib_item_id;

        $libItemRating = LibItemRating::query()
            ->where('library_id', $this->libraryId)
            ->where('lib_item_id', $libItemId)
            ->where('teacher_id', $this->teacherId)
            ->first();

        if ($libItemRating) {
            $this->comments = $libItemRating->comments;
            $this->difficulty = $libItemRating->difficulty;
            $this->level = $libItemRating->level;
            $this->rating = $libItemRating->rating;
        }
    }

    private function updateProgramAddendums(): void
    {
        //remove all current addendums
        ProgramAddendum::where('program_selection_id', $this->programSelectionId)->delete();

        // Collect non-empty addendums
        $addendums = array_filter([
            $this->addendum1,
            $this->addendum2,
            $this->addendum3,
        ], fn($addendum) => strlen($addendum) > 0);

        if (empty($addendums)) {
            return;
        }

        // Prepare data for batch insert
        $insertData = array_map(fn($addendum) => [
            'program_selection_id' => $this->programSelectionId,
            'addendum' => $addendum,
        ], $addendums);

        // Insert all addendums in one query
        ProgramAddendum::insert($insertData);
    }

    private function updateRatings(int $libItemId): void
    {
        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;

        LibItemRating::updateOrCreate(
            [
                'library_id' => $this->libraryId,
                'lib_item_id' => $libItemId,
                'teacher_id' => $teacherId,
            ],
            [
                'rating' => $this->rating,
                'difficulty' => $this->difficulty,
                'level' => $this->level,
                'comments' => $this->comments,
            ]
        );

    }
}
