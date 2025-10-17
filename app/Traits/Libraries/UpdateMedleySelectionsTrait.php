<?php

namespace App\Traits\Libraries;

use App\Models\Libraries\Items\Components\LibMedleySelection;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\LibItem;
use App\Services\Libraries\MakeAlphaService;
use Illuminate\Support\Str;

trait UpdateMedleySelectionsTrait
{
    public function updateMedleySelections(int $libItemId, int $teacherId, array $medleySelections): void
    {
        $libItem = LibItem::find($libItemId);

        if($libItem &&
            ($libItem->item_type === 'medley')
            && (count($medleySelections) > 0)
        ) {

            //remove empty rows
            $filtered = array_filter($medleySelections);

            foreach($filtered AS $title){

                $fTitle = Str::title(trim($title));
                $libTitle = LibTitle::firstOrCreate(
                    ['title' => $fTitle],
                    [
                        'teacher_id' => $teacherId,
                        'alpha' => MakeAlphaService::alphabetize($fTitle),
                    ]
                );

                LibMedleySelection::updateOrCreate(
                    [
                        'lib_title_id' => $libTitle->id,
                        'lib_item_id' => $libItem->id,
                    ],
                    [
                        'teacher_id' => $teacherId,
                    ]
                );
            }
        }
    }
}
