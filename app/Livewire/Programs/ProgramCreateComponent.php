<?php

namespace App\Livewire\Programs;

use App\Livewire\BasePage;
use App\Livewire\Forms\ProgramNewForm;
use App\Models\Schools\School;
use App\Models\UserConfig;
use App\Services\CalcSeniorYearService;

class ProgramCreateComponent extends BasePage
{
    public ProgramNewForm $form;
    public int $curSeniorYear = 0;
    public array $schools = [];
    public array $schoolYears = [];

    public function mount(): void
    {
        parent::mount();

        $this->schools = $this->getSchools();
        $this->schoolYears = $this->getSchoolYears();

        //set form defaults
        $this->form->schoolId = UserConfig::getValue('schoolId');
        $this->form->schoolYear = $this->curSeniorYear;
        $this->form->sysId = 'new';
    }

    private function getSchoolYears(): array
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

    public function render()
    {
        return view('livewire..programs.program-create-component',
            [

            ]
        );
    }

    public function save(): void
    {
        $saved = $this->form->save();

        if ($saved) {
            $this->redirect(route('programs'));
        }
    }

}
