<?php

namespace App\Services;

use App\Models\Events\Versions\VersionConfigAdjudication;

class FileTypesArrayService
{
    public static function getArray(int $versionId): array
    {
        $types = explode(',', VersionConfigAdjudication::query()
            ->where('version_id', $versionId)
            ->value('upload_types'));

        $a = [];

        foreach ($types as $type) {

            $a[strtolower($type)] = ucwords($type);
        }

        return $a;
    }


}
