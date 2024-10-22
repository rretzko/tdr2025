<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfEstimateDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Version;
use App\Services\FindPdfPathService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class EstimatePdfController extends Controller
{
    /**
     * Handle the incoming request.
     * @param  Request  $request
     * @param  Candidate  $candidate
     * @return Response
     */
    public function __invoke(Request $request, Version $version)
    {
        $service = new FindPdfPathService;
        $path = $service->findEstimatePath($version);
        //Log::info('*** path: '.$path.' ***');
        $data = new PdfEstimateDataFactory($version);
        $dto = $data->getDto();

        $pdf = PDF::loadView($path, compact('dto'));

        return $pdf->download('estimate.pdf');
    }

}
