<?php

namespace Database\Factories\Schools;

use App\Models\Schools\School;
use App\Models\Schools\SchoolGrade;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SchoolGradeFactory extends Factory
{
    protected $model = SchoolGrade::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'grade' => $this->faker->numberBetween(1, 12),

            'school_id' => School::factory(),
        ];
    }
}
