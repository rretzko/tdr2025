<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfApplicationDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Participations\Candidate;
use App\Services\FindPdfPathService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

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

        if ($pdf) {
            $candidate->addApplicationDownloadCount();
        }

        $prefix = Str::camel($candidate->student->user->name);

        return $pdf->download($prefix.'_application.pdf');
    }

}
