<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfEstimateDataFactory;
use App\Data\Pdfs\PdfRegistrationCardCandidateDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Schools\School;
use App\Models\Students\VoicePart;
use App\Models\UserConfig;
use App\Services\FindPdfPathService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegistrationCardsController extends Controller
{
    /**
     * @throws \Exception
     */
    public function candidate(Request $request, Candidate $candidate)
    {
        $versionId = UserConfig::getValue('versionId');
        $path = $this->getPath($versionId);

        $data = new PdfRegistrationCardCandidateDataFactory($candidate);
        $dto = $data->getDto();

        $pdf = PDF::loadView($path, compact('dto'));

        return $pdf->download('estimate.pdf');
    }

    /**
     * @throws \Exception
     */
    private function getPath(int $versionId): string
    {
        $service = new FindPdfPathService;
        return $service->findRegistrationCardPath($versionId);
    }

    public function school(Request $request, School $schoolId)
    {
        dd($schoolId);
    }

    public function voicePart(Request $request, VoicePart $voicePartId)
    {
        dd($voicePartId);
    }
}
