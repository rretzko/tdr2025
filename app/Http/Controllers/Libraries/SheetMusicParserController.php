<?php

namespace App\Http\Controllers\Libraries;

use App\Services\Libraries\SheetMusicParser;

class SheetMusicParserController
{
    public function upload(Request $request)
    {
        $file = $request->file('sheet');
        $path = $file->store('sheets');

        /**
         * $metadata should look like:
         * {
         * "title": "A Lovely Way To Spend An Evening",
         * "composer": "JIMMY McHUGH",
         * "lyricist": "HAROLD ADAMSON",
         * "arranger": "MAC HUFF",
         * "choreographer": "JOHN JACOBSON"
         * }
        */
        $metadata = SheetMusicParser::fromFile(storage_path("app/$path"));

        //save to database?
        //$music = \App\Models\SheetMusic::create($metadata);

        return response()->json($metadata);
    }
}
