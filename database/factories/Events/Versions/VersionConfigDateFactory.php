<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigDate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VersionConfigDateFactory extends Factory
{
    protected $model = VersionConfigDate::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'date_type' => $this->faker->word(),
            'version_date' => Carbon::now(),

            'version_id' => Version::factory(),
        ];
    }
}
