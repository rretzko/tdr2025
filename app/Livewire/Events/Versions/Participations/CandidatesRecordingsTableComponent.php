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

//        $this->troubleShooting($coTeacherIds, $eligibleClassOfs, $schoolIds, $this->versionId);
        return DB::table('candidates')
            ->join('students', 'students.id', '=', 'candidates.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('voice_parts', 'voice_parts.id', '=', 'candidates.voice_part_id')
            ->leftJoin('recordings AS scales', 'candidates.id', '=', 'scales.candidate_id')
            ->leftJoin('recordings AS solo', 'candidates.id', '=', 'solo.candidate_id')
            ->leftJoin('recordings AS quintet', 'candidates.id', '=', 'quintet.candidate_id')
            ->where('candidates.version_id', $this->versionId)
            ->whereIn('candidates.teacher_id', $coTeacherIds)
            ->whereIn('candidates.school_id', $schoolIds)
            ->where(function ($query) {
                $query->where('scales.file_type', 'scales')
                    ->orWhereNull('scales.file_type');
            })
            ->where(function ($query) {
                $query->where('solo.file_type', 'solo')
                    ->orWhereNull('solo.file_type');
            })
            ->where(function ($query) {
                $query->where('quintet.file_type', 'quintet')
                    ->orWhereNull('quintet.file_type');
            })
            ->select('candidates.id AS candidateId', 'candidates.status',
                'users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix_name',
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
}
