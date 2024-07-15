<?php

namespace Database\Factories\Events;

use App\Models\Events\Event;
use App\Models\Events\EventEnsemble;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventEnsembleFactory extends Factory
{
    protected $model = EventEnsemble::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'ensemble_name' => $this->faker->name(),
            'ensemble_short_name' => $this->faker->name(),
            'grades' => $this->faker->word(),
            'voice_part_ids' => $this->faker->word(),

            'event_id' => Event::factory(),
        ];
    }
}
