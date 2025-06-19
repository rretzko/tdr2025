<?php

namespace App\Services;

use App\Models\Programs\ProgramSelection;

class ReorderConcertSelectionsService
{
    public function __construct(int $programId)
    {
        $programSelections = ProgramSelection::where('program_id', $programId)->orderBy('order_by')->get();
        $selectionCount = $programSelections->count();
        $counter = 1;
        if ($selectionCount > 0) {
            foreach ($programSelections as $programSelection) {
                $programSelection->update(['order_by' => $counter]);
                $counter++;
            }
        }
    }
}
