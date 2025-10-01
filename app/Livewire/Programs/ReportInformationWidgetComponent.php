<?php

namespace App\Livewire\Programs;

use Livewire\Component;

class ReportInformationWidgetComponent extends Component
{
    public string $test = 'test';
    #[On('updateReportInformation')]
    public function updateReportInformation(int $schoolYear): void
    {
        $this->test = $schoolYear;
    }

    public function render()
    {
        return view('livewire.programs.report-information-widget-component');
    }
}
