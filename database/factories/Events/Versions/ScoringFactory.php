<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Scoring;
use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ScoringFactory extends Factory
{
    protected $model = Scoring::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'version_id' => Version::factory(),
        ];
    }
}
