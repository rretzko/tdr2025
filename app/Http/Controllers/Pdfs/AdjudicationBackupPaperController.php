<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfAdjudicationBackupPaperDataFactory;
use App\Http\Controllers\Controller;
use App\Services\FindPdfPathService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $prefix = (is_a($dto['rooms'], 'App\Models\Events\Versions\Room'))
            ? Str::camel($dto['rooms']->room_name)
            : 'allRooms';

        $pdfName = 'adjudicationBackup'.'_'.$prefix.'.pdf';

        return $pdf->download($pdfName);
    }
}
