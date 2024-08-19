<?php

namespace Database\Factories\Events\Versions\Scoring;

use App\Models\Events\Versions\Scoring\Room;
use App\Models\Events\Versions\Scoring\RoomVoicepart;
use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RoomVoicepartFactory extends Factory
{
    protected $model = RoomVoicepart::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'room_id' => Room::factory(),
            'voice_part_id' => VoicePart::factory(),
        ];
    }
}
