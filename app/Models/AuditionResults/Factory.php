<?php

namespace App\Models\AuditionResults;

use App\Models\Events\Versions\VersionConfigAdjudication;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

/**
 * Determine which algorithm to use when a new cutoff score is introduced
 * Assignment of a cutoff score will automatically reassign event ensemble participant assignments
 * The algorithm of event ensemble participant assignment is dependent on:
 * - score direction (ascending = low score wins, descending = high score wins)
 * - alternating scores (score assignment will alternate between event ensembles)
 *      - requisite event ensemble score assignment order
 * - the event ensemble abbreviation (abbr) will be assigned to a participating registrant as the acceptance abbreviation
 * - non-acceptance abbreviation values:
 *      - value of "na" (not accepted) will be assigned to any registrant with a complete score set and falling outside
 *          the relevant cutoff value
 *      - value of "inc" (incomplete) will be assigned to any registrant with an incomplete score set
 *      - value of "ns" (no show) will be assigned to any registrant with no score set.
 * - "ns" is used as the default value
 */
class Factory extends Model
{
    public $model; //will be an evaluated model
    public Collection $eventEnsembles;
    public VersionConfigAdjudication $versionConfigAdjudication;
    private bool $alternatingScores;
    private string $scoreDirection;

    public function setAlternatingScores(): void
    {
        $this->alternatingScores = (bool) $this->versionConfigAdjudication->alternating_scores;
    }

    #[NoReturn] public function setScore(
        $eventEnsembles,
        $versionconfigAdjudication,
        int $score,
        int $voicePartId
    ): void {
        //evaluate ensemble assignment (ex: StackedScoresDescendingSingleEnsembleAlgorithm )
        $this->setModel($eventEnsembles, $versionconfigAdjudication);

        //register cut-off score
        $this->model->registerCutoff($eventEnsembles, $versionconfigAdjudication, $score, $voicePartId);

        //assign accepted bool and acceptance_abbr to audition_results
        $this->model->acceptParticipants($eventEnsembles, $versionconfigAdjudication, $score, $voicePartId);
    }

    public function setModel($eventEnsembles, $versionConfigAdjudication): void
    {
        $scoreType = $versionConfigAdjudication->alternating_scores ? 'AlternatingScores' : 'StackedScores';
        $direction = $versionConfigAdjudication->scores_ascending ? 'Ascending' : 'Descending';
        $ensembleCount = $eventEnsembles->count() === 1 ? 'SingleEnsemble' : 'MultipleEnsembles';

        //use App\Models\AuditionResults\StackedScoresDescendingSingleEnsembleAlgorithm;
        $modelName = $scoreType.$direction.$ensembleCount.'Algorithm';
        $model = 'App\Models\AuditionResults\\'.$modelName;
        $this->model = new $model();

    }

    public function setScoreDirection($versionConfigAdjudication): void
    {
        $this->scoreDirection = $versionConfigAdjudication->scores_ascending ? 'asc' : 'desc';
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function init(): void
    {

    }
}
