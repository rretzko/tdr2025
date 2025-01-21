<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Livewire\BasePage;
use App\Models\Events\Versions\Version;
use App\Models\Schools\Teacher;
use App\Models\Schools\Teachers\Coteacher;
use App\Models\UserConfig;
use App\Services\CoTeachersService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CandidatesRecordingsTableComponent extends BasePage
{
    public int $schoolId = 0;
    public Version $version;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->schoolId = UserConfig::getValue('schoolId');
        $this->version = Version::find($this->dto['versionId']);
        $this->versionId = $this->version->id;

        $this->sortCol = 'voice_parts.order_by';
    }

    public function render()
    {
        return view('livewire..events.versions.participations.candidates-recordings-table-component',
            [
                'rows' => $this->getRows(),
            ]);
    }

    private function getRows(): Collection
    {
        $coTeacherIds = CoTeachersService::getCoTeachersIds();
        $schoolIds = $this->getSchoolIds();
        $eligibleClassOfs = $this->getEligibleClassOfs();

//        $this->troubleShooting($coTeacherIds, $eligibleClassOfs, $schoolIds);

        return DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('teachers', 'teachers.id', '=', 'candidates.teacher_id')
            ->join('users AS tusers', 'tusers.id', '=', 'teachers.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->join('student_teacher', 'student_teacher.student_id', '=', 'students.id')
            ->join('school_student', 'school_student.student_id', '=', 'students.id')
            ->leftJoin('recordings AS scales', 'candidates.id', '=', 'scales.candidate_id')
            ->leftJoin('recordings AS solo', 'candidates.id', '=', 'solo.candidate_id')
            ->leftJoin('recordings AS quintet', 'candidates.id', '=', 'quintet.candidate_id')
            ->where('candidates.version_id', $this->versionId)
            ->whereIn('candidates.teacher_id', $coTeacherIds)
            ->whereIn('candidates.school_id', $schoolIds)
            ->whereIn('students.class_of', $eligibleClassOfs)
            ->whereIn('student_teacher.teacher_id', $coTeacherIds)
            ->whereIn('school_student.school_id', $schoolIds)
            ->where('school_student.active', 1)
            ->where('scales.file_type', 'scales')
            ->where('solo.file_type', 'solo')
            ->where('quintet.file_type', 'quintet')
            ->tap(function ($query) {
                $this->filters->filterCandidatesByClassOfs($query);
                $this->filters->filterCandidatesByStatuses($query, $this->search);
            })
            ->select('candidates.id AS candidateId', 'candidates.ref', 'candidates.status',
                'candidates.program_name', 'candidates.emergency_contact_id',
                'users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix_name',
                'students.class_of',
                'voice_parts.abbr AS voicePart', 'voice_parts.order_by', 'voice_parts.descr AS voicePartDescr',
                'scales.url AS scalesUrl', 'scales.file_type AS scalesFileType',
                'solo.url AS soloUrl', 'solo.file_type AS soloFileType',
                'quintet.url AS quintetUrl', 'quintet.file_type AS quintetFileType',
            )
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('users.last_name', 'asc') //secondary sort ALWAYS applied
            ->orderBy('users.first_name', 'asc') //tertiary sort ALWAYS applied
            ->get();
    }

    private function getSchoolIds(): array
    {
        $schoolIds = [$this->schoolId];

        $myTeacherId = Teacher::where('user_id', auth()->id())->first()->id;
        $coTeacherSchoolIds = Coteacher::query()
            ->where('coteacher_id', $myTeacherId)
            ->distinct()
            ->pluck('school_id')
            ->toArray();

        return array_merge($schoolIds, $coTeacherSchoolIds);
    }

    private function getEligibleClassOfs(): array
    {
        $event = $this->version->event;
        return $event->classOfs;
    }
}
