<?php

namespace Database\Factories\Events\Versions\Participations;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Recording;
use App\Models\Events\Versions\Version;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RecordingFactory extends Factory
{
    protected $model = Recording::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'file_type' => $this->faker->word(),
            'uploaded_by' => $this->faker->randomNumber(),
            'approved' => Carbon::now(),
            'url' => $this->faker->url(),

            'version_id' => Version::factory(),
            'candidate_id' => Candidate::factory(),
        ];
    }
}
