<?php

declare(strict_types=1);

namespace App\Livewire\Events\Versions;

use App\Services\RegistrationStatsChartService;
use Livewire\Component;

class RegistrationStatsChartComponent extends Component
{
    public int $versionId;

    public function render()
    {
        $service = new RegistrationStatsChartService($this->versionId);
        $chartData = $service->getChartData();

        return view('livewire.events.versions.registration-stats-chart-component', [
            'chartData' => $chartData,
        ]);
    }
}
