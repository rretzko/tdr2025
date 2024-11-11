<?php

namespace App\Models\AuditionResults;

use Illuminate\Database\Eloquent\Model;

class StackedScoresDescendingSingleEnsembleAlgorithm extends Model implements AlgorithmInterface
{

    public function registerCutoff($eventEnsembles, $versionconfigAdjudication, int $score, int $voicePartId)
    {
        // TODO: Implement registerCutoff() method.
    }
}
