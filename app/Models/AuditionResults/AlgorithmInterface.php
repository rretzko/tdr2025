<?php

namespace App\Models\AuditionResults;


use App\Models\Events\Versions\VersionConfigAdjudication;
use Illuminate\Database\Eloquent\Collection;

interface AlgorithmInterface
{
    public function acceptParticipants(
        Collection $eventEnsembles,
        VersionConfigAdjudication $versionconfigAdjudication,
        int $score,
        int $voicePartId
    );

    public function registerCutoff(
        Collection $eventEnsembles,
        VersionConfigAdjudication $versionconfigAdjudication,
        int $score,
        int $voicePartId
    );
}
