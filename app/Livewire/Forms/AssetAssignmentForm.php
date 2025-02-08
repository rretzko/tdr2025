<?php

namespace App\Livewire\Forms;

use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Members\Member;
use App\Models\Students\Student;
use App\Services\CalcGradeFromClassOfService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AssetAssignmentForm extends Form
{
    public array $assetIds = [];
    public array $assets = [];
    public Ensemble $ensemble;
    public int $ensembleId = 0;
    public string $ensembleStatus = '';
    public string $gradeClassOf = '';
//    public array $inventory = [];
    public string $nameAlpha = '';
    public int $srYear = 0;
    public int $studentId = 0;
    public Student $student;

    public function setStudent(int $studentId, int $ensembleId, int $srYear): void
    {
        $this->studentId = $studentId;
        $this->student = Student::find($this->studentId);
        $this->nameAlpha = $this->student->user->fullNameAlpha;

        $this->srYear = $srYear;

        $this->ensembleId = $ensembleId;
        $this->ensemble = Ensemble::find($this->ensembleId);

        $this->assets = $this->setAssets();
//        $this->inventory = $this->setInventory();
        $this->ensembleStatus = $this->setEnsembleStatus();
        $this->gradeClassOf = $this->setGradeClassOf();
    }

    private function setAssets(): array
    {
        return $this->ensemble->assets->pluck('name', 'id')->toArray();
    }

//    private function setInventory(): array
//    {
//        return [];
//    }

    private function setEnsembleStatus(): string
    {
        return Member::query()
            ->where('student_id', $this->studentId)
            ->where('ensemble_id', $this->ensembleId)
            ->value('status');
    }

    private function setGradeClassOf(): string
    {
        $classOf = $this->student->class_of;
        $grade = ($classOf < $this->srYear)
            ? 'alum'
            : (12 - ($classOf - $this->srYear));

        return $grade . '/' . $classOf;
    }
}
