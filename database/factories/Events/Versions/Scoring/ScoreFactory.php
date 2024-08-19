<?php

namespace Database\Factories\Events\Versions\Scoring;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Scoring\Score;
use App\Models\Events\Versions\Scoring\ScoreCategory;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\Students\Student;
use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ScoreFactory extends Factory
{
    protected $model = Score::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'score_category_order_by' => $this->faker->randomNumber(),
            'score_factor_order_by' => $this->faker->randomNumber(),
            'judge_id' => $this->faker->randomNumber(),
            'judge_order_by' => $this->faker->randomNumber(),
            'voice_part_order_by' => $this->faker->randomNumber(),
            'score' => $this->faker->randomNumber(),

            'version_id' => Version::factory(),
            'candidate_id' => Candidate::factory(),
            'student_id' => Student::factory(),
            'school_id' => School::factory(),
            'score_category_id' => ScoreCategory::factory(),
            'score_factor_id' => ScoreFactor::factory(),
            'voice_part_id' => VoicePart::factory(),
        ];
    }
}
