<?php

namespace Database\Factories;

use App\Models\County;
use App\Models\Geostate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CountyFactory extends Factory
{
    protected $model = County::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),

            'geostate_id' => Geostate::factory(),
        ];
    }
}
