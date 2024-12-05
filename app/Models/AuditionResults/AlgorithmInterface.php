<?php

namespace App\Models\AuditionResults;


use App\Models\Events\Versions\VersionConfigAdjudication;
use Illuminate\Database\Eloquent\Collection;

interface AlgorithmInterface
{
    /**
     * record student's participation status into audition_results table
     * @param  Collection  $eventEnsembles
     * @param  VersionConfigAdjudication  $versionConfigAdjudication
     * @param  int  $score
     * @param  int  $voicePartId
     * @return void
     */
    public function acceptParticipants(
        Collection $eventEnsembles,
        VersionConfigAdjudication $versionConfigAdjudication,
        int $score,
        int $voicePartId
    ): void;

    /**
     * Add selected score value to version_cutoffs table for historical reference
     * @param  Collection  $eventEnsembles
     * @param  VersionConfigAdjudication  $versionConfigAdjudication
     * @param  int  $score
     * @param  int  $voicePartId
     * @return void
     */
    public function registerCutoff(
        Collection $eventEnsembles,
        VersionConfigAdjudication $versionConfigAdjudication,
        int $score,
        int $voicePartId
    ): void;
}
