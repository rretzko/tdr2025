<?php

namespace App\Models\Events\Versions;

use Illuminate\Support\Facades\DB;

class CoregistrationManager
{
    public static function getCoregistrationManager(int $versionId, int $versionParticipantId)
    {
        return DB::table('version_participants AS vp')
            ->leftJoin('version_county_assignments AS vca', 'vp.id', '=', 'vca.version_participant_id')
            ->leftJoin('counties AS c', 'vca.county_id', '=', 'c.id')
            ->leftJoin('users AS u', 'vp.user_id', '=', 'u.id')
            ->where('vp.version_id', $versionId)
            ->where('vp.id', $versionParticipantId)
            ->select('vp.id',
                'u.name',
                DB::raw("GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ',') AS countyNames"),
                DB::raw("GROUP_CONCAT(c.id ORDER BY c.id SEPARATOR ',') AS countyIds")
            )
            ->groupBy('vp.id', 'u.name')
            ->orderBy('u.last_name')
            ->orderBy('u.first_name')
            ->first();
    }
}
