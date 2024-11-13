<?php

namespace App\Models\AuditionResults;


use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Scoring\VersionCutoff;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Services\MaxScoreCountService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\NoReturn;

class StackedScoresAscendingMultipleEnsemblesByVoicePartIdAlgorithm extends Model implements AlgorithmInterface
{

    private int $eventEnsembleId = 0;
    private string $eventEnsembleAbbr = 'xx';
    private int $maxScoreCount = 0;

    #[NoReturn] public function acceptParticipants(
        Collection $eventEnsembles,
        VersionConfigAdjudication $versionConfigAdjudication,
        int $score,
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
        Builder $auditionResults,
        int $score
    ): void {

        foreach ($auditionResults->get() as $result) {

            $accepted = $this->isAccepted($result->total, $score);
            $acceptedAbbr = $this->determineAcceptanceAbbr($result->score_count, $accepted);

            $result->update(
                [
                    'accepted' => $accepted,
                    'acceptance_abbr' => $acceptedAbbr,
                ]
            );
        }
    }

    private function isAccepted(int $total, int $score): int
    {
        return $total <= $score ? 1 : 0;
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function determineAcceptanceAbbr(
        int $scoreCount,
        int $accepted,
    ): string {
        //no-show
        if ($scoreCount == 0) {
            return 'ns';
        }

        //incomplete
        if ($scoreCount < $this->maxScoreCount) {
            return 'inc';
        }

        //error; too many scores
        if ($scoreCount > $this->maxScoreCount) {
            return 'err';
        }

        //accepted into an ensemble
        if ($scoreCount == $this->maxScoreCount && $accepted) {
            return $this->eventEnsembleAbbr;
        }

        //not accepted
        return 'na';
    }

    /**
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
    ): void {
        $this->setEventEnsembleVars($eventEnsembles, $voicePartId);

        VersionCutoff::updateOrCreate(
            [
                'version_id' => $versionConfigAdjudication->version_id,
                'voice_part_id' => $voicePartId,
                'event_ensemble_id' => $this->eventEnsembleId,
            ],
            [
                'score' => $score,
            ]
        );
    }

    private function setEventEnsembleVars(Collection $eventEnsembles, int $voicePartId): void
    {
        foreach ($eventEnsembles as $ensemble) {
            if ($ensemble->voiceParts->where('id', $voicePartId)->first()) {
                $this->eventEnsembleId = $ensemble->id;
                $this->eventEnsembleAbbr = $ensemble->abbr;
            }
        }
    }
}
