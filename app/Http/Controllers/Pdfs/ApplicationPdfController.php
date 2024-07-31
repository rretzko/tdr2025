<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\PdfApplicationDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Services\FindPdfPathService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ApplicationPdfController extends Controller
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
        $path = $service->findApplicationPath($candidate);

        $data = new PdfApplicationDataFactory($candidate);
        $dto = $data->getDto();

        $pdf = PDF::loadView($path, compact('dto'));

        return $pdf->download('application.pdf');
    }

}
