<?php

namespace App\Livewire\Forms;

use App\Models\Libraries\Library;
use App\Models\Libraries\LibStack;
use App\Models\Libraries\Items\LibItem;
use App\Models\Programs\ProgramAddendum;
use App\Models\Programs\ProgramSelection;
use App\Models\Schools\Teacher;
use App\Services\Ensembles\AddNewEnsembleMemberService;
use App\Services\Libraries\CreateLibItemService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProgramSelectionForm extends Form
{
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
    public string $composer = '';
    public int $composerId = 0;
    public string $email = '';
    public int $ensembleId = 0;
    public string $firstName = '';
    public string $gradeClassOf = '';
    public string $headerText = 'Add New Concert Selection';
    public string $itemType = 'sheet music';
    public string $lastName = '';
    public int $libTitleId = 0;
    public string $middleName = '';
    public string $music = '';
    public string $office = '';
    public bool $opener = false;
    public int $performanceOrderBy = 1;
    public int $programId = 0;
    public ProgramSelection $programSelection;
    public int $programSelectionId = 0;
    public int $schoolId = 0;
    public int $teacherId = 0;
    public string $title = '';
    public int $voicePartId = 0;
    public string $voicing = '';
    public int $voicingId = 2;
    public string $wam = '';
    public int $wamId = 0;
    public string $words = '';
    public int $wordsId = 0;

    protected function rules(): array
    {
        return [
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

        $this->programSelection = ProgramSelection::create(
            [
                'program_id' => $this->programId,
                'lib_item_id' => $libItemId,
                'ensemble_id' => $this->ensembleId,
                'order_by' => $this->performanceOrderBy,
            ]
        );

        $this->programSelectionId = $this->programSelection->id;

        $this->updateProgramAddendums();

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
            $this->gradeClassOf,
            $this->voicePartId,
            $this->office,
        );

        return $service->added;
    }

    public function resetVars(): void
    {
        $this->programSelection = new ProgramSelection();

        $this->artistBlock = '';
        $this->bgColor = 'bg-gray-100';
//        $this->ensembleId = 0;  //persist the currently selected ensembleId
        $this->headerText = 'Add Concert Selection';
        $this->performanceOrderBy = ProgramSelection::where('program_id', $this->programId)->max('order_by') + 1;
        $this->programSelectionId = 0;
        $this->voicing = '';

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
    }

    public function setVars(int $programSelectionId): void
    {
        $this->teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $this->programSelection = ProgramSelection::find($programSelectionId);

        $this->artistBlock = $this->programSelection->artistBlock;
        $this->bgColor = 'bg-green-100';
        $this->ensembleId = $this->programSelection->ensemble_id;
        $this->headerText = 'Edit "<b>'.$this->programSelection->title.'"</b> Concert Selection';
        $this->performanceOrderBy = $this->programSelection->order_by;
        $this->programSelectionId = $programSelectionId;
        $this->voicing = $this->programSelection->voicing;
        $this->opener = $this->programSelection->opener;
        $this->closer = $this->programSelection->closer;

        $this->setAddendumVars($programSelectionId);
    }

    public function update(): bool
    {
        $this->updateProgramAddendums();

        return $this->programSelection->update(
            [
                'ensemble_id' => $this->ensembleId,
                'order_by' => $this->performanceOrderBy,
                'opener' => $this->opener,
                'closer' => $this->closer,
            ]
        );
    }

    private function addLibItem(): int
    {
        $this->setArtistsArray(); //required by CreateLitItemService

        $service = new CreateLibItemService($this, ['sheet music', 'medley']);

        return $service->libItemId;
    }

    private function addLibItemToLibStack(int $libItemId): void
    {
        $library = Library::where('school_id', $this->schoolId)
            ->where('teacher_id', $this->teacherId)
            ->first();

        if ($library) {
            LibStack::create([
                'library_id' => $library->id,
                'lib_item_id' => $libItemId,
            ]);
        }
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
}
