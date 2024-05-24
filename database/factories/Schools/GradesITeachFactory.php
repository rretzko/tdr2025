<?php

namespace Database\Factories\Schools;

use App\Models\Schools\GradesITeach;
use App\Models\Schools\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GradesITeachFactory extends Factory
{
    protected $model = GradesITeach::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'grade' => $this->faker->randomNumber(),

            'school_id' => School::factory(),
            'teacher_id' => TeacherFactory::factory(),
        ];
    }
}
