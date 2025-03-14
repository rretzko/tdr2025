<?php

namespace App\Livewire\Forms;

use App\Events\WorkEmailChangedEvent;
use App\Models\County;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Schools\SchoolGrade;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use App\Models\Schools\Teachers\Supervisor;
use App\Models\Schools\Teachers\TeacherSubject;
use App\Models\UserConfig;
use App\Services\FormatPhoneService;
use App\ValueObjects\SchoolResultsValueObject;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SchoolForm extends Form
{
    #[Validate('required', 'string', 'min:1', 'max:6')]
    public string $abbr = '';
    public string $advisoryCountyId = '';
    #[Validate('required', message: "A city is required.")]
    public string $city = '';
    public bool $commercialWorkAddressDomain = false;
    #[Validate('required')]
    #[Validate('integer')]
    #[Validate('min:1', message: "You must select a county.")]
    public int $countyId = 0;
    #[Validate('required')]
    public string $email = '';
    public array $gradesITeach = [];
    public array $gradesTaught = [];
    #[Validate('required', message: 'A school name is required.')]
    public string $name = 'S';
    #[Validate('required', message: 'A zip code is required.')]
    public string $postalCode = '';

    public string $resultsCity = '';
    public string $resultsPostalCode = '';
    public string $resultsName = '';
    public School $school;
    public SchoolTeacher $schoolTeacher;
    #[Validate('min:1', message: 'At least one subject must be selected.')]
    public array $subjects = [];
    public string $supervisorEmail = '';
    public string $supervisorName = '';
    public string $supervisorPhone = '';
    public string $sysId = 'new';
    public TeacherSubject $teacherSubject;

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
        $this->validate([
            'gradesITeach' => ['required', 'array', 'min:1'],
            'gradesTaught' => ['required', 'array', 'min:1'],
        ]);

        if ($this->sysId === 'new') {

            $this->subjects[] = 'chorus';

            $this->school = $this->add();

            //link school to teacher
            $this->schoolTeacher = $this->addSchoolTeacher($this->school);

            //link subject to teacher
            $this->teacherSubject = TeacherSubject::create(
                [
                    'teacher_id' => Teacher::where('user_id', auth()->id())->first()->id,
                    'school_id' => $this->school->id,
                    'subject' => 'chorus',
                ]
            );

            //verify school email address
            $this->sendVerificationEmailIfNeeded();

        } else {

            $this->school->update(
                [
                    'abbr' => Str::upper($this->abbr),
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

        //delete old and insert new $this->subjects
        $this->teacherSubject->updateTeacherSubject($this->subjects);

        //check for email change
        $emailChanged = $this->checkForEmailChange($this->school);

        if ($emailChanged) {
            $this->updateSchoolTeacherEmail();
        }

        //send verification email if $this->email is NOT gmail, hotmail, aol, etc.
        if ($emailChanged || is_null($this->schoolTeacher->email_verified_at)) {
            $this->sendVerificationEmailIfNeeded();
        }

        $this->updateSupervisor();
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

    public function updatedEmail(): void
    {
        Log::info(__METHOD__);
        $this->commercialWorkAddressDomain = $this->emailIsACommercialEmail();
        Log::info('commercialWorkAddressDomain: ' . $this->commercialWorkAddressDomain);
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

    private function add(): School
    {
        return School::updateOrCreate(
            [
                'name' => $this->name,
                'postal_code' => $this->postalCode,
            ],
            [
                'abbr' => Str::upper($this->abbr),
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
    }

    private function getSupervisorPhone(): string
    {
        //early exit
        if (!strlen($this->supervisorPhone)) {
            return '';
        }

        $service = new FormatPhoneService();
        return $service->getPhoneNumber($this->supervisorPhone);
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
        } else {
            $this->commercialWorkAddressDomain;
        }
    }

    private function setVars(): void
    {
        $this->abbr = $this->school->abbr;
        $this->city = $this->school->city;
        $this->countyId = $this->school->county_id;
        $this->email = $this->schoolTeacher->email;
        $this->name = $this->school->name;
        $this->postalCode = $this->school->postal_code;
        $this->sysId = $this->school->id;

        $this->setGradesITeach();
        $this->setGradesTaught();
        $this->setSubjects();

        $this->setSupervisorVars();
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

    private function setSupervisorVars(): void
    {
        $schoolId = UserConfig::getValue('schoolId');
        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $supervisor = Supervisor::query()
            ->where('school_id', $schoolId)
            ->where('teacher_id', $teacherId)
            ->first();

        if ($supervisor) {
            $this->supervisorName = $supervisor->supervisor_name ?? '';
            $this->supervisorEmail = $supervisor->supervisor_email ?? '';
            $this->supervisorPhone = $supervisor->supervisor->phone ?? '';
        }
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

    private function updateSupervisor(): void
    {
        $schoolId = UserConfig::getValue('schoolId');
        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $supervisorPhone = $this->getSupervisorPhone();

        Supervisor::updateOrCreate(
            [
                'school_id' => $schoolId,
                'teacher_id' => $teacherId,
            ],
            [
                'supervisor_email' => $this->supervisorEmail,
                'supervisor_name' => $this->supervisorName,
                'supervisor_phone' => $supervisorPhone,
            ]
        );
    }

    private function setSubjects(): void
    {
        $this->subjects = TeacherSubject::query()
            ->where('school_id', $this->school->id)
            ->where('teacher_id', auth()->id())
            ->pluck('subject')
            ->toArray();

        if (!$this->subjects) {

            TeacherSubject::create(
                [
                    'school_id' => $this->school->id,
                    'teacher_id' => auth()->id(),
                    'subject' => 'chorus', //default
                ]
            );

            //recursive call ensures at least on row in $this->subjects array
            $this->setSubjects();
        }

        //set $this->teacherSubject to the first row found
        $this->teacherSubject = TeacherSubject::query()
            ->where('school_id', $this->school->id)
            ->where('teacher_id', auth()->id())
            ->first();
    }

}
