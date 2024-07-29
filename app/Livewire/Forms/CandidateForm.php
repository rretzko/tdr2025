<?php

namespace App\Livewire\Forms;

use App\Models\Address;
use App\Models\EmergencyContact;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Geostate;
use App\ValueObjects\AddressValueObject;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CandidateForm extends Form
{
    public Candidate $candidate;

    public array $auditionFiles = [];
    public int $classOf = 0;
    #[Validate('nullable|email')]
    public string $email = '';
    public array $emergencyContacts = [];
    public array $fileUploads = [];
    #[Validate('required|string')]
    public string $firstName = '';
    #[Validate('required|int')]
    public int $grade = 0;
    #[Validate('nullable|int')]
    public int $height = 0;
    public string $homeAddress = '';
    #[Validate('required|string')]
    public string $lastName = '';
    #[Validate('nullable|string')]
    public string $middleName = '';
    #[Validate('nullable|string')]
    public string $phoneHome = '';
    #[Validate('required|string')]
    public string $programName = '';
    #[Validate('nullable|string')]
    public string $phoneMobile = '';
    public int $recordingCount = 0;
    #[Validate('nullable|string')]
    public string $shirtSize = '';
    #[Validate('bool')]
    public bool $signatures = false;
    #[Validate('required|string')]
    public string $status = '';
    #[Validate('nullable|string')]
    public string $suffixName = '';
    #[Validate('required|int')]
    public int $voicePartId = 0;

    public function setCandidate(int $candidateId): void
    {
        $this->candidate = Candidate::find($candidateId);
        $student = $this->candidate->student;
        $user = $student->user;

        $this->programName = $this->candidate->program_name;
        $this->status = $this->candidate->status;
        $this->voicePartId = $this->candidate->voice_part_id;

        $this->classOf = $student->class_of;
        $this->grade = $student->grade;
        $this->height = $student->height;
        $this->homeAddress = $this->calcHomeAddress($student->address);
        $this->phoneHome = $student->phoneHome;
        $this->phoneMobile = $student->phoneMobile;
        $this->shirtSize = $student->shirt_size;

        $this->email = $user->email;
        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->middleName = $user->middle_name;
        $this->suffixName = $user->suffix_name;

        $this->emergencyContacts = EmergencyContact::query()
            ->where('student_id', $student->id)
            ->select('id AS emergencyContactId',
                'name AS emergencyContactName', 'email AS emergencyContactEmail',
                'phone_home AS emergencyContactPhoneHome', 'phone_mobile AS emergencyContactPhoneMobile',
                'phone_work AS emergencyContactPhoneWork', 'best_phone AS emergencyContactBestPhone')
            ->get()
            ->toArray();

        $this->recordingCount = VersionConfigAdjudication::query()
            ->where('version_id', $this->candidate->version_id)
            ->first()
            ->upload_count;

        //file upload types (ex.scales, solo, quartet, etc.
        $this->fileUploads = explode(',', VersionConfigAdjudication::where('version_id', $this->candidate->version_id)
            ->first()
            ->upload_types);

        $this->auditionFiles['scales'] = 'recordings/42160_scales.mp3';
    }

    private function calcHomeAddress(Address|null $address): string
    {
        //clear any artifacts
        $this->reset('homeAddress');

        if ($address &&
            strlen($address->address1) &&
            strlen($address->city) &&
            is_int($address->geostate_id) &&
            (strlen($address->postal_code) > 4)) {
            return AddressValueObject::getStringVo($address);
        }

        return '';
    }

}
