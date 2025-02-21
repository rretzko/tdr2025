<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfScoringRosterDataFactory;
use App\Http\Controllers\Controller;
use App\Jobs\LargePdfProcessJob;
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
        $start = strtotime('now');
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
        $rows = $dto['rows'];
        $rowsScores = $dto['rowsScores'];
        Log::info(count($rows[0]) . ' | ' . count($rowsScores));
        $fileNameRoot = '_ScoringRoster_' . Carbon::now()->format('Ymd_His') . '.pdf';
        $fileName = $voicePart->abbr . $fileNameRoot;

        if ($eventEnsembleId) {
            $eventEnsemble = EventEnsemble::find($eventEnsembleId);
            $fileName = $eventEnsemble->abbr . $fileNameRoot;
        }

        if (count($rows[0]) < 401) { //return pdf directly to user

            $pdf = PDF::loadView($path, compact('dto', 'rows', 'rowsScores'))
                ->setPaper('letter', 'landscape');

            return $pdf->download($fileName);

        } else { //process pdf in queue and return to user via email

            LargePdfProcessJob::dispatch($rows, $rowsScores, $fileName, $dto);

            $emailAddress = auth()->user()->email;
            return redirect()->back()->with('success', "The pdf is being created and will be sent to your $emailAddress email address when completed.");
        }
    }
}
