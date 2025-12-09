<?php

namespace App\Data\Pdfs;

use App\Models\Events\Versions\Version;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PdfRegistrationCardAuditReport
{
    public array $registrantData;
    public array $registrants;
    public function __construct(private readonly Version $version)
    {
        $this->registrantData = $version->versionRegistrants()->select('id', 'student_id', 'school_id', 'voice_part_id')->get()->toArray();

        $this->init();
    }

    private function init(): void
    {
        $candidateIds = array_column($this->registrantData, 'id');

        $this->registrants = [];

        foreach (array_chunk($candidateIds, 500) as $chunk) {
            $this->registrants = array_merge(
                $this->registrants,
                $this->query($chunk)
            );
        }
    }

    private function query(array $candidateIds): array
    {
        return DB::table('candidates')
            ->join('schools', 'candidates.school_id', '=', 'schools.id')
            ->join('students', 'candidates.student_id', '=', 'students.id', 'left outer')
            ->join('phone_numbers AS studentPhone', 'students.user_id', '=', 'studentPhone.user_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('teachers', 'candidates.teacher_id', '=', 'teachers.user_id')
            ->join('users AS teacherUser', 'teachers.user_id', '=', 'teacherUser.id')
            ->join('phone_numbers AS teacherPhone', 'teacherUser.id', '=', 'teacherPhone.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->join('version_timeslots', function($join) {
                $join->on('version_timeslots.school_id', '=', 'schools.id')
                    ->where('version_timeslots.version_id', '=', $this->version->id);
            })
            ->whereIn('candidates.id', $candidateIds)
            ->where('studentPhone.phone_type', 'mobile')
            ->where('teacherPhone.phone_type', 'mobile')
            ->select(
                'candidates.ref AS id',
                DB::raw("CONCAT(users.last_name, ', ', users.first_name, ' ', users.middle_name) AS alphaName"),
                'users.email AS email',
                'schools.name AS schoolName',
                'voice_parts.abbr AS voicePartAbbr',
                'version_timeslots.timeslot AS timeslotFull',
                DB::raw("DATE_FORMAT(version_timeslots.timeslot, '%l:%i %p') AS timeslot"),
                'studentPhone.phone_number AS studentMobile',
                'teacherUser.name AS teacherName',
                'teacherPhone.phone_number AS teacherMobile'
            )
            ->orderBy('timeslotFull')
            ->orderBy('schoolName')
            ->orderBy('alphaName')
            ->get()
            ->toArray();
    }
}
