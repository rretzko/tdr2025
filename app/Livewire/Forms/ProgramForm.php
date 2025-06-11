<?php

namespace App\Livewire\Forms;

use App\Models\Programs\Program;
use App\Models\Tag;
use App\Models\UserConfig;
use Livewire\Attributes\Validate;
use Livewire\Form;
use phpDocumentor\Reflection\Types\Integer;

class ProgramForm extends Form
{
    public Program $program;
    public string $performanceDate = "";
    public string $programSubtitle = "";
    public string $programTitle = "";
    public int $schoolId = 0;
    public int $schoolYear = 0;
    public string|int $sysId = '';
    public string $tags = '';

    public function resetVars(): void
    {
        $this->performanceDate = "";
        $this->programSubtitle = "";
        $this->programTitle = "";
        $this->schoolId = UserConfig::getValue('schoolId');
        //persist the selected school year
//        $this->schoolYear = 0;
        $this->sysId = 'new';
        $this->tags = '';
    }

    public function save(): bool
    {
        $this->validateVars();

        if ($this->programExists()) {
            return false;
        }

        $tags = $this->parseTagIds();

        $program = Program::create([
            'title' => $this->programTitle,
            'subtitle' => $this->programSubtitle,
            'school_id' => $this->schoolId,
            'school_year' => $this->schoolYear,
            'performance_date' => $this->performanceDate,
        ]);

        if ($program && $tags) {
            $program->tags()->attach($tags);
            return true;
        }

        return false;
    }

    public function update(): bool
    {
        $this->validateVars();

        $tags = $this->parseTagIds();

        //if school year is unchanged, update properties OR
        //if the school year HAS changed and no identical program exists for the changed school year, update properties
        //else return false
        if (
            $this->schoolYear == $this->program->school_year ||
            !$this->programExists()
        ) {
            $this->updateProgramObject();
            $this->program->tags()->sync($tags);
        } else {
            return false;
        }


        return true;
    }

    private function parseTagIds(): array
    {
        //early exit
        if (empty($this->tags)) {
            return [];
        }

        $parts = explode(",", $this->tags);
        $tags = collect();

        foreach ($parts as $part) {
            $name = trim(strtolower($part));
            $tags->push(Tag::firstOrCreate(['name' => $name]));
        }

        return $tags->pluck('id')->toArray();
    }

    private function programExists(): bool
    {
        return Program::query()
            ->where('title', $this->programTitle)
            ->where('school_id', $this->schoolId)
            ->where('school_year', $this->schoolYear)
            ->exists();
    }

    private function updateProgramObject(): void
    {
        $this->program->update([
            'title' => $this->programTitle,
            'subtitle' => $this->programSubtitle,
            'school_id' => $this->schoolId,
            'school_year' => $this->schoolYear,
            'performance_date' => $this->performanceDate,
        ]);
    }

    private function validateVars(): void
    {
        $this->validate([
            'performanceDate' => 'required',
            'programTitle' => 'required',
            'schoolId' => 'required|integer|exists:schools,id',
            'schoolYear' => 'required|integer|min:1960|max:2099',
        ]);
    }

}
