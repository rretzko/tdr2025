<?php

namespace Database\Factories\Schools\Teachers;

use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\Schools\Teachers\TeacherSubject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TeacherSubjectFactory extends Factory
{
    protected $model = TeacherSubject::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'subject' => $this->faker->word(),

            'teacher_id' => Teacher::factory(),
            'school_id' => School::factory(),
        ];
    }
}
