<?php

namespace Database\Factories\Events;

use App\Models\Events\Event;
use App\Models\Events\EventManagement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventManagementFactory extends Factory
{
    protected $model = EventManagement::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'event_id' => Event::factory(),
            'user_id' => User::factory(),
        ];
    }
}
