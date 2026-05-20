<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdfs;

use App\Http\Controllers\Controller;
use App\Models\Events\Versions\Version;
use App\Services\VersionSummaryReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SummaryReportPdfController extends Controller
{
    public function __invoke(Request $request, Version $version)
    {
        ini_set('memory_limit', '256M');
        set_time_limit(120);

        $service = new VersionSummaryReportService($version->id);
        $report  = $service->getData();

        $pdf = PDF::loadView('pdfs.tabrooms.summaryReport', compact('report', 'version'))
            ->setPaper('letter', 'portrait')
            ->setOption('isPhpEnabled', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $filename = 'summary_report_' . $version->id . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}
