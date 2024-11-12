<?php

namespace App\Models\AuditionResults;

use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Scoring\VersionCutoff;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class StackedScoresDescendingSingleEnsembleAlgorithm extends Model implements AlgorithmInterface
{
    private int $maxScoreCount = 0;

    public function acceptParticipants(
        Collection $eventEnsembles,
        VersionConfigAdjudication $versionconfigAdjudication,
        int $score,
        int $voicePartId
    ) {

        $this->maxScoreCount = $this->setMaxScoreCount($versionconfigAdjudication);

        $auditionResults = $this->getAuditionResults($eventEnsembles, $versionconfigAdjudication, $voicePartId);

        //set accepted to 0 and acceptance abbr to "ns"
        $this->setParticipantsToDefault($auditionResults);

        $this->setAcceptedAndAcceptanceAbbr($auditionResults, $score, $eventEnsembles);
    }

    public function registerCutoff($eventEnsembles, $versionconfigAdjudication, int $score, int $voicePartId)
    {
        VersionCutoff::updateOrCreate(
            [
                'version_id' => $versionconfigAdjudication->version_id,
                'voice_part_id' => $voicePartId,
                'event_ensemble_id' => $eventEnsembles->first()->id,
            ],
            [
                'score' => $score,
            ]
        );
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function determineAcceptanceAbbr(
        int $scoreCount,
        int $maxScoreCount,
        int $accepted,
        string $eventAbbr
    ): string {
        //no-show
        if ($scoreCount == 0) {
            return 'ns';
        }

        //incomplete
        if ($scoreCount < $maxScoreCount) {
            return 'inc';
        }

        //error; too many scores
        if ($scoreCount > $maxScoreCount) {
            return 'err';
        }

        //accepted into an ensemble
        if ($scoreCount == $maxScoreCount && $accepted) {
            return $eventAbbr;
        }

        //not accepted
        return 'na';
    }

    private function getAuditionResults(
        Collection $eventEnsembles,
        VersionConfigAdjudication $versionConfigAdjudication,
        int $voicePartId
    ): Builder {
        return AuditionResult::query()
            ->where('version_id', $versionConfigAdjudication->version_id)
            ->where('voice_part_id', $voicePartId);
    }

    private function isAccepted(int $total, int $score): int
    {
        return $total >= $score ? 1 : 0;
    }

    private function setAcceptedAndAcceptanceAbbr(
        Builder $auditionResults,
        int $score,
        Collection $eventEnsembles
    ): void {
        $maxScoreCount = $this->maxScoreCount;
        $eventAbbr = $eventEnsembles->first()->abbr;

        foreach ($auditionResults->get() as $result) {

            $accepted = $this->isAccepted($result->total, $score);
            $acceptedAbbr = $this->determineAcceptanceAbbr($result->score_count, $maxScoreCount, $accepted, $eventAbbr);

            $result->update(
                [
                    'accepted' => $accepted,
                    'acceptance_abbr' => $acceptedAbbr,
                ]
            );
        }
    }

    private function setMaxScoreCount(VersionConfigAdjudication $versionConfigAdjudication): int
    {
        $judgeCount = $versionConfigAdjudication->judge_per_room_count;
        $scoreFactor = new ScoreFactor();
        $factorCount = $scoreFactor->getCountByVersionId($versionConfigAdjudication->version_id);

        return ($judgeCount * $factorCount);
    }

    private function setParticipantsToDefault(Builder $auditionResults): void
    {
        $auditionResults->update(
            [
                'accepted' => 0,
                'acceptance_abbr' => 'ns',
            ]
        );
    }


}
