<?php

namespace App\Livewire\Forms;

use App\Events\WorkEmailChangedEvent;
use App\Models\Schools\School;
use App\Models\Schools\SchoolTeacher;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SchoolForm extends Form
{
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

    public function rules()
    {
        return [
            'gradesITeach' => ['required', 'array', 'min:1'],
            'gradesTaught' => ['required', 'array', 'min:1'],
        ];
    }

    public function update(): void
    {
        $this->validate();

        $school = $this->addSchool();

        //delete old and insert new $this->gradesTaught
        $school->updateGrades($this->gradesTaught);

        //check for email change
        $emailChanged = $this->checkForEmailChange($school);

        //link school to teacher
        $schoolTeacher = $this->addSchoolTeacher($school, $emailChanged);

        //delete old and insert new $this->gradesITeach
        $schoolTeacher->updateGradesITeach($this->gradesITeach, $school->id);

        //send verification email if $this->email is NOT gmail, hotmail, aol, etc.
        if ($emailChanged || is_null($schoolTeacher->email_verified_at)) {

            if (!$this->emailIsACommercialEmail()) {

                event(new WorkEmailChangedEvent($schoolTeacher, $this->email));
            }
        }
    }

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
            ->email;

        return ($currentEmail !== $this->email);
    }

    private function addSchoolTeacher(School $school, bool $emailChanged): SchoolTeacher
    {
        $schoolTeacher = SchoolTeacher::updateOrCreate(
            [
                'school_id' => $school->id,
                'teacher_id' => auth()->id(),
            ],
            [
                'email' => $this->email,
                'active' => 1,
            ]
        );

        //if user has changed their email address, the new address needs to be verified
        if ($emailChanged) {

            $schoolTeacher->update(['email_verified_at' => null]);

        }

        return $schoolTeacher;
    }

    private function emailIsACommercialEmail(): bool
    {
        $email = trim(strtolower($this->email));
        $parts = explode('@', $email);

        if (count($parts) !== 2) {
            return true; //invalid email
        }

        $domain = $parts[1];
        $domains = ['aol.', 'gmail.', 'hotmail.', 'icloud.', 'me.', 'msn.', 'outlook.', 'yahoo.',];

        foreach ($domains as $commercialDomain) {

            if (Str::contains($this->email, $commercialDomain)) {

                return true;
            }
        }

        return false;
    }

}
