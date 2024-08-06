<?php

namespace App\Livewire\Events\Versions\Participations;

use App\Livewire\BasePage;
use App\Models\Events\Versions\VersionPitchFile;

class PitchFilesComponent extends BasePage
{
    public array $columnHeaders = [];
    public int $versionId = 0;

    public function mount(): void
    {
        parent::mount();

        $this->columnHeaders = $this->getColumnHeaders();
        $this->hasFilters = true;
        $this->sortCol = 'version_pitch_files.order_by';
        $this->versionId = $this->dto['id']; //4

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
        $this->filterMethods = ['pitchFileVoiceParts', 'pitchFileFileTypes'];

    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'voice part', 'sortBy' => 'voicePart'],
            ['label' => 'type', 'sortBy' => 'fileType'],
            ['label' => 'description', 'sortBy' => null],
            ['label' => 'play', 'sortBy' => null],
        ];
    }

    public function render()
    {
        $this->saveSortParameters();

        $this->filters->setFilter('pitchFileVoicePartsSelectedIds', $this->dto['header']);
        $this->filters->setFilter('pitchFileFileTypesSelectedIds', $this->dto['header']);

        return view('livewire..events.versions.participations.pitch-files-component',
            [
                'columnHeaders' => $this->columnHeaders,
                'rows' => $this->getPitchFiles(),
            ]
        );
    }

    private function getPitchFiles(): array
    {
        return VersionPitchFile::query()
            ->join('voice_parts', 'voice_parts.id', '=', 'version_pitch_files.voice_part_id')
            ->where('version_id', $this->versionId)
            ->tap(function ($query) {
                $this->filters->filterPitchFileVoiceParts($query);
                $this->filters->filterPitchFileFileTypes($query);
            })
            ->select('version_pitch_files.id', 'file_type', 'description', 'url', 'voice_part_id',
                'voice_parts.descr AS voicePartDescr')
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            ->orderBy('version_pitch_files.order_by')
            ->get()
            ->toArray();
    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'voicePart' => 'voice_parts.order_by',
            'fileType' => 'version_pitch_files.file_type',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];

    }
}
