<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfApplicationDataFactory;
use App\Data\Pdfs\PdfCandidateScoreDataFactory;
use App\Data\Pdfs\PdfContractDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\Candidate;
use App\Services\FindPdfPathService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CandidateScorePdfController extends Controller
{
    /**
     * Handle the incoming request.
     * @param  Request  $request
     * @param  Candidate  $candidate
     * @return Response
     */
    public function __invoke(Request $request, Candidate $candidate)
    {
        $service = new FindPdfPathService;
        $path = $service->findCandidateScorePath($candidate);

        $data = new PdfCandidateScoreDataFactory($candidate);
        $dto = $data->getDto();

        $pdf = PDF::loadView($path, compact('dto'))
            ->setPaper('letter', 'landscape');

        $prefix = $candidate->student->user->last_name.$candidate->student->user->first_name;

        return $pdf->download($prefix.'_scores.pdf');
    }

}
