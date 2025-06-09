<?php

namespace App\Livewire\Forms;

use App\Models\Programs\Program;
use App\Models\Tag;
use Livewire\Attributes\Validate;
use Livewire\Form;
use phpDocumentor\Reflection\Types\Integer;

class ProgramNewForm extends Form
{
    public string $performanceDate = "";
    public string $programSubtitle = "";
    public string $programTitle = "";
    public int $schoolId = 0;
    public int $schoolYear = 0;
    public string|int $sysId = '';
    public string $tags = '';

    public function save(): bool
    {
        $this->validate([
            'performanceDate' => 'required',
            'programTitle' => 'required',
            'schoolId' => 'required|integer|exists:schools,id',
            'schoolYear' => 'required|integer|min:1960|max:2099',
        ]);

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

}
