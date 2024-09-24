<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigTimeslot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VersionConfigTimeslotFactory extends Factory
{
    protected $model = VersionConfigTimeslot::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now(),
            'duration' => $this->faker->randomNumber(),

            'version_id' => Version::factory(),
        ];
    }
}
