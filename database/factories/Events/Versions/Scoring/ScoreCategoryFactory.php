<?php

namespace Database\Factories\Events\Versions\Scoring;

use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ScoreCategoryFactory extends Factory
{
    protected $model = ScoreCategory::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'descr' => $this->faker->word(),
            'order_by' => $this->faker->randomNumber(),

            'version_id' => Version::factory(),
        ];
    }
}
