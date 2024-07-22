<?php

namespace Database\Factories\Events\Versions;

use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionPitchFile;
use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VersionConfigPitchFileFactory extends Factory
{
    protected $model = VersionPitchFile::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'file_type' => $this->faker->word(),
            'url' => $this->faker->url(),
            'description' => $this->faker->text(),
            'order_by' => $this->faker->randomNumber(),

            'version_id' => Version::factory(),
            'voice_part_id' => VoicePart::factory(),
        ];
    }
}
