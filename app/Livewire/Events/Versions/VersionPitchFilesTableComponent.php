<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Livewire\Forms\VersionPitchFileForm;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionPitchFile;
use App\Models\Students\VoicePart;
use Illuminate\Database\Eloquent\Builder;

class VersionPitchFilesTableComponent extends BasePage
{
    public VersionPitchFileForm $form;
    public bool $showAddForm = true;
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = $this->dto['id'];
    }

    public function render()
    {
        return view('livewire..events.versions.version-pitch-files-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'fileTypes' => $this->getFileTypes(),
                'options1Thru50' => range(0, 50),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
                'voiceParts' => $this->getVoiceParts(),
            ]);
    }

    public function updatedShowAddForm(): void
    {
        $this->form->setNewPitchFile($this->versionId);
    }

    private function getColumnHeaders(): array
    {
        return [];
    }

    private function getFileTypes(): array
    {
        $types = explode(',', VersionConfigAdjudication::query()
            ->where('version_id', $this->versionId)
            ->value('upload_types'));

        $a = [];

        foreach ($types as $type) {

            $a[strtolower($type)] = ucwords($type);
        }

        return $a;
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

    /**
     * Return array of [voicePartId] = voicePartDescription for all voice parts
     * used by $this->versionId's ensembles
     * @return array
     */
    private function getVoiceParts(): array
    {
        $eventEnsembles = Version::with('event.eventEnsembles')
            ->find($this->versionId)
            ->event
            ->eventEnsembles;

        // Collect all voice part IDs
        $voicePartIds = $eventEnsembles->flatMap(function ($ensemble) {
            return explode(',', $ensemble->voice_part_ids);
        })->unique()->toArray();

        // Fetch and sort the voice parts
        return VoicePart::whereIn('id', $voicePartIds)
            ->orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();
    }
}
