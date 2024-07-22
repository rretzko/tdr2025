<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Models\Events\Versions\VersionPitchFile;
use Illuminate\Database\Eloquent\Builder;

class VersionPitchFilesTableComponent extends BasePage
{
    public function render()
    {
        return view('livewire..events.versions.version-pitch-files-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
            ]);
    }

    private function getColumnHeaders(): array
    {
        return [];
    }

    private function getRows(): Builder
    {
        return VersionPitchFile::query()
            ->orderBy('order_by');
//        ->join('version_participants', 'version_participants.id', '=', 'version_roles.version_participant_id')
//        ->join('users', 'users.id', '=', 'version_participants.user_id')
//        ->join('teachers', 'teachers.user_id', '=', 'users.id')
//        ->join('school_teacher', 'school_teacher.teacher_id', '=', 'teachers.id')
//        ->join('schools', 'schools.id', '=', 'school_teacher.school_id')
//        ->where('version_roles.version_id', $this->versionId)
//        ->where('users.name', 'LIKE', '%'.$this->search.'%')
//        ->orWhere('schools.name', 'LIKE', '%'.$this->search.'%')
//        ->select('version_roles.id', 'version_roles.role',
//            'version_participants.id as versionParticipantsId', 'version_participants.status',
//            'users.id as userId', 'users.last_name', 'users.first_name', 'users.middle_name',
//            'schools.name as schoolName')
//        ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
//        ->orderBy('users.last_name')
//        ->orderBy('users.first_name');
    }
}
