<?php

namespace App\Livewire\Libraries\Items;

use App\Imports\LibraryItemsImport;
use App\Models\Libraries\Items\Components\Artist;
use App\Models\Libraries\Items\Components\Voicing;
use App\Models\Libraries\Library;
use App\Models\Libraries\LibStack;
use App\Models\Libraries\Items\LibItem;
use App\Services\ArtistSearchService;
use App\Services\Libraries\CreateLibItemService;
use App\Services\Libraries\LibraryStackSearchService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;

class ItemComponent extends BaseLibraryItemPage
{
    use WithFileUploads;
    public array $artistTypes = [];
    public bool $displayFileImportForm = false;
    public string $errorMessage = '';
    public string $fileUploadMessage = '';

    public int $libraryId = 0;
    public LibItem $libItem;
    public string $libraryName = '';
    public string $searchResults = 'Search Results';
    public array $searchVoicings = [];
    public array $searchResultsArtists = [
        'arranger' => '',
        'choreographer' => '',
        'composer' => '',
        'music' => '',
        'wam' => '',
        'words' => '',
    ];
    public string $tagCsv = '';

    public $uploadedFileContainer; //used as container for uploaded file
    public int $uploadedMaxFileSize = 400000; //4MB
    public bool $uploadedMaxFileSizeExceeded = false;
    public string $uploadedMaxFileSizeExceededMessage = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
    public string $uploadTemplateUrl = '';

    public function mount(): void
    {
        parent::mount();

        $this->libraryId = auth()->user()->isLibrarian() ? $this->dto['libraryId'] : $this->dto['id'];
        $this->libraryName = Library::find($this->libraryId)->name;
        $this->form->libraryId = $this->libraryId;

        $this->artistTypes = parent::ARTISTTYPES;

        if (isset($this->dto['libItem'])) {
            $this->libItem = $this->dto['libItem'];
            $this->form->setLibItem($this->libItem);
        } else {
            $this->form->resetVars();
        }

        $this->uploadTemplateUrl = \Storage::disk('s3')->url('templates/libraryItemUploadTemplate.csv');

    }

    public function render()
    {
        return view('livewire..libraries.items.item-component',
            [
                'bladeForm' => 'components.forms.libraries.itemTypes.' . $this->form->itemTypeBlade() . 'Form',
            ]);
    }

    public function clickUploadCsv(): void
    {
        $this->reset('fileUploadMessage', 'uploadedMaxFileSizeExceeded');

        //check size
        $fileSize = $this->uploadedFileContainer->getSize();
        Log::info('fileSize: '.$fileSize);
        //early exit if fileSize exceeds maxFileSIze
        if ($fileSize > $this->uploadedMaxFileSize) {
            $this->uploadedMaxFileSizeExceeded = true;
        } else {
            Log::info('fileSize is good.');
            //store the file on a s3 disk
            $s3Path = 'libraries/items';
            $fileName = 'test'.rand(1000, 3000).'.csv';
            Log::info('fileName: '.$fileName);
            $storedFileName = $this->uploadedFileContainer->storePubliclyAs($s3Path, $fileName, 's3');
            Log::info('storedFileName: '.$storedFileName);
            if ($storedFileName) {
                try {
                    Excel::import(
                        new LibraryItemsImport($this->libraryId),
                        $storedFileName,
                        's3',
                        \Maatwebsite\Excel\Excel::CSV);
                    Log::info('Import completed successfully, continuing...');
                } catch (\Exception $e) {
                    Log::error('Excel import failed: '.$e->getMessage());
                }
                $this->reset('uploadedFileContainer');
                $this->displayFileImportForm = false;
                $this->redirect("/library/$this->libraryId/items");
            } else {
                Log::info('No file was uploaded.');
            }
        }
    }

    #[NoReturn] public function clickImportItems(): void
    {
        $this->displayFileImportForm = true;
    }

    #[NoReturn] public function clickVoicing(int $voicingId): void
    {
        $voicing = Voicing::find($voicingId);
        $this->form->voicingId = $voicing->id;
        $this->form->voicingDescr = $voicing->descr;
        $this->reset('searchVoicings');
    }

