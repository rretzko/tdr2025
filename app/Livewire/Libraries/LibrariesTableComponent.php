<?php

namespace App\Livewire\Libraries;

use App\Livewire\BasePage;
use App\Livewire\Forms\LibraryForm;
use App\Services\CoTeachersService;
use App\Services\MakeLibraryService;
use App\Services\RemoveLibraryService;
use Illuminate\Support\Facades\DB;

class LibrariesTableComponent extends BasePage
{
    public array $columnHeaders = [];
    public bool $displayForm = false;
    public LibraryForm $form;

    public function mount(): void
    {
        parent::mount();

        //every user must have a home library
        new MakeLibraryService();

        $this->columnHeaders = $this->getColumnHeaders();
        $this->schools = $this->addHomeLibrary();

        $this->form->setTeacher();
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name', 'sortBy' => 'name'],
            ['label' => 'school', 'sortBy' => 'school'],
        ];

    }

    /** END OF PUBLIC FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

    private function addHomeLibrary()
    {
        $this->schools[0] = 'Home Library';
        return $this->schools;
    }

    public function render()
    {
        return view('livewire..libraries.libraries-table-component',
            [
                'rows' => $this->getRows(),
            ]);
    }

    private function getRows(): array
    {
        $coTeachers = CoTeachersService::getCoTeachersIds();

        return DB::table('libraries')
            ->leftJoin('schools', 'libraries.school_id', '=', 'schools.id')
            ->whereIn('libraries.teacher_id', $coTeachers)
            ->select('libraries.id', 'libraries.name',
                'schools.id AS schoolId', 'schools.name AS schoolName')
            ->orderBy('libraries.name')
            ->get()
            ->toArray();
    }

    public function clickForm(): void
    {
        $this->displayForm = (!$this->displayForm);
    }

    public function edit(int $libraryId): void
    {
        $this->form->setLibrary($libraryId);
        $this->displayForm = (!$this->displayForm);
    }

    public function remove(int $libraryId): void
    {
        new RemoveLibraryService($libraryId);
    }

    public function save(): void
    {
        if ($this->form->save()) {
            $this->form->resetVars();
            $this->displayForm = (!$this->displayForm);
        }
    }


}
