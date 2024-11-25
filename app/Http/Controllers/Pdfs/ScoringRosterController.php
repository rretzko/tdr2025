<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfScoringRosterDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\EventEnsemble;
use App\Models\Students\VoicePart;
use App\Models\UserConfig;
use App\Services\FindPdfPathService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScoringRosterController extends Controller
{
    public function __invoke(Request $request, VoicePart $voicePart = null, $private = false, $eventEnsembleId = 0)
    {
        $versionId = UserConfig::getValue('versionId');

        $path = ($private)
            ? 'pdfs.tabrooms.scoringRosterPrivate'
            : 'pdfs.tabrooms.scoringRoster';

        $voicePartTest = $voicePart->descr === "ALL"
            ? null
            : $voicePart;

        $data = new PdfScoringRosterDataFactory($versionId, $voicePartTest, $eventEnsembleId);
        $dto = $data->getDto();

        set_time_limit(120);
        $pdf = PDF::loadView($path, compact('dto'))
            ->setPaper('letter', 'landscape');

        $fileNameRoot = '_ScoringRoster_'.Carbon::now()->format('Ymd_His').'.pdf';
        $fileName = $voicePart->abbr.$fileNameRoot;

        if ($eventEnsembleId) {
            $eventEnsemble = EventEnsemble::find($eventEnsembleId);
            $fileName = $eventEnsemble->abbr.$fileNameRoot;
        }

        return $pdf->download($fileName);
    }
}
