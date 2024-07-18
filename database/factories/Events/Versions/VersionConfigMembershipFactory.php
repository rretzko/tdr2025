<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigMembership;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VersionConfigMembershipFactory extends Factory
{
    protected $model = VersionConfigMembership::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'membership_card' => $this->faker->boolean(),
            'valid_thru' => Carbon::now(),

            'version_id' => Version::factory(),
        ];
    }
}
