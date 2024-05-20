<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ViewCard>
 */
class ViewCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'header' => $this->faker->word,
            'heroicon' => 'heroicons.building',
            'color' => 'black',
            'href' => $this->faker->url(),
            'label' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'order_by' => $this->faker->numberBetween(1, 99),
        ];
    }
}
