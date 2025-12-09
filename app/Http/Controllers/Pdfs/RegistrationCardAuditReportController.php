<?php

namespace App\Http\Controllers\Pdfs;

use App\Data\Pdfs\PdfAdjudicationBackupPaperDataFactory;
use App\Data\Pdfs\PdfRegistrationCardAuditReport;
use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Version;
use App\Services\FindPdfPathService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationCardAuditReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Version $version)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300); // 5 minutes

        $path = 'pdfs.registrationCards.registrationCardAuditReport';

        $data = new PdfRegistrationCardAuditReport($version);
        $registrants = $data->registrants;

        $pdf = PDF::loadView($path, compact('registrants'))
            ->setPaper('letter', 'portrait')
            ->setOption('isPhpEnabled', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $pdfName = 'registrationCardAuditReport'.'_'.date('Ymd_gis').'.pdf';

        return $pdf->stream($pdfName); // Use stream() instead of download()
    }
}
