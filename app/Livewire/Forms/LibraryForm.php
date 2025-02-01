<?php

namespace App\Livewire\Forms;

use App\Models\Libraries\Library;
use App\Models\Schools\Teacher;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LibraryForm extends Form
{
    #[Validate('required')]
    public string $name = '';
    #[Validate('required')]
    #[Validate('int')]
    public int $schoolId = 0;
    public int $sysId = 0;
    public int $teacherId = 0;

    public function resetVars(): void
    {
        $this->name = '';
        $this->schoolId = 0;
        $this->sysId = 0;
    }

    public function save(): mixed
    {
        return $this->sysId
            ? $this->update()
            : $this->add();
    }

    public function update(): bool
    {
        $library = Library::find($this->sysId);
        return $library->update(
            [
                'school_id' => $this->schoolId,
                'name' => $this->name,
            ]
        );
    }

    public function add(): Library
    {
        return Library::create(
            [
                'name' => $this->name,
                'school_id' => $this->schoolId,
                'teacher_id' => $this->teacherId,
            ]
        );
    }

    public function setLibrary(int $libraryId): void
    {
        $library = Library::find($libraryId);
        $this->sysId = $library->id;
        $this->name = $library->name;
        $this->schoolId = $library->school_id;
    }

    public function setTeacher(): void
    {
        $this->teacherId = Teacher::where('user_id', auth()->id())->first()->id;
    }
}
