<?php

namespace Database\Factories;

use App\Models\Geostate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GeostateFactory extends Factory
{
    protected $model = Geostate::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'country_abbr' => $this->faker->word(),
            'name' => $this->faker->name(),
            'abbr' => $this->faker->word(),
        ];
    }
}
