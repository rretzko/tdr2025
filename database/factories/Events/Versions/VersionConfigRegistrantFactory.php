<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigRegistrant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VersionConfigRegistrantFactory extends Factory
{
    protected $model = VersionConfigRegistrant::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'eapplication' => $this->faker->boolean(),
            'audition_count' => $this->faker->randomNumber(),

            'version_id' => Version::factory(),
        ];
    }
}
