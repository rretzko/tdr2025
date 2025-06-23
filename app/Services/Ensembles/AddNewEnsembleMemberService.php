<?php

namespace App\Services\Ensembles;


use App\Models\Ensembles\Members\Member;
use App\Models\Schools\Teacher;
use App\Models\Students\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AddNewEnsembleMemberService
{
    private Student $student;
    public bool $added = false;
    private int $classOf = 0;
    private int $teacherId = 0;

    public function __construct(
        private readonly int $schoolId,
        private readonly int $ensembleId,
        private readonly int $schoolYear,
        private readonly string $firstName,
        private readonly string $middleName,
        private readonly string $lastName,
        private readonly string $email,
        private readonly int $gradeClassOf,
        private readonly int $voicePartId,
        private readonly string $office = 'member',
        private readonly string $status = 'active'
    ) {
        Log::info('lastName: '.$this->lastName);
        Log::info('firstName: '.$this->firstName);
        Log::info('middleName: '.$this->middleName);
        $this->teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $this->init();
    }

    private function init(): void
    {
        //calc classOf from gradeClassOf
        $this->calcClassOfFromGrade();

        //discover student from schoolId, email, lastName, firstName, middleName, classOf,
        if ($this->studentFound()) {
            // if gradeClassOf > $this->classOf, update student to greater value
            // explanation: Teachers may be adding historical programs where the
            // individual class_of values may be unknown and need to be approximated.
            // For that reason, always use the greater value of the gradeClassOf and $this->classOf
            if ($this->classOf > $this->student->class_of) {
                $this->student->update(['class_of' => $this->classOf]);
            }
            $student = $this->student;

        } else {
            //   if student is not found:
            //      create new user
            $user = User::create([
                'name' => $this->firstName.' '.$this->middleName.' '.$this->lastName,
                'email' => $this->email,
                'first_name' => $this->firstName,
                'middle_name' => $this->middleName,
                'last_name' => $this->lastName,
                'pronoun_id' => 1, //default
                'password' => Hash::make('studentfolder.info'),
            ]);
            //      create new student if student is not found
            $student = Student::create([
                'user_id' => $user->id,
                'voice_part_id' => $this->voicePartId,
                'class_of' => $this->classOf,
                'height' => 36,
                'birthday' => date("Y-m-d"),
                'school_id' => 'med'
            ]);
        }

        // ensure that student is linked to teacher
        if (!$student->teachers()->where('teacher_id', $this->teacherId)->exists()) {
            $student->teachers()->attach($this->teacherId);
        }

        // ensure that student is linked to school
        if (!$student->schools()->where('school_id', $this->schoolId)->exists()) {
            $student->schools()->attach($this->schoolId);
        }

        //update or create new EnsembleMember
        $member = Member::updateOrCreate([
            'school_id' => $this->schoolId,
            'ensemble_id' => $this->ensembleId,
            'school_year' => $this->schoolYear,
            'student_id' => $student->id,
            'voice_part_id' => $this->voicePartId,
        ],
            [
            'office' => $this->office,
            'status' => $this->status,
        ]);

        if ($member->id) {
            $this->added = true;
        }
    }

    private function calcClassOfFromGrade(): void
    {
        if ($this->isFourDigitYear()) {
            // Use the maximum of gradeClassOf and schoolYear
            $this->classOf = max($this->gradeClassOf, $this->schoolYear);
        } elseif ($this->isGrade()) {
            // Calculate classOf based on grade and senior year logic
            $maxSchoolYears = 12;
            $yearsUntilSeniorYear = $maxSchoolYears - $this->gradeClassOf;
            $this->classOf = $this->schoolYear + $yearsUntilSeniorYear;
        } else {
            //Fallback for an unexpected value
            $this->classOf = $this->schoolYear;
        }
    }

    /**
     * test that $this->gradeClass of is a four-digit integer greater than 1900
     * @return bool
     */
    private function isFourDigitYear(): bool
    {
        $yearsInSchool = 12;
        $maxClassOf = (int) date('Y') + $yearsInSchool;
        return ((strlen($this->gradeClassOf) === 4) && ($this->gradeClassOf > 1900) && ($this->gradeClassOf < $maxClassOf));
    }

    /**
     * test that $this->gradeClass of between 1 and 12 inclusive
     * @return bool
     */
    private function isGrade(): bool
    {
        return (($this->gradeClassOf >= 1) && ($this->gradeClassOf <= 12));
    }

    private function studentEmailFound(): bool
    {
        return User::query()
            ->join('students', 'students.user_id', '=', 'users.id')
            ->where('email', $this->email)
            ->exists();
    }

    private function studentFound(): bool
    {
        //search by Email
        if ($this->studentEmailFound()) {
            $this->student = Student::query()
                ->join('users', 'users.id', '=', 'students.user_id')
                ->where('users.email', $this->email)
                ->first();

            return true;
        }

        //search by SchoolId + names
        $userName = trim($this->firstName.' '.$this->middleName.' '.$this->lastName);
        if ($this->studentNameFound($userName)) {
            $this->student = Student::query()
                ->join('school_student', 'students.id', '=', 'school_student.student_id')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->where('school_student.school_id', $this->schoolId)
                ->where('users.name', $userName)
                ->first();
            return true;
        }

        return false;
    }

    private function studentNameFound(string $userName): bool
    {
        return Student::query()
            ->join('school_student', 'students.id', '=', 'school_student.student_id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('school_student.school_id', $this->schoolId)
            ->where('users.name', $userName)
            ->exists();
    }

}
