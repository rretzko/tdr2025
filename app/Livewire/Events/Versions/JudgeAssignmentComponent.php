<?php

namespace App\Livewire\Events\Versions;

use App\Livewire\BasePage;
use App\Livewire\Forms\RoomForm;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionScoring;
use App\Models\UserConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class JudgeAssignmentComponent extends BasePage
{
    public RoomForm $form;
    public array $columnHeaders = [];
    public array $contentTypes = [];
    public array $members = [];
    public bool $showForm = true;
    public array $tolerances = [];
    public int $versionId = 0;
    public Version $version;
    public Collection $voiceParts;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = UserConfig::getValue('versionId');
        $this->version = Version::find($this->versionId);

        $this->columnHeaders = $this->getColumnHeaders();
        $this->contentTypes = $this->getContentTypes();
        $this->members = $this->getMembers();
        $this->tolerances = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $this->voiceParts = $this->version->event->voiceParts;
    }

    public function render()
    {
        return view('livewire.events.versions.judge-assignment-component',
            [
                'rows' => $this->getRows(),
            ]);
    }

    public function save(): void
    {
        $this->form->save();

        $this->dispatch('savedRoomForm', auth()->id());
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'name', 'sortBy' => null],
            ['label' => 'voice parts', 'sortBy' => null],
            ['label' => 'content', 'sortBy' => null],
            ['label' => 'judges', 'sortBy' => null],
            ['label' => 'tolerance', 'sortBy' => null],
        ];
    }

    private function getContentTypes(): array
    {
        return VersionScoring::query()
            ->where('version_id', $this->versionId)
            ->distinct('file_type')
            ->pluck('file_type', 'file_type')
            ->toArray();
    }

    private function getMembers(): array
    {
        $statuses = ['invited', 'obligated', 'participating'];

        return DB::table('version_participants')
            ->join('users', 'users.id', '=', 'version_participants.user_id')
            ->where('version_id', $this->versionId)
            ->whereIn('status', $statuses)
            ->select(DB::raw("CONCAT(users.last_name,', ',users.first_name) AS fullName"),
                'users.id', 'users.last_name', 'users.first_name')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->pluck('fullName', 'users.id')
            ->toArray();
    }

    private function getRows(): array
    {
        return [];
    }
}
