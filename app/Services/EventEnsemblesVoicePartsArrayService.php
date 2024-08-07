<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\Registrant;
use App\Models\Students\VoicePart;
use App\Models\UserConfig;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class EventEnsemblesVoicePartsArrayService
{
    private array $summaryDetails = [];
    private array $voiceParts = [];

    public function __construct(private readonly Collection $eventEnsembles)
    {
        $this->init();
    }

    /**
     * @return array
     * ex: array:8 [▼ // app\Data\Pdfs\PdfEstimateDataFactory.php:174
     * 63 => "Soprano I"
     * 64 => "Soprano II"
     * 65 => "Alto I"
     * 66 => "Alto II"
     * 67 => "Tenor I"
     * 68 => "Tenor II"
     * 69 => "Bass I"
     * 70 => "Bass II"
     * ]
     */
    public function getArray()
    {
        return $this->voiceParts;
    }

    /**
     * @return array
     * ex. array:8 [▼ // app\Services\EventEnsemblesVoicePartsArrayService.php:76
     * 0 => array:2 [▼
     * "id" => 63
     * "abbr" => "SI"
     * ]
     * 1 => array:2 [▼
     * "id" => 64
     * "abbr" => "SII"
     * ]
     * etc...
     * ]
     */
    public function getEstimateSummaryArray(): array
    {
        return $this->summaryDetails;
    }

    private function init(): void
    {
        $ensembles = $this->eventEnsembles;

        $voiceParts = $ensembles->flatMap(function ($ensemble) {
            return explode(',', $ensemble->voice_part_ids);
        })->unique();

        $registrant = new Registrant(UserConfig::getValue('schoolId'), UserConfig::getValue('versionId'));

        $this->voiceParts = VoicePart::query()
            ->whereIn('id', $voiceParts)
            ->whereNot('descr', 'ALL')
            ->orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();

        $this->summaryDetails = $this->buildSummaryDetails($voiceParts, $registrant);
    }

    private function buildSummaryDetails(SupportCollection $voiceParts, Registrant $registrant): array
    {
        $voicePartsArray = $this->getEstimateSummaryVoiceParts($voiceParts);

        foreach ($voicePartsArray as $key => $voicePart) {

            $voicePartsArray[$key]['count'] = $registrant->getCountOfVoicePart($voicePart['id']);
        }

        return $voicePartsArray;
    }

    private function getEstimateSummaryVoiceParts(SupportCollection $voiceParts): array
    {
        return VoicePart::query()
            ->whereIn('id', $voiceParts)
            ->whereNot('descr', 'ALL')
            ->orderBy('order_by')
            ->select('voice_parts.id', 'voice_parts.abbr')
            ->get()
            ->toArray();
    }


}
