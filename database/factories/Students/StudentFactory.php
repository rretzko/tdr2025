<?php

namespace Database\Factories\Students;

use App\Models\Students\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'class_of' => $this->faker->randomNumber(),
            'height' => $this->faker->randomNumber(),
            'shirt_size' => $this->faker->word(),

            'user_id' => User::factory(),
        ];
    }
}
