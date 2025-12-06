<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfEstimateDataFactory;
use App\Data\Pdfs\PdfRegistrationCardCandidateDataFactory;
use App\Data\Pdfs\PdfRegistrationCardSchoolDataFactory;
use App\Data\Pdfs\PdfRegistrationCardVoicePartDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
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
     * @todo Possible refactoring of methods and *DataFactorys into a single entity
     */
    public function candidate(Request $request, Candidate $candidate)
    {
        $versionId = UserConfig::getValue('versionId');
        $path = $this->getPath($versionId);

        $data = new PdfRegistrationCardCandidateDataFactory($candidate);
        $dto = $data->getDto();

        $pdf = PDF::loadView($path, compact('dto'));

        return $pdf->download('candidate_'.$candidate->id.'.pdf');
    }

    /**
     * @throws \Exception
     */
    public function school(Request $request, School $school)
    {
        $versionId = UserConfig::getValue('versionId');
        $version = Version::find($versionId);
        $path = $this->getPath($versionId);
        Log::info('path: '.$path);
        $data = new PdfRegistrationCardSchoolDataFactory($version, $school);
        $dto = $data->getDto();

        $pdf = PDF::loadView($path, compact('dto'));

        $fileName = 'registrationCards_'.Str::camel($school->name).'.pdf';

        return $pdf->download($fileName);
    }

    public function voicePart(Request $request, VoicePart $voicePart)
    {
        $versionId = UserConfig::getValue('versionId');
        $version = Version::find($versionId);
        $path = $this->getPath($versionId);
        Log::info('path: '.$path);
        $data = new PdfRegistrationCardVoicePartDataFactory($version, $voicePart);
        $dto = $data->getDto();

        date_default_timezone_set('America/New_York');
        $pdf = PDF::loadView($path, compact('dto'));

        $fileName = 'registrationCards_'.$voicePart->abbr.'.pdf';

        return $pdf->download($fileName);
    }

    private function getPath(int $versionId): string
    {
        $service = new FindPdfPathService;
        return $service->findRegistrationCardPath($versionId);
    }


}
