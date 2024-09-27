<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionTimeslot;
use App\Models\Schools\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VersionTimeslotFactory extends Factory
{
    protected $model = VersionTimeslot::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'timeslot' => Carbon::now(),

            'version_id' => Version::factory(),
            'school_id' => School::factory(),
        ];
    }
}
