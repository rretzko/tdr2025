<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfApplicationDataFactory;
use App\Data\Pdfs\PdfCandidateScoreDataFactory;
use App\Data\Pdfs\PdfCandidateScoresSchoolDataFactory;
use App\Data\Pdfs\PdfContractDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Models\Schools\School;
use App\Models\UserConfig;
use App\Services\FindPdfPathService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CandidateScoresConfidentialPdfController extends Controller
{
    /**
     * Handle the incoming request.
     * @param  Request  $request
     * @param  Candidate  $candidate
     * @return Response
     */
    public function __invoke(Request $request)
    {dd(__LINE__);
        $version = Version::find(UserConfig::getValue('versionId'));
        $service = new FindPdfPathService;
        $path = $service->findCandidateScoresSchoolPath($version);

        $school = School::find(UserConfig::getValue('schoolId'));
        $data = new PdfCandidateScoresSchoolDataFactory($school, $version);
        $dto = $data->getDto();

        $pdf = PDF::loadView($path, compact('dto'))
            ->setPaper('letter', 'landscape');

        $prefix = Str::snake($school->name);

        return $pdf->download($prefix.'_scores.pdf');
    }

}
