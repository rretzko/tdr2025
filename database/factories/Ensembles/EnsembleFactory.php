<?php

namespace Database\Factories\Ensembles;

use App\Models\Schools\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ensembles\Ensemble>
 */
class EnsembleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'school_id' => School::factory(),
            'name' => implode(' ', ($this->faker->words(3))),
            'short_name' => $this->faker->word(),
            'abbr' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'active' => $this->faker->boolean,
        ];
    }
}
