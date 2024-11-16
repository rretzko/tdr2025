<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfScoringRosterDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Students\VoicePart;
use App\Models\UserConfig;
use App\Services\FindPdfPathService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScoringRosterController extends Controller
{
    public function __invoke(Request $request, VoicePart $voicePart = null, $private = false)
    {
        $versionId = UserConfig::getValue('versionId');

        $path = ($private)
            ? 'pdfs.tabrooms.scoringRosterPrivate'
            : 'pdfs.tabrooms.scoringRoster';

        $voicePartTest = $voicePart->descr === "ALL"
            ? null
            : $voicePart;

        $data = new PdfScoringRosterDataFactory($versionId, $voicePartTest);
        $dto = $data->getDto();

        set_time_limit(120);
        $pdf = PDF::loadView($path, compact('dto'))
            ->setPaper('letter', 'landscape');

        $fileName = $voicePart->abbr.'_ScoringRoster_'.Carbon::now()->format('Ymd_His').'.pdf';

        return $pdf->download($fileName);
    }
}
