<?php

namespace App\Models\AuditionResults;

use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Scoring\VersionCutoff;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Services\MaxScoreCountService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AlternatingScoresAscendingMultipleEnsemblesByScoreAlgorithm extends Model implements AlgorithmInterface
{
    private array $eventEnsembles = [];
    private int $versionId = 0;
    private int $voicePartId = 0;

    public function acceptParticipants(
        Collection $eventEnsembles,
        VersionConfigAdjudication $versionConfigAdjudication,
        int $score, //target score to meet or beat
        int $voicePartId
    ): void {

        $this->maxScoreCount = MaxScoreCountService::getMaxScoreCount($versionConfigAdjudication);

        $auditionResults = $this->getAuditionResults($eventEnsembles, $versionConfigAdjudication, $voicePartId);

        //set accepted to 0 and acceptance abbr to "ns"
        $this->setParticipantsToDefault($auditionResults);

        $this->setAcceptedAndAcceptanceAbbr($auditionResults, $score);
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

    private function setParticipantsToDefault(Builder $auditionResults): void
    {
        $auditionResults->update(
            [
                'accepted' => 0,
                'acceptance_abbr' => 'ns',
            ]
        );
    }

    private function setAcceptedAndAcceptanceAbbr(
        Builder $auditionResults, //audition_results rows matching version_id and voice_part_id
        int $score  //target score to meet or beat
    ): void
    {

        foreach ($auditionResults->get() as $candidate) {

            //bool
            $accepted = $this->isAccepted($candidate->total, $score);

            $acceptedAbbr = $this->determineAcceptanceAbbr($candidate->score_count, $accepted, $candidate->total);

            $candidate->update(
                [
                    'accepted' => $accepted,
                    'acceptance_abbr' => $acceptedAbbr,
                ]
            );
        }
    }

    private function isAccepted(
        int $candidateTotal,
        int $score //target score to meet or beat
    ): int
    {
        return $candidateTotal <= $score ? 1 : 0;
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function determineAcceptanceAbbr(
        int $candidateScoreCount,
        int $accepted,
        int $candidateTotal,
    ): string {

        //no-show
        if ($candidateScoreCount == 0) {
            return 'ns';
        }

        //incomplete
        if ($candidateScoreCount < $this->maxScoreCount) {
            return 'inc';
        }

        //error; too many scores
        if ($candidateScoreCount > $this->maxScoreCount) {
            return 'err';
        }

        //accepted into an ensemble and only one ensemble qualifies for the candidate
        if ($candidateScoreCount == $this->maxScoreCount &&
            $accepted &&
            count($this->eventEnsembles) === 1) {

            return $this->eventEnsembles[0]['abbr'];
        }

        //accepted into an ensemble and multiple ensembles qualify for the candidate
        if ($candidateScoreCount == $this->maxScoreCount &&
            $accepted &&
            count($this->eventEnsembles) > 1) {

            $uniqueScores = $this->getUniqueScores();

            //ex: $candidateTotal = 10, therefore key of $uniqueScores === 3
            $scoreRank = array_search($candidateTotal, $uniqueScores);

            return ($scoreRank % 2) //odd $scoreRank
                ? $this->eventEnsembles[0]['abbr']
                : $this->eventEnsembles[1]['abbr'];
        }

        //not accepted
        return 'na';
    }

    /**
     * return array of unique scores (no duplicates) matching
     * the current version_id and voice_part_id
     * @return array
     */
    private function getUniqueScores(): array
    {
        $totals = AuditionResult::query()
            ->where('version_id', $this->versionId)
            ->where('voice_part_id', $this->voicePartId)
            ->distinct()
            ->orderBy('total')
            ->pluck('total')
            ->toArray();

        // Reset the keys and set the first key to 1 so that an even/odd sequence is logical
        $totals = array_values($totals);
        return array_combine(range(1, count($totals)), $totals);
    }

    public function registerCutoff(
        Collection $eventEnsembles,
        VersionConfigAdjudication $versionConfigAdjudication,
        int $score, //target score to meet or beat
        int $voicePartId
    ): void {

        $this->versionId = $versionConfigAdjudication->version_id;
        $this->voicePartId = $voicePartId;

        $this->setEventEnsembleVars($eventEnsembles, $voicePartId);

        $this->updateVersionCutoffs($versionConfigAdjudication, $voicePartId, $score);
    }

    private function setEventEnsembleVars(Collection $eventEnsembles, int $voicePartId): void
    {
        foreach ($eventEnsembles as $ensemble) {
            if ($ensemble->voiceParts->where('id', $voicePartId)->first()) {
                $this->eventEnsembles[] =
                    [
                        'id' => $ensemble->id,
                        'abbr' => $ensemble->abbr,
                    ];
            }
        }
    }

    private function updateVersionCutoffs(
        VersionConfigAdjudication $versionConfigAdjudication,
        int $voicePartId,
        int $score
    ): void {
        foreach ($this->eventEnsembles as $eventEnsemble) {

            VersionCutoff::updateOrCreate(
                [
                    'version_id' => $versionConfigAdjudication->version_id,
                    'voice_part_id' => $voicePartId,
                    'event_ensemble_id' => $eventEnsemble['id'],
                ],
                [
                    'score' => $score,
                ]
            );
        }
    }
}
