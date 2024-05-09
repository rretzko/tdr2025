<?php

namespace Database\Factories\Schools;

use App\Models\Schools\School;
use App\Models\Schools\SchoolTeacher;
use App\Models\Schools\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SchoolTeacherFactory extends Factory
{
    protected $model = SchoolTeacher::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'active' => $this->faker->boolean(),

            'school_id' => School::factory(),
            'teacher_id' => Teacher::factory(),
        ];
    }
}
