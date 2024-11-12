<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\AuditionResult;
use App\Models\Events\Versions\VersionConfigAdjudication;
use Illuminate\Database\Eloquent\Collection;

class AuditionResultsScoreColorsService
{
    private array $colors;

    public function __construct(
        array $scores,
        private VersionConfigAdjudication $versionConfigAdjudication,
        private int $voicePartId,
        private readonly Collection $eventEnsembles
    ) {
    }

    /**
     * @param  array  $scores
     * @return array
     * @todo color schemes are hard-coded by ensemble abbreviation.
     * @todo This s/b turned into logical workflow (first ensemble = blue, second ensemble = yellow, third ensemble = green, etc)
     */
    public function getColors(array $scores): array
    {
        $colors = [];

        foreach ($scores as $total) {

            $acceptanceAbbr = AuditionResult::query()
                ->where('version_id', $this->versionConfigAdjudication->version_id)
                ->where('voice_part_id', $this->voicePartId)
                ->where('total', $total)
                ->where('accepted', 1)
                ->select('acceptance_abbr')
                ->value('acceptance_abbr');

            $colorSchemes = [
                'mx' => 'bg-blue-100 text-black hover:bg-blue-400 hover:text-white',
                'hsc' => 'bg-blue-800 text-white hover:bg-blue-600',
                'msc' => ' bg-yellow-100 text-black hover:bg-yellow-400'
            ];

            $colorScheme = array_key_exists($acceptanceAbbr, $colorSchemes)
                ? $colorSchemes[$acceptanceAbbr]
                : 'bg-transparent text-black hover:bg-gray-400';

            $colors[] = [
                'score' => $total,
                'colors' => $colorScheme,
            ];
        }

        return $colors;
    }
}
