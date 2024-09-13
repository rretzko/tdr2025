<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Rooms;
use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RoomsFactory extends Factory
{
    protected $model = Rooms::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'room_name' => $this->faker->name(),
            'tolerance' => $this->faker->randomNumber(),
            'order_by' => $this->faker->randomNumber(),

            'version_id' => Version::factory(),
        ];
    }
}
