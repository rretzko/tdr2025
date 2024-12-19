<?php

namespace App\Livewire\Forms;

use App\Models\Events\Event;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigEmergencyContact;
use App\Models\UserConfig;
use App\Services\CalcSeniorYearService;
use App\Services\ConvertToPenniesService;
use App\Services\ConvertToUsdService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VersionProfileForm extends Form
{
    public bool $cloneAdvisory = false;
    public VersionConfigEmergencyContact $emergencyContact;
    public bool $emergencyContactName = false;
    public bool $emergencyContactEmail = false;
    public bool $emergencyContactPhoneMobile = false;
    public string $epaymentVendor = 'none';
    public float $feeEpaymentSurcharge = 0;
    public float $feeParticipation = 0;
    public float $feeOnSiteRegistration = 0;
    public float $feeRegistration = 0;
    public bool $height = false;
    public string $name = '';
    public bool $pitchFilesStudent = false;
    public bool $pitchFilesTeacher = false;
    public string $shortName = '';
    public bool $shirtSize = false;
    public int $seniorClassId = 2025;
    public string $statusId = 'sandbox';
    public bool $student = false;
    public bool $studentHomeAddress = false;
    public string $sysId = 'new';
    public bool $teacher = false;
    public string $uploadType = 'none';

    public function add(): Version
    {
        return Version::create(
            [
                'epayment_student' => $this->student,
                'epayment_teacher' => $this->teacher,
                'epayment_vendor' => $this->epaymentVendor,
                'event_id' => UserConfig::getValue('eventId'),
                'name' => $this->name,
                'short_name' => $this->shortName,
                'senior_class_of' => $this->seniorClassId,
                'status' => $this->statusId,
                'upload_type' => $this->uploadType,
                'fee_registration' => ConvertToPenniesService::usdToPennies($this->feeRegistration),
                'fee_on_site_registration' => ConvertToPenniesService::usdToPennies($this->feeOnSiteRegistration),
                'fee_participation' => ConvertToPenniesService::usdToPennies($this->feeParticipation),
                'fee_epayment_surcharge' => ConvertToPenniesService::usdToPennies($this->feeEpaymentSurcharge),
                'pitch_files_student' => $this->pitchFilesStudent,
                'pitch_files_teacher' => $this->pitchFilesTeacher,
                'student_home_address' => $this->studentHomeAddress,
                'height' => $this->height,
                'shirt_size' => $this->shirtSize,
            ]
        );
    }

    public function setProfile(int $versionId, bool $clone = false): bool
    {
        $version = Version::find($versionId);
        $this->sysId = ($clone ? 'new' : $versionId);
        $this->name = $version->name;
        $this->shortName = $version->short_name;
        $this->seniorClassId = $version->senior_class_of;
        $this->statusId = $version->status;
        $this->uploadType = $version->upload_type;
        $this->epaymentVendor = $version->epayment_vendor;
        $this->feeEpaymentSurcharge = ConvertToUsdService::penniesToUsd($version->fee_epayment_surcharge);
        $this->feeParticipation = ConvertToUsdService::penniesToUsd($version->fee_participation);
        $this->feeOnSiteRegistration = ConvertToUsdService::penniesToUsd($version->fee_on_site_registration);
        $this->feeRegistration = ConvertToUsdService::penniesToUsd($version->fee_registration);
        $this->teacher = $version->epayment_teacher;
        $this->student = $version->epayment_student;
        $this->pitchFilesStudent = $version->pitch_files_student;
        $this->pitchFilesTeacher = $version->pitch_files_teacher;
        $this->studentHomeAddress = $version->student_home_address;
        $this->height = $version->height;
        $this->shirtSize = $version->shirt_size;

        $this->emergencyContact = VersionConfigEmergencyContact::where('version_id',
            $versionId)->first() ?? new VersionConfigEmergencyContact();
        $this->emergencyContactName = $this->emergencyContact->ec_name;
        $this->emergencyContactEmail = $this->emergencyContact->ec_email;
        $this->emergencyContactPhoneMobile = $this->emergencyContact->ec_phone_mobile;

        return true;
    }

    /**
     * If other versions exist for the current event,
     * use the most recent senior_class_of to clone profile values
     * @return void
     */
    public function setProfileClone(): bool
    {
        $event = Event::find(UserConfig::getValue('eventId'));
        $versionId = $event->getCurrentVersion()->id ?: 0;

        if ($versionId) {

            return $this->setProfile($versionId, true);
        }

        return false;
    }

    public function update(int $versionId): Version
    {
        if ($this->sysId === 'new') {

            $version = $this->add();

        } else {

            $version = Version::find($this->sysId);

            $version->update(
                [
                    'epayment_student' => $this->student,
                    'epayment_teacher' => $this->teacher,
                    'epayment_vendor' => $this->epaymentVendor,
                    'event_id' => UserConfig::getValue('eventId'),
                    'name' => $this->name,
                    'short_name' => $this->shortName,
                    'senior_class_of' => $this->seniorClassId,
                    'status' => $this->statusId,
                    'upload_type' => $this->uploadType,
                    'fee_epayment_surcharge' => ConvertToPenniesService::usdToPennies($this->feeEpaymentSurcharge),
                    'fee_registration' => ConvertToPenniesService::usdToPennies($this->feeRegistration),
                    'fee_on_site_registration' => ConvertToPenniesService::usdToPennies($this->feeOnSiteRegistration),
                    'fee_participation' => ConvertToPenniesService::usdToPennies($this->feeParticipation),
                    'pitch_files_student' => $this->pitchFilesStudent,
                    'pitch_files_teacher' => $this->pitchFilesTeacher,
                    'student_home_address' => $this->studentHomeAddress,
                    'height' => $this->height,
                    'shirt_size' => $this->shirtSize,
                ]
            );

            $this->updateEmergencyContact();
        }

        return $version;
    }

    public function setSeniorClassId(): void
    {
        $service = new CalcSeniorYearService();

        $this->seniorClassId = $service->getSeniorYear();
    }

    private function updateEmergencyContact(): void
    {
        $this->emergencyContact->ec_name = $this->emergencyContactName;
        $this->emergencyContact->ec_email = $this->emergencyContactEmail;
        $this->emergencyContact->ec_phone_mobile = $this->emergencyContactPhoneMobile;
        $this->emergencyContact->save();
    }

}
