<?php

namespace Database\Factories\Events\Versions\Participations;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\StudentPayment;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StudentPaymentFactory extends Factory
{
    protected $model = StudentPayment::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'amount' => $this->faker->randomNumber(),
            'transaction_id' => $this->faker->word(),
            'comments' => $this->faker->word(),
            'payment_type' => $this->faker->word(),

            'candidate_id' => Candidate::factory(),
            'student_id' => Student::factory(),
            'version_id' => Version::factory(),
            'school_id' => School::factory(),
        ];
    }
}
