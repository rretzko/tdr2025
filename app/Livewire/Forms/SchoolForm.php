<?php

namespace App\Livewire\Forms;

use App\Events\WorkEmailChangedEvent;
use App\Models\County;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Schools\SchoolGrade;
use App\Models\Schools\SchoolTeacher;
use App\ValueObjects\SchoolResultsValueObject;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SchoolForm extends Form
{
    public string $advisoryCountyId = '';
    #[Validate('required', message: "A city is required.")]
    public string $city = '';
    #[Validate('required')]
    #[Validate('integer')]
    #[Validate('min:1', message: "You must select a county.")]
    public int $countyId = 0;
    #[Validate('required')]
    public string $email = '';
    public array $gradesITeach = [];
    public array $gradesTaught = [];
    #[Validate('required', message: 'A school name is required.')]
    public string $name = '';
    #[Validate('required', message: 'A zip code is required.')]
    public string $postalCode = '';

    public string $resultsCity = '';
    public string $resultsPostalCode = '';
    public string $resultsName = '';
    public School $school;
    public SchoolTeacher $schoolTeacher;
    public string $sysId = 'new';

    public function rules()
    {
        return [
            'gradesITeach' => ['required', 'array', 'min:1'],
            'gradesTaught' => ['required', 'array', 'min:1'],
        ];
    }

    public function setSchool(School $school): void
    {
        $this->school = $school;

        //nullify 'deleted_at' property if exists
        $this->restoreTrashedRow($school);

        $this->schoolTeacher = SchoolTeacher::firstOrCreate(
            [
                'school_id' => $school->id,
                'teacher_id' => auth()->id(),
            ],
            [
                'email' => $this->email,
                'email_verified_at' => null,
                'active' => 1
            ]
        );

        //set $this variables
        $this->setVars();

        //clear user search results
        $this->reset('resultsCity', 'resultsPostalCode', 'resultsName');
    }

    public function update(): void
    {
        $this->validate();

        if ($this->sysId === 'new') {

            $this->school = $this->addSchool();
            //link school to teacher
            $this->schoolTeacher = $this->addSchoolTeacher($this->school);
        } else {

            $this->school->update(
                [
                    'name' => $this->name,
                    'city' => $this->city,
                    'county_id' => $this->countyId,
                    'postal_code' => $this->postalCode,
                ]
            );
        }

        //delete old and insert new $this->gradesTaught
        $this->school->updateGrades($this->gradesTaught);

        //delete old and insert new $this->gradesITeach
        $this->schoolTeacher->updateGradesITeach($this->gradesITeach, $this->school->id);

        //check for email change
        $emailChanged = $this->checkForEmailChange($this->school);

        if ($emailChanged) {
            $this->updateSchoolTeacherEmail();
        }

        //send verification email if $this->email is NOT gmail, hotmail, aol, etc.
        if ($emailChanged || is_null($this->schoolTeacher->email_verified_at)) {
            $this->sendVerificationEmailIfNeeded();
        }
    }

    public function updatedCity(): void
    {
        $this->reset('resultsCity');
        $min = 4; //minimum number of characters needed to initiate search

        $str = '';

        if (strlen($this->city) > $min) {

            $schools = School::query()
                ->where('city', 'LIKE', '%'.$this->city.'%')
                ->orderBy('name')
                ->orderBy('city')
                ->get();

            if ($schools->count()) {

                $str = '';

                foreach ($schools as $school) {

                    $str .= '<button type="button" wire:click="addSchool('.$school->id.')" class="text-sm text-blue-500 ml-2">'
                        .SchoolResultsValueObject::getVo($school) //"schoolName (city in county, state)"
                        .'</button>';
                }
            } else {

                $str = '<div>No schools found in city "'.$this->city.'".</div>';
            }
        }

        $this->resultsCity = (strlen($this->city) && (strlen($this->city) <= $min))
            ? 'Please enter at least '.($min + 1).' characters'
            : $str;
    }

    public function updatedCountyId(): void
    {
        $this->advisoryCountyId = (County::find($this->countyId)->name === 'Unknown')
            ? 'An "unknown" county may preclude your engagement in and knowledge of some events.'
            : '';
    }

    public function updatedName(): void
    {
        $this->reset('resultsName');
        $min = 4; //minimum number of characters needed to initiate search

        $str = '';

        if (strlen($this->name) > $min) {

            $schools = School::query()
                ->where('name', 'LIKE', '%'.$this->name.'%')
                ->orderBy('name')
                ->orderBy('city')
                ->get();

            if ($schools->count()) {

                $str = '';

                foreach ($schools as $school) {

                    $str .= '<button type="button" wire:click="addSchool('.$school->id.')" class="text-sm text-blue-500 ml-2">'
                        .SchoolResultsValueObject::getVo($school) //"schoolName (city in county, state)"
                        .'</button>';
                }
            } else {

                $str = '<div>No schools found for "'.$this->name.'".</div>';
            }
        }

        $this->resultsName = (strlen($this->name) && strlen($this->name) <= $min)
            ? 'Please enter at least '.($min + 1).' characters'
            : $str;
    }

    public function updatedPostalCode(): void
    {
        $this->reset('resultsPostalCode');

        $str = '<div>Please enter at least three numbers to begin search...</div>';

        if (strlen($this->postalCode) > 2) {

            $schools = School::query()
                ->where('postal_code', 'LIKE', $this->postalCode.'%')
                ->orderBy('name')
                ->orderBy('city')
                ->get();

            if ($schools->count()) {

                $str = '';

                foreach ($schools as $school) {
                    $str .= '<button type="button" wire:click="addSchool('.$school->id.')"
                    class="text-sm text-blue-500 w-1/3">'
                        .SchoolResultsValueObject::getVo($school) //"schoolName (city in county, state postalCode)"
                        .'</button>';
                }
            } else {

                $str = '<div>No schools found for "'.$this->postalCode.'".</div>';
            }
        }

        $this->resultsPostalCode = $str;
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function addSchool(): School
    {
        return School::updateOrCreate(
            [
                'name' => $this->name,
                'postal_code' => $this->postalCode,
            ],
            [
                'city' => $this->city,
                'county_id' => $this->countyId,
            ]
        );
    }

    private function checkForEmailChange(School $school): bool
    {
        $currentEmail = SchoolTeacher::query()
            ->where('school_id', $school->id)
            ->where('teacher_id', auth()->id())
            ->first()
            ->email ?? '';

        return ($currentEmail !== $this->email);
    }

    private function addSchoolTeacher(School $school): SchoolTeacher
    {
        //restore school if trashed, then updateOrCreate
        $this->restoreTrashedRow($school);

        return SchoolTeacher::updateOrCreate(
            [
                'school_id' => $school->id,
                'teacher_id' => auth()->id(),
            ],
            [
                'email' => $this->email,
                'email_verified_at' => null,
                'active' => 1,
            ]
        );
    }

    private function emailIsACommercialEmail(): bool
    {

        $commercialDomains = [
            'gmail.com', 'hotmail.com', 'aol.com',
            'icloud.com', 'me.com', 'msn.com', 'outlook.com', 'yahoo.com'
        ];

        $emailDomain = substr(strrchr($this->email, "@"), 1);

        return in_array($emailDomain, $commercialDomains);

//        $email = trim(strtolower($this->email));
//        $parts = explode('@', $email);
//
//        if (count($parts) !== 2) {
//            return true; //invalid email
//        }
//
//        $domain = $parts[1];
//        $domains = ['aol.', 'gmail.', 'hotmail.', 'icloud.', 'me.', 'msn.', 'outlook.', 'yahoo.',];
//
//        foreach ($domains as $commercialDomain) {
//
//            if (Str::contains($this->email, $commercialDomain)) {
//
//                return true;
//            }
//        }
//
//        return false;
    }

    private function restoreTrashedRow(School $school): void
    {
        $trashedRowFound = SchoolTeacher::withTrashed()
            ->where('school_id', $school->id)
            ->where('teacher_id', auth()->id())
            ->first();

        $trashedRowFound?->restore();

    }

    private function sendVerificationEmailIfNeeded(): void
    {
        if (!$this->emailIsACommercialEmail()) {

            event(new WorkEmailChangedEvent($this->schoolTeacher, $this->email));
        }
    }

    private function setVars(): void
    {
        $this->city = $this->school->city;
        $this->countyId = $this->school->county_id;
        $this->email = $this->schoolTeacher->email;
        $this->name = $this->school->name;
        $this->postalCode = $this->school->postal_code;
        $this->sysId = $this->school->id;

        $this->setGradesITeach();
        $this->setGradesTaught();
    }

    private function setGradesITeach(): void
    {
        $this->gradesITeach = GradesITeach::query()
            ->where('school_id', $this->school->id)
            ->where('teacher_id', auth()->id())
            ->pluck('grade')
            ->toArray();
    }

    private function setGradesTaught(): void
    {
        $this->gradesTaught = SchoolGrade::where('school_id', $this->school->id)
            ->pluck('grade')
            ->toArray();
    }

    private function updateSchoolTeacherEmail(): void
    {
        $this->schoolTeacher->update(
            [
                'email' => $this->email,
                'email_verified_at' => null,
            ]
        );

    }

}
