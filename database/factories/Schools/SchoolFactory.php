<?php

namespace Database\Factories\Schools;

use App\Models\County;
use App\Models\Schools\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->name(),
            'postal_code' => $this->faker->postcode(),
            'city' => $this->faker->city(),

            'county_id' => County::factory(),
        ];
    }
}
