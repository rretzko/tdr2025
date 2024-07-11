<?php

namespace Database\Factories\Students;

use App\Models\Students\Student;
use App\Models\Students\VoicePart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $shirtSizes = ['sm', 'med', 'lg'];
        $voiceParts = [1, 2, 3, 4, 5, 6, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73];

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'class_of' => $this->faker->numberBetween(2020, 2028),
            'height' => $this->faker->numberBetween(30, 80),
            'shirt_size' => $shirtSizes[$this->faker->numberBetween(0, 2)],
            'voice_part_id' => $voiceParts[array_rand($voiceParts)],
            'birthday' => Carbon::now(),
            'user_id' => User::factory(),
        ];
    }
}
