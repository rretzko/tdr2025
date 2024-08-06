<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Participations\Registrant;
use App\Models\Events\Versions\Version;
use App\Models\Schools\Teacher;
use App\Models\UserConfig;
use App\Services\ConvertToUsdService;

class EstimateComponent extends BasePage
{
    public array $columnHeaders = [];
    public float $registrationFee = 0.00;
    public string $selectedTab = 'estimate';
    public array $tabs = [];

    public function mount(): void
    {
        parent::mount();

        $this->columnHeaders = $this->getColumnHeaders();
        $this->registrationFee = $this->getRegistrationFee();
        $this->sortColLabel = 'users.name';
        $this->tabs = ['estimate', 'payments', 'payPal'];
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name', 'sortBy' => 'name'],
            ['label' => 'voice part', 'sortBy' => 'voicePartDescr'],
            ['label' => 'grade', 'sortBy' => 'grade'],
            ['label' => 'fee', 'sortBy' => null],
        ];
    }

    private function getRegistrationFee(): float
    {
        $fee = Version::find(UserConfig::getValue('versionId'))->fee_registration;

        return ConvertToUsdService::penniesToUsd($fee);
    }

    public function render()
    {
        return view('livewire..events.versions.participations.estimate-component',
            [
                'registrants' => $this->getRegistrantArrayForEstimateForm(),
            ]);
    }

    private function getRegistrantArrayForEstimateForm(): array
    {
        $registrant = new Registrant(
            UserConfig::getValue('schoolId'),
            UserConfig::getValue('versionId')
        );

        return $registrant->getRegistrantArrayForEstimateForm();
    }
}
