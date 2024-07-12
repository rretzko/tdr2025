<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Event;
use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VersionFactory extends Factory
{
    protected $model = Version::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
            'short_name' => $this->faker->name(),
            'senior_class_of' => $this->faker->randomNumber(),
            'status' => $this->faker->word(),

            'event_id' => Event::factory(),
        ];
    }
}
