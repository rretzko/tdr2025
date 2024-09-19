<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfAdjudicationBackupPaperDataFactory;
use App\Http\Controllers\Controller;
use App\Services\FindPdfPathService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdjudicationBackupPaperController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $roomId)
    {
        $service = new FindPdfPathService;
        $path = $service->findAdjudicationBackupPaperPath($roomId);

        $data = new PdfAdjudicationBackupPaperDataFactory($roomId);
        $dto = $data->getDto();


        $pdf = PDF::loadView($path, compact('dto'))
            ->setPaper('letter', 'landscape');

        $prefix = $candidate->student->user->last_name.$candidate->student->user->first_name;

        return $pdf->download($prefix.'_scores.pdf');
    }
}
