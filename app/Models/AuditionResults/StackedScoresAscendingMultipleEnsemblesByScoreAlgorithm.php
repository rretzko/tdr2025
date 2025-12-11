<?php

namespace App\Models\AuditionResults;


use App\Models\Events\EventEnsemble;
use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Events\Versions\Scoring\VersionCutoff;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionEventEnsembleOrder;
use App\Services\MaxScoreCountService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

class StackedScoresAscendingMultipleEnsemblesByScoreAlgorithm extends Model implements AlgorithmInterface
{

    private array|SupportCollection $cutoffs = [];
    private int $eventEnsembleId = 0;
    private array $eventEnsembleIds = [];
    private string $eventEnsembleAbbr = 'xx';
    private int $maxScoreCount = 0;

    public function acceptParticipants(
        Collection $eventEnsembles,
        VersionConfigAdjudication $versionConfigAdjudication,
        int $score,
        int $voicePartId
    ): void {

        $this->maxScoreCount = MaxScoreCountService::getMaxScoreCount($versionConfigAdjudication);

        //query builder for audition results related to $voicePartId
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

            $accepted = $this->isAccepted($result->total, $score, $result->voice_part_id, $result->version_id);

            $acceptedAbbr = $this->determineAcceptanceAbbr($result->score_count, $accepted, $result->total);

            $result->update(
                [
                    'accepted' => $accepted,
                    'acceptance_abbr' => $acceptedAbbr,
                ]
            );
        }
    }

    private function isAccepted(int $total, int $score, int $voicePartId, int $versionId): bool
    {
        $highestScore = VersionCutoff::query()
            ->where('voice_part_id', $voicePartId)
            ->where('version_id', $versionId)
            ->max('score');

        return $total <= $highestScore;
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
        int $score, //score selected by user
        int $voicePartId
    ): void {

        //store $this->eventEnsembleIds, current cutoffs
        $this->setEventEnsembleVars($voicePartId, $score, $versionConfigAdjudication);

        //register cut-off score
        $this->registerCutoffScore($score, $versionConfigAdjudication, $voicePartId);
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function determineAcceptanceAbbr(
        int $scoreCount,
        bool $accepted,
        int $score
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

        //accepted into alternate ensemble
        $altEventEnsemble = EventEnsemble::find($this->eventEnsembleIds[1]);
        $altAbbr = $altEventEnsemble->abbr;
        $altCutoff = $this->cutoffs[$this->eventEnsembleIds[1]];
        $cutoffAbbr = 'na'; //default to not-accepted
//Log::info("score: $score, altCutoff: $altCutoff, accepted: $accepted");
        if (($scoreCount == $this->maxScoreCount) && $accepted && ($score <= $altCutoff)) {
            $cutoffAbbr = $altAbbr;
        }

        //accepted into lead ensemble
        $leadEventEnsemble = EventEnsemble::find($this->eventEnsembleIds[0]);
        $leadAbbr = $leadEventEnsemble->abbr;
        $leadCutoff = $this->cutoffs[$this->eventEnsembleIds[0]];
//Log::info("score: $score, leadCutoff: $leadCutoff, accepted: $accepted");
        if (($scoreCount == $this->maxScoreCount) && $accepted && ($score <= $leadCutoff)) {
            $cutoffAbbr = $leadAbbr;
        }
//Log::info('scoreCount == $this->maxScoreCount: ' .  ($scoreCount == $this->maxScoreCount));
//Log::info('score: ' . $score);
//Log::info('accepted: ' . $accepted);
//Log::info('leadCutoff: ' . $leadCutoff);
//Log::info('altCutoff: ' . $altCutoff);
//Log::info('leadAbbr: ' . $leadAbbr);
//Log::info('cutoffAbbr: ' . $cutoffAbbr);
//Log::info('***************************************');

        //not accepted
        return $cutoffAbbr;
    }

    private function registerCutoffScore(int $score, VersionConfigAdjudication $versionConfigAdjudication, int $voicePartId): void
    {
        //current cutoff for lead ensemble
        $leadCutoff = $this->cutoffs[$this->eventEnsembleIds[0]] ?? 0;
//Log::info('score: ' . $score . ' leadCutoff: ' . $leadCutoff);
        //current cutoff for alternate ensemble
        $alternateCutoff = $this->cutoffs[$this->eventEnsembleIds[1]] ?? 0;
//Log::info(' alternateCutoff: ' . $alternateCutoff);
//Log::info('+++++++++++++++++++++++++++++++++++++++');
        // Determine which ensemble to update
        if ($leadCutoff === 0 || $score <= $leadCutoff) {
            // No cutoff exists or score beats lead cutoff
            $ensembleId = $this->eventEnsembleIds[0];
            $this->updateVersionCutoff($score, $voicePartId, $ensembleId, $versionConfigAdjudication);

        } elseif ($alternateCutoff === 0 || $score > $alternateCutoff) {
            // LeadCutoff exists, alternativeCutoff is missing and score is greater than leadCutoff
            $ensembleId = $this->eventEnsembleIds[1];
            $this->updateVersionCutoff($score, $voicePartId, $ensembleId, $versionConfigAdjudication);

        }elseif (($score > $leadCutoff) && ($score < $alternateCutoff)) {
            // score is greater than lead and less than alternate cutoff
            // user is making the lead ensemble larger
            $ensembleId = $this->eventEnsembleIds[0];
            $this->updateVersionCutoff($score, $voicePartId, $ensembleId, $versionConfigAdjudication);
        } else {
            // Score didn't beat either cutoff, promote alternate to lead
            $ensembleId = $this->eventEnsembleIds[0];
            $score = $alternateCutoff;
            $this->updateVersionCutoff($score, $voicePartId, $ensembleId, $versionConfigAdjudication);
        }

        //reset local $this->cutoffs
        $this->cutoffs = [];
        foreach(array_reverse($this->eventEnsembleIds) as $eventEnsembleId) {
            $this->cutoffs[$eventEnsembleId] = VersionCutoff::query()
                ->where('version_id', $versionConfigAdjudication->version_id)
                ->where('event_ensemble_id', $eventEnsembleId)
                ->where('voice_part_id', $voicePartId)
                ->value('score') ?? 0;
        }
    }

    private function setEventEnsembleVars(int $voicePartId, int $score, VersionConfigAdjudication $versionConfigAdjudication): void
    {
        $versionId = $versionConfigAdjudication->version_id;

        //event ensemble ids
        $this->eventEnsembleIds = VersionEventEnsembleOrder::query()
            ->where('version_id', $versionId)
            ->orderBy('order_by')
            ->pluck('event_ensemble_id')
            ->toArray();

        //current cutoffs
        foreach($this->eventEnsembleIds as $eventEnsembleId) {
            $this->cutoffs[$eventEnsembleId] = VersionCutoff::query()
                ->where('version_id', $versionId)
                ->where('voice_part_id', $voicePartId)
                ->where('event_ensemble_id', $eventEnsembleId)
                ->value('score');
        }
    }

    private function updateVersionCutoff(int $score, int $voicePartId, int $ensembleId, VersionConfigAdjudication $versionConfigAdjudication)
    {
        VersionCutoff::updateOrCreate(
            [
                'version_id' => $versionConfigAdjudication->version_id,
                'voice_part_id' => $voicePartId,
                'event_ensemble_id' => $ensembleId,
            ],
            [
                'score' => $score
            ]
        );
    }
}
