<?php

namespace App\Livewire\Forms;

use App\Models\PhoneNumber;
use App\Models\Schools\School;
use App\Models\Students\Student;
use App\Models\User;
use App\Services\FindMatchingStudentService;
use App\Services\FormatPhoneService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StudentForm extends Form
{
    /**
     * @var string
     */
    #[Validate('nullable', 'date')]
    public string $birthday = '';
    #[Validate('required')]
    public int $classOf = 0;
    #[Validate('email', message: 'An email address is required.')]
    public string $email;
    #[Validate('required', message: 'First name is required.')]
    public string $first;
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
    #[Validate('required', 'int', 'exists:pronouns,id')]
    public int $pronounId;
    #[Validate('required', 'string')]
    public School $school;
    public string $shirtSize;
    #[Validate('nullable', 'string')]
    public string $suffix;
    public string $sysId = 'new';
    #[Validate('required', 'exists:voice_parts,id')]
    public int $voicePartId = 1; //default soprano

    public function setBirthday(): void
    {
        $this->birthday = Carbon::now()->subYears(18)->format('Y-m-d');
    }

    public function setSchool(School $school): void
    {
        $this->school = $school;
    }

    public function setStudent(): void
    {
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

    public function update(): bool
    {
        $this->validate();

        //look for matching student email, name, and/or phoneMobile
        $service = new FindMatchingStudentService($this);
        $matches = $service->getMatches();

        //display duplicates found advisory: email, phoneMobile
        if (count($matches)) {

            dd('build Duplicates Found advisory');

        } else {
            //save user, student, phones, attach to school, attach to teacher
            $user = User::create(
                [
                    'name' => $this->setFullName(),
                    'email' => $this->email,
                    'pronoun_id' => $this->pronounId,
                    'password' => Hash::make($this->email),
                ]
            );

            $this->addStudent($user);
//            $student = new Student();
//            $student->id = $user->id;
//            $student->user_id = $user->id;
//            $student->class_of = $this->classOf;
//            $student->height = $this->heightInInches;
//            $student->birthday = Carbon::parse($this->birthday)->format('Y-m-d');
//            $student->shirt_size = $this->shirtSize;
//            $student->updated_at = Carbon::now()->format('Y-m-d');
//            $student->created_at = Carbon::now()->format('Y-m-d');

//            Log::info('birthday: ' . $student->birthday);
//            Log::info('updated_at: ' . $student->updated_at);
//            Log::info('created_at: ' . $student->created_at);

            //$student->save();

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

            return $this->properlyUpdated();
        }
    }

    private function addStudent(User $user): void
    {
        Log::info('birthday: '.$this->birthday);

        $student = new Student();
        $student->id = $user->id;
        $student->user_id = $user->id;
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
