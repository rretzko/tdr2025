<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\TeacherPayment;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TeacherPaymentFactory extends Factory
{
    protected $model = TeacherPayment::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'fee_type' => $this->faker->word(),
            'transaction_id' => $this->faker->word(),
            'amount' => $this->faker->randomNumber(),
            'comments' => $this->faker->word(),

            'version_id' => Version::factory(),
            'school_id' => School::factory(),
            'user_id' => User::factory(),
        ];
    }
}
