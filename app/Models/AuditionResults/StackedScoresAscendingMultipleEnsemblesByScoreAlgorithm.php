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

    private array $altVoicePartIds = [];
    private array|SupportCollection $cutoffs = [];
    private int $eventEnsembleId = 0;
    private array $eventEnsembleIds = [];
    private string $eventEnsembleAbbr = 'xx';
    private array $leadVoicePartIds = [];
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

            $acceptedAbbr = $this->determineAcceptanceAbbr($result->score_count, $accepted, $result->total, $result->voice_part_id);

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
        int $score,
        int $voicePartId
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
        $altVoicings = explode(',',$altEventEnsemble->voice_part_ids);

Log::info("score: $score, altCutoff: $altCutoff, accepted: $accepted, altVoicings: " . implode(',',$altVoicings) . " voicePartId: $voicePartId");
        if (($scoreCount == $this->maxScoreCount) && $accepted && ($score <= $altCutoff) && (in_array($voicePartId, $altVoicings))) {
            $cutoffAbbr = $altAbbr;
        }

        //accepted into lead ensemble
        $leadEventEnsemble = EventEnsemble::find($this->eventEnsembleIds[0]);
        $leadAbbr = $leadEventEnsemble->abbr;
        $leadCutoff = $this->cutoffs[$this->eventEnsembleIds[0]];
        $leadVoicings = explode(',',$leadEventEnsemble->voice_part_ids);

//Log::info("score: $score, leadCutoff: $leadCutoff, accepted: $accepted");
        if (($scoreCount == $this->maxScoreCount) && $accepted && ($score <= $leadCutoff) && (in_array($voicePartId, $leadVoicings))) {
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

        //current cutoff for alternate ensemble
        $alternateCutoff = $this->cutoffs[$this->eventEnsembleIds[1]] ?? 0;

        // Determine which ensemble to update
        if (in_array($voicePartId, $this->leadVoicePartIds) && ($leadCutoff === 0 || $score <= $leadCutoff)) {
            // No cutoff exists or score beats lead cutoff
            $ensembleId = $this->eventEnsembleIds[0];
            $this->updateVersionCutoff($score, $voicePartId, $ensembleId, $versionConfigAdjudication);

        } elseif (in_array($voicePartId, $this->altVoicePartIds) && ($alternateCutoff === 0 || $score > $alternateCutoff)) {
            // LeadCutoff exists, alternativeCutoff is missing and score is greater than leadCutoff
            $ensembleId = $this->eventEnsembleIds[1];
            $this->updateVersionCutoff($score, $voicePartId, $ensembleId, $versionConfigAdjudication);

        } elseif (in_array($voicePartId, $this->leadVoicePartIds) && ($score > $leadCutoff) && ($score < $alternateCutoff)) {
            // score is greater than lead and less than alternate cutoff
            // user is making the lead ensemble larger
            $ensembleId = $this->eventEnsembleIds[0];
            $this->updateVersionCutoff($score, $voicePartId, $ensembleId, $versionConfigAdjudication);

        } elseif (in_array($voicePartId, $this->altVoicePartIds) && (! in_array($voicePartId, $this->leadVoicePartIds)) && ($score < $alternateCutoff)) {
            // voicePartId ONLY exists in alternative ensemble and score is less than alternate cutoff
            $ensembleId = $this->eventEnsembleIds[1];
            $this->updateVersionCutoff($score, $voicePartId, $ensembleId, $versionConfigAdjudication);

        } elseif (in_array($voicePartId, $this->leadVoicePartIds) && (! in_array($voicePartId, $this->altVoicePartIds)) && ($score < $leadCutoff)) {
            // voicePartId ONLY exists in lead ensemble and score is less than lead cutoff
            $ensembleId = $this->eventEnsembleIds[0];
            $this->updateVersionCutoff($score, $voicePartId, $ensembleId, $versionConfigAdjudication);

        } else {
            if(in_array($voicePartId, $this->leadVoicePartIds)) {
                // Score didn't beat either cutoff, promote alternate to lead
                $ensembleId = $this->eventEnsembleIds[0];
                $score = $alternateCutoff;
                $this->updateVersionCutoff($score, $voicePartId, $ensembleId, $versionConfigAdjudication);
            }
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
                ->value('score') ?? 0;
        }

        //set Voice Part Ids
        $this->leadVoicePartIds = explode(',', EventEnsemble::find($this->eventEnsembleIds[0])->voice_part_ids);
        $this->altVoicePartIds = explode(',', EventEnsemble::find($this->eventEnsembleIds[1])->voice_part_ids);

    }

    private function updateVersionCutoff(int $score, int $voicePartId, int $ensembleId, VersionConfigAdjudication $versionConfigAdjudication)
    {
        $eventEnsemble = EventEnsemble::find($ensembleId);
        $voicings = explode(',',$eventEnsemble->voice_part_ids);

        if (in_array($voicePartId, $voicings)) {

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
}
