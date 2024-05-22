<?php

namespace Database\Factories;

use App\Models\PageView;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PageViewFactory extends Factory
{
    protected $model = PageView::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'header' => $this->faker->word(),
            'view_count' => $this->faker->randomNumber(),

            'user_id' => User::factory(),
        ];
    }
}
