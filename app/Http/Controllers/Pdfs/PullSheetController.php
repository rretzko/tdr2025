<?php

namespace App\Http\Controllers\Pdfs;

use App\Http\Controllers\Controller;
use App\Models\Libraries\Items\LibItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PullSheetController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $bladePath = 'pdfs.libraries.pullSheet';
        $fileName = 'pullSheet_'.date('Ymd_Gis').'.pdf';
        $itemIds = explode(',', $request->query('itemIds', ''));
        $libItems = LibItem::query()
            ->join('lib_titles', 'lib_titles.id', '=', 'lib_items.lib_title_id')
            ->whereIn('lib_items.id', $itemIds)
            ->orderBy('lib_titles.alpha')
            ->get();

        $pdf = PDF::loadView($bladePath, compact('libItems'));

        return $pdf->download($fileName);
    }
}
