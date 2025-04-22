<?php

namespace App\Services\Sandbox;

readonly class GenerateScoresService
{
    public function __construct(private int $versionId)
    {
        $this->init();
    }

    private function init(): void
    {
        //candidate_id
        //student_id
        //school_id
        //score_category_id
        //score_category_order_by
        //score_factor_id
        //score_factor_order_by
        //judge_id
        //judge_order_by
        //voice_part_id
        //voice_part_order_by
        //score

    }
}
