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

class ScoringRosterController extends Controller
{
    public function __invoke(VoicePart $voicePart = null)
    {
        $versionId = UserConfig::getValue('versionId');
//        $path = $this->getPath($versionId);
        $path = 'pdfs.tabrooms.scoringRoster';

        $data = new PdfScoringRosterDataFactory($versionId, $voicePart);
        $dto = $data->getDto();

        $pdf = PDF::loadView($path, compact('dto'))
            ->setPaper('letter', 'landscape');

        $fileName = ($voicePart)
            ? $voicePart->abbr.'_ScoringRoster_'.Carbon::now()->format('Ymd_His').'.pdf'
            : 'AllVoices_ScoringRoster_'.Carbon::now()->format('Ymd_His').'pdf';

        return $pdf->download($fileName);
    }

    private function getPath(int $versionId): string
    {
        $service = new FindPdfPathService;
        return $service->findRegistrationCardPath($versionId);
    }
}
