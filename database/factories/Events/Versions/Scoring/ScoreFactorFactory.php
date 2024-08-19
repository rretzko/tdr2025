<?php

namespace Database\Factories\Events\Versions\Scoring;

use App\Models\Events\Event;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ScoreFactorFactory extends Factory
{
    protected $model = ScoreFactor::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'factor' => $this->faker->word(),
            'abbr' => $this->faker->word(),
            'best' => $this->faker->randomNumber(),
            'worst' => $this->faker->randomNumber(),
            'interval_by' => $this->faker->randomNumber(),
            'multiplier' => $this->faker->randomNumber(),
            'tolerance' => $this->faker->randomNumber(),
            'order_by' => $this->faker->randomNumber(),

            'event_id' => Event::factory(),
            'version_id' => Version::factory(),
            'score_category_id' => ScoreCategory::factory(),
        ];
    }
}
