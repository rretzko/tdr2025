<?php

namespace Database\Factories;

use App\Models\epaymentCredentials;
use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ePaymentCredentialsFactory extends Factory
{
    protected $model = epaymentCredentials::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'version_id' => $this->faker->randomNumber(),
            'ePaymentId' => $this->faker->word(),

            'event_id' => Version::factory(),
        ];
    }
}
