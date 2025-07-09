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
        $libraryId = $request->query('libraryId', '');
        $libItems = LibItem::query()
            ->join('lib_titles', 'lib_titles.id', '=', 'lib_items.lib_title_id')
            ->whereIn('lib_items.id', $itemIds)
            ->select('lib_items.*', 'lib_titles.title')
            ->orderBy('lib_titles.alpha')
            ->get();

        $pdf = PDF::loadView($bladePath, compact('libItems', 'libraryId'));

        return $pdf->download($fileName);
    }
}
