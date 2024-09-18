<?php

namespace App\Livewire\Events\Versions;

use App\Exports\PitchFilesExport;
use App\Livewire\BasePage;
use App\Livewire\Forms\VersionPitchFileForm;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigAdjudication;
use App\Models\Events\Versions\VersionPitchFile;
use App\Models\Students\VoicePart;
use App\Services\FileTypesArrayService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class VersionPitchFilesTableComponent extends BasePage
{
    use WithFileUploads;

    public array $fileTypes = [];
    public VersionPitchFileForm $form;
    public array $previousVersionPitchFiles = [];
    public bool $showAddForm = false;
    public bool $showEditForm = false;
    public bool $showEventEnsemblesMissingAdvisory = false;
    public bool $showPitchFiles = false;
    public int $versionId = 0;
    public int $eventId = 0;
    public array $voiceParts = [];

    #[Validate('mimes:mp3,mpeg,ogg,wav,pdf|max:6000')]
    public $pitchFile;


    public function mount(): void
    {
        parent::mount();

        $version = Version::find($this->dto['id']);
        $this->versionId = $version->id;
        $this->eventId = $version->event_id;
        $this->fileTypes = FileTypesArrayService::getArray($this->versionId);
        //append pdf FileType
        $this->fileTypes = array_merge($this->fileTypes, ['pdf' => 'PDF']);
        $this->voiceParts = $this->getVoiceParts();

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

        $this->sortCol = $this->userSort ? $this->userSort->column : 'version_pitch_files.order_by';
        $this->sortAsc = $this->userSort ? $this->userSort->asc : $this->sortAsc;
        $this->sortColLabel = $this->userSort ? $this->userSort->label : 'orderBy';

        //form
        if ($this->voiceParts) {
            $this->form->setDefaults($this->versionId, $this->voiceParts);
        }

        //pitchFileAvailability
        $this->showPitchFiles = $version->showPitchFiles();

        //previous files
        $this->previousVersionPitchFiles = $this->getPreviousVersionPitchFiles();

    }

    public function render()
    {
        $this->saveSortParameters();

        $this->filters->setFilter('pitchFileVoicePartsSelectedIds', $this->dto['header']);
        $this->filters->setFilter('pitchFileFileTypesSelectedIds', $this->dto['header']);

//Log::info(serialize($this->filters->pitchFileVoicePartsSelectedIds));
//Log::info(serialize($this->filters->pitchFileFileTypesSelectedIds));

        return view('livewire..events.versions.version-pitch-files-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'fileTypes' => $this->fileTypes,
                'options1Thru50' => range(0, 50),
                'rows' => $this->getRows()->paginate($this->recordsPerPage),
                'voiceParts' => $this->voiceParts,
            ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new PitchFilesExport, 'pitchFiles.csv');
    }

    public function addPitchFile(): void
    {
        //preemptory strike to avoid potential
        //run condition in setting the file from
        //$this->updatePitchFile
        if (!$this->form->url) {
            sleep(5);
            Log::info(__METHOD__.': '.__LINE__);
            Log::info('race condition potentially avoided with pitch file upload.');
        }

        $this->form->add();

        $this->form->resetAll();

        $this->reset('showAddForm');

        $this->showSuccessIndicator = true;

        $this->successMessage = 'Pitch file added.';
    }

    public function pitchFileUpdate(): void
    {
        $this->form->pitchFileUpdate();

        $this->form->resetAll();

        $this->reset('showEditForm');

        $this->showSuccessIndicator = true;

        $this->successMessage = 'Pitch file updated.';
    }

    public function remove(int $versionPitchFileId): void
    {
        VersionPitchFile::find($versionPitchFileId)->delete();

        $this->showSuccessIndicator = true;

        $this->successMessage = 'Pitch file removed.';
    }

    public function sortBy(string $key): void
    {
        $this->sortColLabel = $key;

        $properties = [
            'fileType' => 'version_pitch_files.file_type',
            'orderBy' => 'version_pitch_files.order_by',
            'voicePart' => 'voice_parts.order_by',
        ];

        $requestedSort = $properties[$key];

        //toggle $this->sortAsc if user clicks on the same column header twice
        if ($requestedSort === $this->sortCol) {

            $this->sortAsc = (!$this->sortAsc);
        }

        $this->sortCol = $properties[$key];
    }

    public function updatedShowAddForm(): void
    {
        //close edit-form if opened
        $this->reset('showEditForm');

        $this->form->setNewPitchFile($this->versionId, $this->fileTypes, $this->voiceParts);
    }

    public function updatedShowEditForm(int $versionPitchFileId): void
    {
        //close add-form if opened
        $this->reset('showAddForm');

        $this->form->setPitchFile($this->versionId, $versionPitchFileId);
    }

    public function updatedPitchFile(): void
    {
        $fileName = $this->makeFileName();

        $stored = $this->pitchFile->storePubliclyAs('pitchFiles', $fileName, 's3');

        //store new url identifier
        $this->form->url = 'pitchFiles/'.$fileName;

    }

    private function clonePitchFiles(): void
    {
        $event = Version::find($this->versionId)->event;
        $mostRecentPreviousVersion = $event->versions()->whereNot('id', $this->versionId)->first();
        $pitchFiles = $mostRecentPreviousVersion->versionPitchFiles;

        foreach ($pitchFiles as $pitchFile) {

            VersionPitchFile::create(
                [
                    'version_id' => $this->versionId,
                    'file_type' => $pitchFile->file_type,
                    'voice_part_id' => $pitchFile->voice_part_id,
                    'url' => $pitchFile->url,
                    'description' => $pitchFile->description,
                    'order_by' => $pitchFile->order_by,
                ]
            );
        }
    }

    private function getColumnHeaders(): array
    {
        return [
            ['label' => '###', 'sortBy' => null],
            ['label' => 'voice part', 'sortBy' => 'voicePart'],
            ['label' => 'file type', 'sortBy' => 'fileType'],
            ['label' => 'description', 'sortBy' => null],
            ['label' => 'pitch file', 'sortBy' => null],
            ['label' => 'order', 'sortBy' => 'orderBy'],
        ];
    }

    private function getPreviousVersionPitchFiles(): array
    {
        $versionIds = Version::find($this->versionId)
            ->event
            ->versions()
            ->whereNot('id', $this->versionId)
            ->pluck('id');

        return VersionPitchFile::whereNot('version_id', $this->versionId)
            ->whereIn('version_id', $versionIds)
            ->distinct('description')
            ->select('version_pitch_files.id', 'version_pitch_files.description', 'version_pitch_files.order_by')
            ->orderBy('order_by')
            ->pluck('description', 'id')
            ->toArray();
    }

    private function getRows(): Builder
    {
        //if no pitch files are found, clone previous pitch files if possible
        if (!$this->pitchFilesExist()) {
            $this->clonePitchFiles();
        }

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
                'voice_parts.descr', 'voice_parts.order_by')
            ->orderBy($this->sortCol, ($this->sortAsc ? 'asc' : 'desc'))
            //order_by is always a secondary sort and mimics if order_by is also the primary sort
            ->orderBy('version_pitch_files.order_by', $secondarySortOrder);
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

        //early exit
        if (!$eventEnsembles->count()) {
            $this->showEventEnsemblesMissingAdvisory = true;
            return [];
        }

        // Collect all voice part IDs
        $voicePartIds = $eventEnsembles->flatMap(function ($ensemble) {
            return explode(',', $ensemble->voice_part_ids);
        })->unique()->toArray();

        if (!$voicePartIds) {
            return [];
        }

        //add ALL to voice parts
        $voicePartIds[] = VoicePart::where('descr', 'ALL')->first()->id;

        // Fetch and sort the voice parts
        return VoicePart::whereIn('id', $voicePartIds)
            ->orderBy('order_by')
            ->pluck('descr', 'id')
            ->toArray();
    }

    private function makeFileName(): string
    {
        //ex: 9_75_scales_sopranoIHighScales.mp3
        $fileName = $this->eventId;
        $fileName .= '_';
        $fileName .= $this->versionId;
        $fileName .= '_';
        $fileName .= Str::camel($this->form->fileType);
        $fileName .= '_';
        $fileName .= Str::camel($this->form->description);
        $fileName .= '.';
        $fileName .= pathInfo($this->pitchFile->getClientOriginalName(), PATHINFO_EXTENSION);

        return $fileName;
    }

    private function pitchFilesExist(): bool
    {
        return VersionPitchFile::where('version_id', $this->versionId)->exists();
    }
}
