<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class County extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'geostate_id',
    ];

    public function geostate(): BelongsTo
    {
        return $this->belongsTo(Geostate::class);
    }

    public function participantCount(int $versionId, string $participantType): int
    {
        return DB::table('version_participants')
            ->join('teachers', 'version_participants.user_id', '=', 'teachers.user_id')
            ->join('school_teacher', 'teachers.id', '=', 'school_teacher.teacher_id')
            ->join('schools', 'school_teacher.school_id', '=', 'schools.id')
            ->where('version_participants.version_id', $versionId)
            ->where('version_participants.status', $participantType)
            ->where('school_teacher.active', 1)
            ->where('schools.county_id', $this->id)
            ->count('version_participants.id');
    }

    public function registrationManagerName(int $versionId): string
    {
//        dd(DB::table('version_county_assignments')
//            ->join('version_participants', 'version_county_assignments.version_participant_id', '=', 'version_participants.id')
//            ->join('users', 'users.id', '=', 'version_participants.user_id')
//            ->where('version_county_assignments.version_id', $versionId)
//            ->where('version_county_assignments.county_id', $this->id)
//            ->select(DB::raw("CONCAT(users.last_name, ', ' , users.first_name , ' ' , users.middle_name) as nameAlpha"))
//            ->toRawSql());

        return DB::table('version_county_assignments')
            ->join('version_participants', 'version_county_assignments.version_participant_id', '=', 'version_participants.id')
            ->join('users', 'users.id', '=', 'version_participants.user_id')
            ->where('version_county_assignments.version_id', $versionId)
            ->where('version_county_assignments.county_id', $this->id)
            ->select(DB::raw("CONCAT(users.last_name, ', ' , users.first_name , ' ' , users.middle_name) as nameAlpha"))
            ->value('nameAlpha') ?? '';
    }

    public function studentCount(int $versionId): int
    {
        return DB::table('candidates')
            ->join('schools', 'candidates.school_id', '=', 'schools.id')
            ->join('counties', 'schools.county_id', '=', 'counties.id')
            ->where('candidates.version_id', $versionId)
            ->where('candidates.status', 'registered')
            ->where('schools.county_id', $this->id)
            ->count('candidates.id');
    }
}
