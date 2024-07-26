<?php

namespace Database\Factories\Events\Versions\Participations;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\Schools\Teacher;
use App\Models\Students\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'program_name' => $this->faker->name(),
            'candidate_type' => $this->faker->word(),

            'user_id' => User::factory(),
            'version_id' => Version::factory(),
            'student_id' => Student::factory(),
            'teacher_id' => Teacher::factory(),
        ];
    }
}
