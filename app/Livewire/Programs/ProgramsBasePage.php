<?php

namespace App\Livewire\Programs;

use App\Livewire\BasePage;
use App\Livewire\Forms\ProgramForm;
use App\Models\Programs\Program;
use App\Models\UserConfig;
use App\Services\CalcSeniorYearService;

class ProgramsBasePage extends BasePage
{
    public ProgramForm $form;
    public int $curSeniorYear;
    public Program $program;
    public string $programExistsMessage = '';
    public array $schoolYears = [];
    public array $schools = [];

    public function mount(): void
    {
        $this->schools = $this->getSchools();
        $this->schoolYears = $this->getSchoolYears();

        //set $form defaults
        if (array_key_exists('programId', $this->dto)) {
            $this->program = Program::find($this->dto['programId']);
            $this->form->program = $this->program;
            $this->form->programTitle = $this->program->title;
            $this->form->programSubtitle = $this->program->subtitle;
            $this->form->schoolYear = $this->program->school_year;
            $this->form->schoolId = $this->program->school_id;
            $this->form->tags = implode(',', $this->program->tags->pluck('name')->toArray());
            $this->form->performanceDate = $this->program->performance_date;
        } else {
            $this->form->schoolId = UserConfig::getValue('schoolId');
        }


    }

    protected function getSchoolYears(): array
    {
        $schoolYears = [];
        $service = new CalcSeniorYearService();
        $this->curSeniorYear = $service->getSeniorYear();
        $nextSrYear = $this->curSeniorYear + 1;
        $earliestSrYear = $this->curSeniorYear - 50;

        for ($i = $nextSrYear; $i >= $earliestSrYear; $i--) {
            $endYr = $i + 1;
            $label = "$i-$endYr";
            $schoolYears[$endYr] = $label;
        }

        return $schoolYears;
    }

    /**
     * Reset successMessage and programExistsMessage to blanks
     * whenever a form property is updated
     * @return void
     */
    public function updatedForm(): void
    {
        $this->reset(['successMessage', 'programExistsMessage']);
    }

    protected function updateProgramExistsMessage(): void
    {
        $schoolYearStart = $this->form->schoolYear - 1;
        $schoolYear = $schoolYearStart.'-'.$this->form->schoolYear;
        $this->programExistsMessage = '<b>'.$this->form->programTitle.'</b> program already exists for school year '.$schoolYear.'.';
    }

}
