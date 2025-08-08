<?php

namespace App\Livewire\Libraries;

use App\Livewire\BasePage;
use App\Livewire\Forms\LibraryForm;
use App\Models\Libraries\LibLibrarian;
use App\Models\Schools\Teacher;
use App\Models\User;
use App\Services\CoTeachersService;
use App\Services\MakeLibraryService;
use App\Services\RemoveLibraryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LibrariesTableComponent extends BasePage
{
    public array $columnHeaders = [];
    public bool $displayForm = false;

    public LibraryForm $form;
    public string $studentLibrarianEmail = '';
    public string $studentLibrarianPassword = '';

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
        $teacher = Teacher::where('user_id', auth()->id())->first();
        $schools = $teacher->schools->sortBy('name');

        $this->schools[0] = 'Home Library';

        foreach ($schools as $school) {
            $this->schools[$school->id] = $school->name;
        }
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

    private function hasLibrarian(): bool
    {
        return LibLibrarian::where('library_id', $this->form->sysId)->exists();
    }

    public function clickForm(): void
    {
        $this->displayForm = (!$this->displayForm);
    }

    /**
     * @throws \Exception
     */
    public function edit(int $libraryId): void
    {
        $this->form->setLibrary($libraryId);
        $this->displayForm = (!$this->displayForm);
        $this->setLibrarian();
    }

    public function regenerateLibrarianPassword(): void
    {
        $librarian = LibLibrarian::where('library_id', $this->form->sysId)->first();
        $librarian->regeneratePassword();
        $this->studentLibrarianPassword = $librarian->password;
        $this->displayForm = true;
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

    private function setLibrarian(): void
    {
        $maxAttempts = 5;
        $attempt = 0;
        while (!$this->hasLibrarian() && ($attempt < $maxAttempts)) {
            $librarian = new LibLibrarian;
            $librarian->make($this->form->name, $this->form->schoolId, $this->form->sysId);

            $attempt++;
        }

        $librarian = LibLibrarian::where('library_id', $this->form->sysId)->first();

        if ($librarian) {
            $this->studentLibrarianEmail = $librarian->email;
            $this->studentLibrarianPassword = $librarian->password;
        } else {
            // Handle the case where librarian creation failed after retries
            throw new \Exception('Failed to set librarian after '.$maxAttempts.' attempts.');
        }

    }

}
