<?php

namespace Database\Factories;

use App\Models\Epayment;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EpaymentFactory extends Factory
{
    protected $model = Epayment::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'fee_type' => $this->faker->word(),
            'candidate_id' => $this->faker->randomNumber(),
            'transaction_id' => $this->faker->word(),
            'amount' => $this->faker->randomNumber(),
            'comments' => $this->faker->word(),

            'version_id' => Version::factory(),
            'school_id' => School::factory(),
            'user_id' => User::factory(),
        ];
    }
}
