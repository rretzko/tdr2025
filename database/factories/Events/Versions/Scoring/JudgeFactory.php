<?php

namespace Database\Factories\Events\Versions\Scoring;

use App\Models\Events\Versions\Scoring\Judge;
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
            'judge_role' => $this->faker->word(),

            'version_id' => Version::factory(),
            'room_id' => Room::factory(),
            'user_id' => User::factory(),
        ];
    }
}