    #[NoReturn] public function findItem(int $libItemId): void
    {
        $this->form->setLibItem(LibItem::find($libItemId));
    }

    public function save()
    {
        //determine if save = updating or adding
        $updating = (bool)$this->form->sysId;

        //parse $tagsCsv & persist in $this->form->tags array
        $this->parseTagsCsv();

        $this->reset('errorMessage', 'successMessage');

        $saved = $this->form->save($this->libraryId);

        //format title for use in success/error messages
        $fTitle = Str::title($this->form->title);

        if ($saved) {
            $message = '"' . $fTitle . ($updating ? '" updated.' : '" saved.');
            session()->flash('successMessage', $message);
            $this->reset('tagCsv');
        } else {
            $this->errorMessage = 'Unable to save "' . $fTitle . '" at this time.';
        }

        return $this->redirect("/library/$this->libraryId/items");
    }

    public function saveAndStay(): void
    {
        //parse $tagsCsv & persist in $this->form->tags array
        $this->parseTagsCsv();

        $this->reset('errorMessage', 'successMessage');

        $service = new CreateLibItemService(
            $this->form,
            $this->form->tags,
            $this->form->locations,
            $this->libraryId
        );

        if ($service->saved) {
            $this->addItemToLibrary($service->libItemId);
            $libItemTitle = LibItem::find($service->libItemId)->title;
            $this->successMessage = 'Item "' . $libItemTitle . '" Saved.';
            $this->form->resetVars();
        } else {
            $this->errorMessage = 'Unable to save item.';
        }

    }

    public function setArtist(string $artistType, int $artistId): void
    {
        $artist = Artist::find($artistId);
        $this->form->artists[$artistType] = $artist->artist_name;
        $this->form->artistIds[$artistType] = $artistId;
        $this->searchResultsArtists[$artistType] = [];
    }

    public function updatedFormArtistsArranger($value): void
    {
        $this->updatedFormArtistsType($value, 'arranger');
    }

    public function updatedFormArtistsChoreographer($value): void
    {
        $this->updatedFormArtistsType($value, 'choreographer');
    }

    public function updatedFormArtistsComposer($value): void
    {
        $this->updatedFormArtistsType($value, 'arranger');
    }

    public function updatedFormArtistsMusic($value): void
    {
        $this->updatedFormArtistsType($value, 'music');
    }

    public function updatedFormArtistsWam($value): void
    {
        $this->updatedFormArtistsType($value, 'wam');
    }

    public function updatedFormArtistsWords($value): void
    {
        $this->updatedFormArtistsType($value, 'words');
    }

    public function updatedFormTitle(): void
    {
        $this->search();
    }

    public function updatedFormVoicingDescr(): void
    {
        //clear current stack
        $this->reset('searchVoicings');
        //redo search
        $this->searchVoicing();
    }

    private function addItemToLibrary(int $libItemId): void
    {
        LibStack::updateOrCreate(
            [
                'library_id' => $this->libraryId,
                'lib_item_id' => $libItemId
            ],
            []
        );
    }

    private function parseTagsCsv(): void
    {
        if (strlen($this->tagCsv)) {
            $tags = explode(",", $this->tagCsv);
            $this->form->tags = array_filter(array_map('trim', $tags), fn($tag) => $tag !== '');
        } else {
            $this->form->tags = [];
        }
    }

    private function search(): void
    {
        $search = new LibraryStackSearchService($this->form);

        $this->searchResults = $search->getResults();
    }

    private function searchVoicing(): void
    {
        $searchValue = '%'.strtolower($this->form->voicingDescr).'%';

        $found = Voicing::where('descr', 'LIKE', $searchValue)->get();

        foreach ($found as $voicing) {
            $this->searchVoicings[] = [
                'id' => $voicing->id,
                'descr' => $voicing->descr,
            ];
        }

    }

    private function updatedFormArtistsType(string $value, string $type): void
    {
        if (strlen($value)) {

            $this->searchResultsArtists[$type] = ArtistSearchService::getResults($value, $type);

        } else {//user has removed the current value

            $this->searchResultsArtists[$type] = null;
            $this->form->artists[$type] = '';
            $this->form->artistIds[$type] = 0;
            $this->libItem->update([$type => 0]);
        }
    }

}
