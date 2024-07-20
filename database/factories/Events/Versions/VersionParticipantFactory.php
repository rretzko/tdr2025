<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VersionParticipantFactory extends Factory
{
    protected $model = VersionParticipant::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'status' => $this->faker->word(),

            'version_id' => Version::factory(),
            'user_id' => User::factory(),
        ];
    }
}
