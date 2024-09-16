<?php

namespace Database\Factories\Events\Versions\Scoring;

use App\Models\Events\Versions\Scoring\Room;
use App\Models\Events\Versions\Scoring\RoomScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RoomScoreCategoriesFactory extends Factory
{
    protected $model = RoomScoreCategory::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'room_id' => Room::factory(),
            'score_category_id' => ScoreCategory::factory(),
        ];
    }
}
