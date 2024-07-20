<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\Events\Versions\VersionRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VersionRoleFactory extends Factory
{
    protected $model = VersionRole::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'role' => $this->faker->word(),

            'version_id' => Version::factory(),
            'version_participant_id' => VersionParticipant::factory(),
        ];
    }
}
