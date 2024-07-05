<?php

namespace Database\Seeders;

use App\Models\Ensembles\Ensemble;
use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\Schools\SchoolGrade;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use App\Models\Schools\Teachers\TeacherSubject;
use App\Models\Students\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Teachers100Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {

            $user = User::factory()->create();

            $teacher = Teacher::create(
                [
                    'user_id' => $user->id,
                ]
            );

            $school = School::factory()->create();
            $schoolId = $school->id;

            SchoolTeacher::create(
                [
                    'school_id' => $schoolId,
                    'teacher_id' => $teacher->id,
                    'email' => $user->email,
                    'email_verified_at' => Carbon::now(),
                    'active' => 1,
                ]
            );

            TeacherSubject::create(
                [
                    'teacher_id' => $teacher->id,
                    'school_id' => $school->id,
                    'subject' => 'chorus',
                ]
            );


            for ($j = 9; $j < 13; $j++) {

                SchoolGrade::create(
                    [
                        'school_id' => $schoolId,
                        'grade' => $j,
                    ]
                );
            }
//
            for ($k = 9; $k < 13; $k++) {

                GradesITeach::create(
                    [
                        'school_id' => $school->id,
                        'teacher_id' => $teacher->id,
                        'grade' => $k,
                    ]
                );
            }

            //three school ensembles
            $ensemble = Ensemble::factory(3)->create(['school_id' => $schoolId]);
        }// end of teacher creation

        //student creation
        foreach (Teacher::all() as $teacher) {

            foreach ($teacher->schools as $school) {

                //create 30 students per teacher
                for ($i = 0; $i < 30; $i++) {

                    $user = User::factory()->create();
                    $userId = $user->id;

                    $student = Student::factory()->create(['id' => $userId, 'user_id' => $userId]);

                    $teacher->students()->attach($student);

                    $school->students()->attach($student, ['active' => 1]);

                }
            }
        }
    }
}
