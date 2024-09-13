<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Judge;
use App\Models\Events\Versions\Scoring\Room;
use App\Models\Events\Versions\Version;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class JudgeFactory extends Factory
{
    protected $model = Judge::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'status_type' => $this->faker->word(),
            'judge_type' => $this->faker->word(),

            'version_id' => Version::factory(),
            'room_id' => Room::factory(),
            'user_id' => User::factory(),
        ];
    }
}
