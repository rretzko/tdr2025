<?php

namespace App\Livewire\Forms;

use App\Models\Events\Versions\Version;
use App\Services\CalcSeniorYearService;
use App\Services\ConvertToPenniesService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VersionCreateForm extends Form
{
    public float $feeParticipation = 0;
    public float $feeOnSiteRegistration = 0;
    public float $feeRegistration = 0;
    public string $name = 'Test';
    public bool $pitchFilesStudent = false;
    public bool $pitchFilesTeacher = false;
    public string $shortName = '';
    public int $seniorClassId = 2025;
    public string $statusId = 'sandbox';
    public bool $student = false;
    public string $sysId = 'new';
    public bool $teacher = false;
    public string $uploadType = 'none';

    public function add(int $eventId): Version
    {
        return Version::create(
            [
                'epayment_student' => $this->student,
                'epayment_teacher' => $this->teacher,
                'event_id' => $eventId,
                'name' => $this->name,
                'short_name' => $this->shortName,
                'senior_class_of' => $this->seniorClassId,
                'status' => $this->statusId,
                'upload_type' => $this->uploadType,
                'fee_registration' => ConvertToPenniesService::usdToPennies($this->feeRegistration),
                'fee_on_site_registration' => ConvertToPenniesService::usdToPennies($this->feeOnSiteRegistration),
                'fee_participation' => ConvertToPenniesService::usdToPennies($this->feeParticipation),
            ]
        );

    }

    public function setSeniorClassId(): void
    {
        $service = new CalcSeniorYearService();

        $this->seniorClassId = $service->getSeniorYear();
    }

}
