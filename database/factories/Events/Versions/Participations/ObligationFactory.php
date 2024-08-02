<?php

namespace Database\Factories\Events\Versions\Participations;

use App\Models\Events\Versions\Participations\Obligation;
use App\Models\Events\Versions\Version;
use App\Models\Schools\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ObligationFactory extends Factory
{
    protected $model = Obligation::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'accepted' => $this->faker->boolean(),

            'version_id' => Version::factory(),
            'teacher_id' => Teacher::factory(),
        ];
    }
}
