<?php

namespace Database\Factories\Events\Versions\Participations;

use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AuditionResultFactory extends Factory
{
    protected $model = AuditionResult::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'voice_part_order_by' => $this->faker->randomNumber(),
            'score_count' => $this->faker->randomNumber(),
            'total' => $this->faker->randomNumber(),
            'accepted' => $this->faker->boolean(),
            'acceptance_abbr' => $this->faker->word(),

            'candidate_id' => Candidate::factory(),
            'version_id' => Version::factory(),
            'voice_part_id' => VoicePart::factory(),
            'school_id' => School::factory(),
        ];
    }
}
