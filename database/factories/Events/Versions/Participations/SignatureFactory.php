<?php

namespace Database\Factories\Events\Versions\Participations;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Signature;
use App\Models\Events\Versions\Version;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SignatureFactory extends Factory
{
    protected $model = Signature::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'role' => $this->faker->word(),
            'signed' => $this->faker->boolean(),

            'version_id' => Version::factory(),
            'candidate_id' => Candidate::factory(),
            'user_id' => User::factory(),
        ];
    }
}
