<?php

namespace App\Livewire\Forms;

use App\Models\Address;
use App\Models\EmergencyContact;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Recording;
use App\Models\Events\Versions\Participations\Signature;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionConfigRegistrant;
use App\Models\Geostate;
use App\Models\PhoneNumber;
use App\Models\Students\Student;
use App\Models\User;
use App\Services\CalcApplicationRequirements;
use App\Services\CalcClassOfFromGradeService;
use App\Services\CandidateStatusService;
use App\Services\EventEnsemblesVoicePartsArrayService;
use App\Services\FormatPhoneService;
use App\ValueObjects\AddressValueObject;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CandidateForm extends Form
{
    public Candidate $candidate;
    public Student $student;
    public User $user;

    public array $auditionFiles = []; //store uploaded __FILE__
    public int $classOf = 0;
    #[Validate('nullable|email')]
    public string $email = '';
    public bool $eApplication = false;
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
    public array $missingApplicationRequirements = [];
    #[Validate('nullable|string')]
    public string $phoneHome = '';
    #[Validate('required|string')]
    public string $programName = '';
    #[Validate('nullable|string')]
    public string $phoneMobile = '';
    public int $recordingCount = 0;
    public array $recordings = []; //store Recording object details [fileType][url/approved datetime] = value
    #[Validate('nullable|string')]
    public string $shirtSize = '';
    #[Validate('bool')]
    public bool $signatureGuardian = false;
    public bool $signatureStudent = false;
    public bool $signatureTeacher = false;
    public string $status = '';
    public string $statusBg = 'bg-gray-200';
    public string $statusTextColor = 'text-black';
    #[Validate('nullable|string')]
    public string $suffixName = '';
    #[Validate('required|int')]
    public int $voicePartId = 0;

    protected array $propertyMap = [
        'classOf' => 'student',
        'grade' => 'student',
        'height' => 'student',
        'firstName' => 'user',
        'lastName' => 'user',
        'middleName' => 'user',
        'phoneHome' => 'phoneNumber',
        'phoneMobile' => 'phoneNumber',
        'programName' => 'candidate',
        'shirtSize' => 'student',
        'signatureGuardian' => 'signature',
        'signatureStudent' => 'signature',
        'signatureTeacher' => 'signature',
        'suffixName' => 'user',
        'email' => 'user',
        'voicePartId' => 'candidate',
    ];

    public function missingApplicationRequirements(): void
    {
        //clear any artifacts
        $this->missingApplicationRequirements = [];

        //early exit
        if ($this->candidate->id) {

            $service = new CalcApplicationRequirements($this->candidate);

            $this->missingApplicationRequirements = $service->getMissingRequirements();
        }
    }

    public function recordingApprove(string $fileType): bool
    {
        if (array_key_exists($fileType, $this->recordings) &&
            strlen($this->recordings[$fileType]['url'])) {
            $recording = Recording::query()
                ->where('candidate_id', $this->candidate->id)
                ->where('version_id', $this->candidate->version_id)
                ->where('file_type', $fileType)
                ->first();

            $updated = $recording->update([
                'approved' => Carbon::now()->format('Y-m-d H:i:s'),
                'approved_by' => auth()->id(),
                'url' => $this->recordings[$fileType]['url']
            ]);

            if ($updated) {
                $this->recordings[$fileType]['approved'] = Carbon::parse($recording->approved)->format('D, M, y g:i a');
            }

            return $updated;
        }
    }

    public function recordingReject(string $fileType): bool
    {
        if (array_key_exists($fileType, $this->recordings) &&
            strlen($this->recordings[$fileType]['url'])) {
            $recording = Recording::query()
                ->where('candidate_id', $this->candidate->id)
                ->where('version_id', $this->candidate->version_id)
                ->where('file_type', $fileType)
                ->first();

            $url = $recording->url;

            $deleted = $recording->delete();

            if ($deleted) {

                //update the recording array
                $this->setRecordingsArray();
            }

            return $deleted;
        }
    }

    public function recordingSave(string $fileType): bool
    {
        return (bool) Recording::create(
            [
                'version_id' => $this->candidate->version_id,
                'candidate_id' => $this->candidate->id,
                'file_type' => $fileType,
                'uploaded_by' => auth()->id(),
                'url' => $this->recordings[$fileType]['url'],
            ]
        );
    }

    public function setCandidate(int $candidateId): void
    {
        $this->candidate = Candidate::find($candidateId);
        $this->student = $this->candidate->student;
        $this->user = $this->student->user;

        $this->programName = $this->candidate->program_name;
        $this->status = $this->getStatus();
        $this->voicePartId = $this->testVoicePartId($this->candidate->voice_part_id);

        $this->classOf = $this->student->class_of;
        $this->grade = $this->student->grade;
        $this->height = $this->student->height;
        $this->homeAddress = $this->calcHomeAddress($this->student->address);
        $this->phoneHome = $this->student->phoneHome;
        $this->phoneMobile = $this->student->phoneMobile;
        $this->shirtSize = $this->student->shirt_size;

        $this->email = $this->user->email;
        $this->firstName = $this->user->first_name;
        $this->lastName = $this->user->last_name;
        $this->middleName = $this->user->middle_name;
        $this->suffixName = $this->user->suffix_name;

        $this->emergencyContacts = EmergencyContact::query()
            ->where('student_id', $this->student->id)
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

        //recordings array
        $this->setRecordingsArray();

        //eApplications
        $this->eApplication = VersionConfigRegistrant::query()
            ->where('version_id', $this->candidate->version_id)
            ->first()
            ->eapplication;

        //signatures
        $this->setSignatures();

        //check for missing application requirements
        $this->missingApplicationRequirements();

        //set status background and text colors
        $this->formatStatus();
    }

    public function updatedProperty($value, $key): bool
    {
        $this->validateOnly($key);

        if ($key === 'grade') {
            $key = 'classOf';
            $service = new CalcClassOfFromGradeService;
            $value = $service->getClassOf($value);
        }

        $property = $this->propertyMap[$key] ?? null;

        if (!$property) {
            throw new InvalidArgumentException("Invalid property key: $key");
        }

        if (str_starts_with($property, 'phone')) {
            return $this->updatedPhoneNumber($value, $key);
        } elseif (str_starts_with($property, 'signature')) {
            return $this->updatedSignature($value, $key);
        } else {
            return $this->$property->update([Str::snake($key) => $value]);
        }
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

    private function formatStatus(): void
    {
        $statusStyles = [
            'prohibited' => ['bg-red-200', 'text-red-800'],
            'removed' => ['bg-red-200', 'text-red-800'],
            'withdrew' => ['bg-red-200', 'text-red-800'],
            'applied' => ['bg-yellow-200', 'text-yellow-800'],
            'registered' => ['bg-green-200', 'text-green-800'],
        ];

        if (isset($statusStyles[$this->status])) {
            [$this->statusBg, $this->statusTextColor] = $statusStyles[$this->status];
        } else {
            $this->statusBg = 'bg-gray-200';
            $this->statusTextColor = 'text-black';
        }
    }

    private function getStatus(): string
    {
        return CandidateStatusService::getStatus($this->candidate);
    }

    private function setSignatures(): void
    {
        $this->signatureGuardian = (bool) Signature::query()
            ->where('candidate_id', $this->candidate->id)
            ->where('version_id', $this->candidate->version_id)
            ->where('role', 'guardian')
            ->where('signed', 1)
            ->first();

        $this->signatureStudent = (bool) Signature::query()
            ->where('candidate_id', $this->candidate->id)
            ->where('version_id', $this->candidate->version_id)
            ->where('role', 'student')
            ->where('signed', 1)
            ->first();

        $this->signatureTeacher = (bool) Signature::query()
            ->where('candidate_id', $this->candidate->id)
            ->where('version_id', $this->candidate->version_id)
            ->where('role', 'teacher')
            ->where('signed', 1)
            ->first();
    }

    private function setRecordingsArray(): void
    {
        //clear artifacts
        $this->recordings = [];

        $recordings = Recording::query()
            ->where('candidate_id', $this->candidate->id)
            ->where('version_id', $this->candidate->version_id)
            ->get();

        foreach ($recordings as $recording) {

            $this->recordings[$recording->file_type]['url'] = $recording->url;
            $this->recordings[$recording->file_type]['approved'] =
                Carbon::parse($recording->approved)->format('D, M j, y g:i a');
        }
    }

    /**
     * Ensure that $voicePartId is a legitimate choice for the event ensembles
     * @param  int  $voicePartId
     * @return int
     */
    private function testVoicePartId(int $voicePartId): int
    {
        $ensembles = Version::find($this->candidate->version_id)->event->eventEnsembles;

        $service = new EventEnsemblesVoicePartsArrayService($ensembles);

        $voiceParts = $service->getArray();

        if (array_key_exists($voicePartId, $voiceParts)) { //all good
            return $voicePartId;
        } else { //set $this->candidate->voice_part_id to a default value
            $default = array_key_first($voiceParts);
            $this->candidate->update(['voice_part_id' => $default]);
            return $default;
        }
    }

    private function updatedPhoneNumber($value, $key): bool
    {
        //ensure proper formatting of $value if not blank
        $service = new FormatPhoneService;
        $fValue = $service->getPhoneNumber($value);

        $this->$key = $fValue;

        return (bool) PhoneNumber::updateOrCreate(
            [
                'user_id' => $this->user->id,
                'phone_number' => $fValue,
            ],
            [
                'phone_type' => Str::lower(substr($key, 5)),
            ]
        );
    }

    private function updatedSignature($value, $key): bool
    {
        $role = Str::lower(Str::remove('signature', $key)); //i.e. guardian, student, or teacher

        return (bool) Signature::updateOrCreate(
            [
                'version_id' => $this->candidate->version_id,
                'candidate_id' => $this->candidate->id,
                'user_id' => auth()->id(),
                'role' => $role,
            ],
            [
                'signed' => $value,
            ]
        );
    }

}
