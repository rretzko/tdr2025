<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Livewire\BasePage;
use App\Models\Events\Versions\VersionPitchFile;
use Illuminate\Database\Eloquent\Builder;

class TeacherPitchFilesTableComponent extends BasePage
{
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->versionId = $this->dto['id'];

        //default values
        $this->hasFilters = true;

        //filters
        $this->filters->pitchFileVoicePartsSelectedIds = $this->filters->previousFilterExists('pitchFileVoicePartsSelectedIds',
            $this->dto['header'])
            ? $this->filters->getPreviousFilterArray('pitchFileVoicePartsSelectedIds', $this->dto['header'])
            : $this->filters->pitchFileVoicePartsSelectedIds;

        $this->filters->pitchFileFileTypesSelectedIds = $this->filters->previousFilterExists('pitchFileFileTypesSelectedIds',
            $this->dto['header'])
            ? $this->filters->getPreviousFilterArray('pitchFileFileTypesSelectedIds', $this->dto['header'])
            : $this->filters->pitchFileFileTypesSelectedIds;

        //filterMethods
        $this->filterMethods[] = 'pitchFileVoiceParts';
        $this->filterMethods[] = 'pitchFileFileTypes';

//        $this->sortCol = $this->userSort ? $this->userSort->column : 'version_pitch_files.order_by';
//        $this->sortAsc = $this->userSort ? $this->userSort->asc : $this->sortAsc;
//        $this->sortColLabel = $this->userSort ? $this->userSort->label : 'orderBy';
    }

    public function render()
    {
        $this->saveSortParameters();

        $this->filters->setFilter('pitchFileVoicePartsSelectedIds', $this->dto['header']);
        $this->filters->setFilter('pitchFileFileTypesSelectedIds', $this->dto['header']);

        return view('livewire..events.versions.participations.teacher-pitch-files-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
//                'fileTypes' => $this->fileTypes,
//                'options1Thru50' => range(0, 50),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
//                'voiceParts' => $this->voiceParts,
            ]);
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'voice part', 'sortBy' => 'voicePart'],
            ['label' => 'file type', 'sortBy' => 'fileType'],
            ['label' => 'description', 'sortBy' => null],
            ['label' => 'pitch file', 'sortBy' => null],
        ];
    }

    private function getRows(): Builder
    {
//        $this->test();

        $secondarySortOrder = ($this->sortCol === 'version_pitch_files.order_by')
            ? ($this->sortAsc ? 'asc' : 'desc')
            : 'asc';

        return VersionPitchFile::query()
            ->leftJoin('voice_parts', 'voice_parts.id', '=', 'version_pitch_files.voice_part_id')
            ->where('version_id', $this->versionId)
            ->tap(function ($query) {
                $this->filters->filterPitchFileVoiceParts($query);
                $this->filters->filterPitchFileFileTypes($query);
            })
            ->select('version_pitch_files.id', 'version_pitch_files.version_id',
                'version_pitch_files.file_type', 'version_pitch_files.voice_part_id',
                'version_pitch_files.url', 'version_pitch_files.description',
                'version_pitch_files.order_by',
                'voice_parts.descr as voicePartDescr', 'voice_parts.order_by')
            ->orderBy('version_pitch_files.order_by', 'asc');
    }
}
