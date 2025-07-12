<?php

namespace App\Traits\Libraries;

use App\Models\Libraries\Items\Components\LibItemLocation;
use Illuminate\Http\Request;

trait LibrarySetLocationsTrait
{
    public static function setItemLocations(
        int $libraryId,
        int $libItemId,
        array $locations,
    ): void {
        $locations = array_values(array_filter($locations));

        if (count($locations) > 0) {

            LibItemLocation::updateOrCreate(
                [
                    'library_id' => $libraryId,
                    'lib_item_id' => $libItemId,
                ],
                [
                    'location1' => $locations[0],
                    'location2' => array_key_exists(1, $locations) ? $locations[1] : null,
                    'location3' => array_key_exists(2, $locations) ? $locations[2] : null,
                ]
            );
        }
    }
}
