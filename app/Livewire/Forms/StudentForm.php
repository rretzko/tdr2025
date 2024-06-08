<?php

namespace App\Livewire\Forms;

use App\Models\PhoneNumber;
use App\Models\Schools\School;
use App\Models\Students\Student;
use App\Models\User;
use App\Services\FindMatchingStudentService;
use App\Services\FormatPhoneService;
use Carbon\Carbon;
use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StudentForm extends Form
{
    public string $address1 = '';
    public string $address2 = '';
    #[Validate('nullable', 'date')]
    public string $birthday = '';
    public string $city = '';
    #[Validate('required')]
    public int $classOf = 0;
    public string $duplicateStudentAdvisory = '';
    #[Validate('email', message: 'An email address is required.')]
    public string $email;
    #[Validate('required', message: 'First name is required.')]
    public string $first;
    public int $geostate_id = 37;
    #[Validate('required', 'min:30', 'max:80')]
    public int $heightInInches = 30; //minimum height
    #[Validate('required', message: 'Last name is required.')]
    public string $last;
    #[Validate('nullable', 'string')]
    public string $middle;
    #[Validate('nullable', 'string', 'min:10')]
    public string $phoneHome;
    #[Validate('nullable', 'string', 'min:10')]
    public string $phoneMobile;
    public string $postalCode = '';
    #[Validate('required', 'int', 'exists:pronouns,id')]
    public int $pronounId;
    #[Validate('required', 'string')]
    public School $school;
    public string $shirtSize;
    public bool $skipDuplicateStudentCheck = false;
    #[Validate('nullable', 'string')]
    public string $suffix;
    public string $sysId = 'new';
    #[Validate('required', 'exists:voice_parts,id')]
    public int $voicePartId = 1; //default soprano

    private array $shirtSizes = [];

    public function resetDuplicateStudentAdvisory()
    {
        $this->reset('duplicateStudentAdvisory');
    }

    public function setBirthday(): void
    {
        $this->birthday = Carbon::now()->subYears(18)->format('Y-m-d');
    }

    public function setSchool(School $school): void
    {
        $this->school = $school;
    }

    public function setShirtSizes(array $shirtSizes): void
    {
        $this->shirtSizes = $shirtSizes;
    }

    public function setStudent(Student $student = null): void
    {
        if ($student) {

            $this->first = $student->user->first_name;
            $this->middle = $student->user->middle_name;
            $this->last = $student->user->last_name;
            $this->suffix = $student->user->suffix_name;
            $this->email = $student->user->email;
            $this->pronounId = $student->user->pronoun_id;
            $this->classOf = $student->class_of;
            $this->voicePartId = $student->voice_part_id;
            $this->heightInInches = $student->height;
            $this->birthday = $student->birthday;
            $this->shirtSize = $student->shirt_size;
            $this->phoneMobile = $student->phoneMobile ?? '';
            $this->phoneHome = $student->phoneHome ?? '';

            if ($student->address) {

                $address = $student->address;

                $this->address1 = $address->address1 ?? '';
                $this->address2 = $address->address2 ?? '';
                $this->city = $address->city ?? '';
                $this->geostate_id = $address->geostate_id ?? 37;
                $this->postalCode = $address->postal_code ?? '';

            }

        } else { //default for testing

            $this->first = 'Bradley';
            $this->middle = 'A.';
            $this->last = 'Eckensberger';
            $this->suffix = 'I';
            $this->email = 'beckensberger@fake.com';
            $this->pronounId = 2;
            $this->classOf = 2028;
            $this->voicePartId = 16;
            $this->heightInInches = 72;
            $this->birthday = '2010-06-04';
            $this->shirtSize = 'xl';
            $this->phoneMobile = '1234567890';
            $this->phoneHome = '2345678901';
        }
    }

    public function update(): bool
    {
        $this->validate([
            'email' => (ValidationRule::unique('users', 'email')),
        ]);

        //look for matching student email, name, and/or phoneMobile
        $service = new FindMatchingStudentService($this);
        $matches = $service->getMatches();

        //display duplicates found advisory: email, phoneMobile
        //unless user clicks the "continue" button on the duplicateStudentAdvisory
        if ((!$this->skipDuplicateStudentCheck) && count($matches)) {

            $this->duplicateStudentAdvisory = 'At least '.count($matches).' student(s) were
            found with matching name and grades.
            <b>Please avoid creating duplicate student records.</b>
            Do you want to continue?
            <div class="mt-2 flex space-x-2">
            <button type="button" wire:click="formContinue" class="bg-green-600 text-white text-xs rounded-full px-2">Continue</button>
            <button type="button" wire:click="formCancel" class="bg-black text-white text-xs rounded-full px-2">Cancel</button>
            </div>';

            return false;

        } else {

            $this->addNewStudent();

            return $this->properlyUpdated();
        }
    }

    public function updateWithoutDuplicateStudentCheck(): void
    {
        $this->skipDuplicateStudentCheck = true;

        $this->update();
    }

    private function addNewStudent(): void
    {
        //save user, student, phones, attach to school, attach to teacher
        $user = User::create(
            [
                'name' => $this->setFullName(),
                'prefix_name' => '',
                'first_name' => $this->first,
                'middle_name' => $this->middle,
                'last_name' => $this->last,
                'suffix_name' => $this->suffix,
                'email' => $this->email,
                'pronoun_id' => $this->pronounId,
                'password' => Hash::make($this->email),
            ]
        );

        $this->addStudent($user);

        $service = new FormatPhoneService();

        PhoneNumber::create(
            [
                'user_id' => $user->id,
                'phone_type' => 'mobile',
                'phone_number' => $service->getPhoneNumber($this->phoneMobile),
            ]
        );

        PhoneNumber::create(
            [
                'user_id' => $user->id,
                'phone_type' => 'home',
                'phone_number' => $service->getPhoneNumber($this->phoneHome),
            ]
        );
    }

    private function addStudent(User $user): void
    {
        Log::info('birthday: '.$this->birthday);

        $student = new Student();
        $student->id = $user->id;
        $student->user_id = $user->id;
        $student->voice_part_id = $this->voicePartId;
        $student->class_of = $this->classOf;
        $student->height = $this->heightInInches;
        $student->birthday = Carbon::parse($this->birthday)->format('Y-m-d');
        $student->shirt_size = $this->shirtSize;
        $student->updated_at = Carbon::now()->format('Y-m-d');
        $student->created_at = Carbon::now()->format('Y-m-d');

        $student->save();

        $student->schools()->attach($this->school);

        $student->teachers()->attach(auth()->id());
    }

    private function properlyUpdated(): bool
    {
        return true;
    }

    private function setFullName(): string
    {
        $middle = strlen($this->middle) ? $this->middle.' ' : '';
        $suffix = strlen($this->suffix) ? ', '.$this->suffix : '';

        return trim($this->first.' '.$middle.$this->last.$suffix);
    }
}