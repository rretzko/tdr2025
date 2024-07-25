<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionScoring;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VersionScoringFactory extends Factory
{
    protected $model = VersionScoring::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'version_id' => Version::factory(),
        ];
    }
}
