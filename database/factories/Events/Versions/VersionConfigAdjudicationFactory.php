<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VersionConfigAdjudicationFactory extends Factory
{
    protected $model = VersionConfigAdjudication::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'upload_count' => $this->faker->randomNumber(),
            'upload_types' => $this->faker->word(),
            'judge_per_room_count' => $this->faker->randomNumber(),
            'room_monitor' => $this->faker->boolean(),
            'averaged_scores' => $this->faker->boolean(),
            'scores_ascending' => $this->faker->boolean(),

            'version_id' => Version::factory(),
        ];
    }
}
