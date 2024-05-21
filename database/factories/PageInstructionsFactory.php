<?php

namespace Database\Factories;

use App\Models\PageInstruction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PageInstructionsFactory extends Factory
{
    protected $model = PageInstruction::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'header' => $this->faker->word(),
            'instructions' => $this->faker->paragraphs(2),
        ];
    }
}
