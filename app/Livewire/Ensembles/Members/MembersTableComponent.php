<?php

namespace App\Livewire\Ensembles\Members;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MembersTableComponent extends BasePageMember
{
    public function mount(): void
    {
        parent::mount();

        $this->hasFilters = true;
        $this->hasSearch = true;

        $this->setFilterMethods();

    }

    public function render()
    {
        return view('livewire..ensembles.members.members-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getMembers(),
                'tabs' => self::ENSEMBLETABS,
            ]);
    }

    public function updatedSelectedTab()
    {
        $uri = ($this->selectedTab === 'ensembles')
            ? '/ensembles'
            : '/ensembles/'.$this->selectedTab;

        $this->redirect($uri);
    }

    private function getColumnHeaders(): array
    {
        return ['###', 'name/school', 'ensemble', 'voice part', 'grade', 'year', 'status', 'office'];
    }

    private function getMembers(): array
    {
        $schoolIds = auth()->user()->teacher->schools->pluck('id')->toArray();

        //$this->logSql($schoolIds);

        return DB::table('ensemble_members')
            ->join('students', 'ensemble_members.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('school_student', 'ensemble_members.student_id', '=', 'school_student.student_id')
            ->join('ensembles', 'ensemble_members.ensemble_id', '=', 'ensembles.id')
            ->join('voice_parts', 'ensemble_members.voice_part_id', '=', 'voice_parts.id')
            ->join('schools', 'school_student.school_id', '=', 'schools.id')
            ->whereIn('ensemble_members.school_id', $schoolIds)
            ->tap(function ($query) {
                $this->filters->filterStudentsBySchools($query);
                $this->filters->filterMembersByEnsemble($query);
                $this->filters->filterMembersBySchoolYear($query);
            })
            ->select('users.name', 'schools.name AS schoolName', 'ensembles.name AS ensembleName',
                'voice_parts.descr AS voicePartDescr', 'students.class_of',
                'ensemble_members.school_year', 'ensemble_members.status', 'ensemble_members.office',
                'ensemble_members.id')
            ->get()
            ->toArray();
    }

    /**
     * for troubleshooting
     */
    private function logSql(array $schoolIds): void
    {
        $sql = DB::table('ensemble_members')
            ->join('students', 'ensemble_members.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('school_student', 'ensemble_members.student_id', '=', 'school_student.student_id')
            ->join('ensembles', 'ensemble_members.ensemble_id', '=', 'ensembles.id')
            ->join('voice_parts', 'ensemble_members.voice_part_id', '=', 'voice_parts.id')
            ->join('schools', 'school_student.school_id', '=', 'schools.id')
            ->whereIn('ensemble_members.school_id', $schoolIds)
            ->tap(function ($query) {
                $this->filters->filterStudentsBySchools($query);
                $this->filters->filterMembersByEnsemble($query);
                $this->filters->filterMembersBySchoolYear($query);
            })
            ->select('users.name', 'schools.name AS schoolName', 'ensembles.name AS ensembleName',
                'voice_parts.descr AS voicePartDescr', 'students.class_of',
                'ensemble_members.school_year', 'ensemble_members.status', 'ensemble_members.office',
                'ensemble_members.id')
            ->toRawSql();

        Log::info($sql);
    }

    private function setFilterMethods(): void
    {
        if (count($this->schools) > 1) {

            $this->filterMethods[] = 'schools';
        }

        if (count($this->filters->ensembles()) > 1) {

            $this->filterMethods[] = 'ensembles';
        }

        if (count($this->filters->ensembleYears()) > 1) {

            $this->filterMethods[] = 'ensembleYears';
        }
    }

}
