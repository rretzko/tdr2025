<?php

namespace App\Livewire\Forms;

use App\Models\Libraries\Library;
use App\Models\Libraries\LibPerusalCopy;
use App\Models\Schools\Teacher;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LibraryForm extends Form
{
    #[Validate('required')]
    public string $name = '';
    public bool $perusalUseItemId = true;
    public array $perusalItemTypes = [];
    #[Validate('required')]
    #[Validate('int')]
    public int $schoolId = 0;
    public int $sysId = 0;
    public int $teacherId = 0;

    public function resetVars(): void
    {
        $this->name = '';
        $this->perusalItemTypes = [];
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
        $this->updateLibPerusalCopies($library);
        return $library->update(
            [
                'school_id' => $this->schoolId,
                'name' => $this->name,
            ]
        );
    }

    public function add(): Library
    {
        $library = Library::create(
            [
                'name' => $this->name,
                'school_id' => $this->schoolId,
                'teacher_id' => $this->teacherId,
            ]
        );

        $this->updateLibPerusalCopies($library);

        return $library;
    }

    public function setLibrary(int $libraryId): void
    {
        $library = Library::find($libraryId);
        $this->sysId = $library->id;
        $this->name = $library->name;
        $this->schoolId = $library->school_id;
        $this->perusalItemTypes = [];

        $libPerusalCopy = LibPerusalCopy::query()
            ->where('library_id', $libraryId)
            ->where('teacher_id', $this->teacherId)
            ->first();

        if ($libPerusalCopy) {
            $types = ['octavo', 'medley', 'book', 'digital', 'cd', 'dvd', 'cassette', 'vinyl'];
            foreach ($types as $type) {
                if ($libPerusalCopy->$type) {
                    $this->perusalItemTypes[] = $type;
                }
            }
        }
    }

    public function setTeacher(): void
    {
        $this->teacherId = Teacher::where('user_id', auth()->id())->first()->id;
    }

    private function updateLibPerusalCopies(Library $library): void
    {
        //early exit
        if ($this->name === 'Home Library') {
            return;
        }

        $types = ['octavo', 'medley', 'book', 'digital', 'cd', 'dvd', 'cassette', 'vinyl'];

        $attributes = [];
        foreach ($types as $type) {
            $attributes[$type] = in_array($type, $this->perusalItemTypes, true) ? 1 : 0;
        }

        //add location value choice
        $attributes['useItemId'] = $this->perusalUseItemId;

        LibPerusalCopy::updateOrCreate(
            [
                'library_id' => $library->id,
                'teacher_id' => $this->teacherId,
            ],
            $attributes
        );

    }
}
