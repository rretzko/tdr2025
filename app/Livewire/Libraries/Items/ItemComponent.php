<?php

namespace App\Livewire\Libraries\Items;

use App\Imports\LibraryItemsImport;
use App\Jobs\ProcessLibraryItemsImport;
use App\Mail\LibraryCsvReceivedMail;
use App\Models\Libraries\Items\Components\Artist;
use App\Models\Libraries\Items\Components\LibItemDoc;
use App\Models\Libraries\Items\Components\Voicing;
use App\Models\Libraries\LibLibrarian;
use App\Models\Libraries\Library;
use App\Models\Libraries\LibStack;
use App\Models\Libraries\Items\LibItem;
use App\Models\User;
use App\Services\ArtistSearchService;
use App\Services\Libraries\CreateLibItemService;
use App\Services\Libraries\LibraryStackSearchService;
use App\Services\Libraries\SheetMusicParser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;

class ItemComponent extends BaseLibraryItemPage
{
    use WithFileUploads;

    public array $artistTypes = [];
    public array $bookTypes = ['music', 'text'];
    public bool $displayFileImportForm = false;
    public bool $displayViaImageOrPdf = false;
    public string $errorMessage = '';
    public string $fileSize = '0';
    public string $fFileSize = '0'; //formatted file size
    public string $fileUploadMessage = '';

    public int $libraryId = 0;
    public LibItem $libItem;
    public string $libraryName = '';

    public bool $searching = false;
    public string $searchResults = 'Search Results';
    public array $searchVoicings = [];
    public array $searchResultsArtists = [
        'arranger' => '',
        'author' => '',
        'choreographer' => '',
        'composer' => '',
        'music' => '',
        'wam' => '',
        'words' => '',
    ];
    public string $tagCsv = '';
    public $uploadedFileContainer; //used as container for uploaded file
    public string $uploadDescr = '';
    public int $uploadedMaxFileSize = 4000000; //4MB
    public bool $uploadedMaxFileSizeExceeded = false;
    public string $uploadedMaxFileSizeExceededMessage = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
    public string $uploadTemplateUrl = '';

    public function mount(): void
    {
        parent::mount();

        $iniFileSize = ini_get('upload_max_filesize');
        $iniPostMaxSize = ini_get('post_max_size');

        $this->libraryId = auth()->user()->isLibrarian() ? $this->dto['libraryId'] : $this->dto['id'];
        $this->libraryName = Library::find($this->libraryId)->name;
        $this->form->libraryId = $this->libraryId;

        $this->artistTypes = parent::ARTISTTYPES;

        if (isset($this->dto['libItem'])) {
            $this->libItem = $this->dto['libItem'];
            $this->form->setLibItem($this->libItem);
        } else {
            $this->form->resetVars($this->libraryId);
        }

        $this->form->teacherEmail = $this->setTeacherEmail();

        $this->uploadTemplateUrl = \Storage::disk('s3')->url('templates/libraryItemUploadTemplate.csv');
    }

    public function render()
    {
        return view('livewire..libraries.items.item-component',
            [
                'bladeForm' => 'components.forms.libraries.itemTypes.' . $this->form->itemTypeBlade() . 'Form',
            ]);
    }

    public function clickAddViaImageOrPdf(): void
    {
        $this->reset('displayFileImportForm');
        $this->displayViaImageOrPdf = true;
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
            $libraryName = Library::find($this->libraryId)->name;
            $slug = Str::slug($libraryName, '-');
            Log::info('fileSize is good @ '.$fileSize.'.');
            //store the file on a s3 disk
            $s3Path = 'libraries/items';
            $fileName = $slug.rand(1000, 3000).'.csv';
            Log::info('fileName: '.$fileName);
            $storedFileName = $this->uploadedFileContainer->storePubliclyAs($s3Path, $fileName, 's3');
            Log::info('storedFileName: '.$storedFileName);
            if ($storedFileName) {
                Log::info('storedFileName: '.$storedFileName.': now processing jobs...');
                Mail::to(User::find(368))->send(new LibraryCsvReceivedMail($storedFileName));

//                ProcessLibraryItemsImport::dispatch($this->libraryId, $storedFileName, auth()->id());
                Log::info('Import completed successfully, continuing...');
            } else { //catch (\Exception $e) {
                Log::error('stored file name is missing in '.__METHOD__.' @ line 99.');
            }
            $this->reset('uploadedFileContainer');
            $this->displayFileImportForm = false;
            $this->redirect("/library/$this->libraryId/items");
//            } else {
//                Log::info('No file was uploaded.');
//            }
        }
    }

    public function clickUploadDoc(): void
    {
        $this->reset('fileUploadMessage', 'uploadedMaxFileSizeExceeded');

        $this->validate([
            'uploadDescr' => 'required',
        ]);

        //check size
        $fileSize = $this->uploadedFileContainer->getSize();
        Log::info('fileSize: '.$fileSize);
        //early exit if fileSize exceeds maxFileSIze
        if ($fileSize > $this->uploadedMaxFileSize) {
            $this->uploadedMaxFileSizeExceeded = true;
        } else {
            Log::info('fileSize is good @ '.$fileSize.'.');

            //store the file on a s3 disk
            $s3Path = 'libraries/items/docs';
            $fileName = $this->makeLibItemDocFileName($s3Path);

            Log::info('fileName: '.$fileName);
            $storedFileName = $this->uploadedFileContainer->storePubliclyAs($s3Path, $fileName, 's3');
            Log::info('storedFileName: '.$storedFileName);
            if ($storedFileName) {
                $userId = $this->form->getTeacherUserId();
                LibItemDoc::updateOrCreate(
                    [
                        'library_id' => $this->libraryId,
                        'lib_item_id' => $this->form->sysId,
                        'user_id' => $userId,
                        'url' => $storedFileName,
                        'shareable' => $this->form->shareable ? 1 : 0,
                    ],
                    [
                        'label' => $this->uploadDescr,
                    ]
                );

            } else { //catch (\Exception $e) {
                Log::error('stored file name is missing in '.__METHOD__.' @ line 143.');
            }
            $this->reset('uploadedFileContainer');
            $this->displayFileImportForm = false;
            $this->redirect("/library/$this->libraryId/items");
//            } else {
//                Log::info('No file was uploaded.');
//            }
        }
    }

    #[NoReturn] public function clickUploadImageOrPdf(): void
    {
        $this->reset('fileSize', 'fileUploadMessage', 'uploadedMaxFileSizeExceeded');

        //check size
        if($this->uploadedFileContainer->isValid()) {
            $this->fileSize = $this->uploadedFileContainer->getSize();
        }else{
            Log::info('uploadedFileContainer is not valid');
        }

        Log::info('fileSize: '.$this->fileSize);
        //early exit if fileSize exceeds maxFileSIze
        if ($this->fileSize && ($this->fileSize > $this->uploadedMaxFileSize)) {
            $this->uploadedMaxFileSizeExceeded = true;
            Log::info('fileSize: '.$this->fileSize. ' exceeds uploadedMayFileSize: ' . $this->uploadedMaxFileSize);
        } else {

            Log::info('fileSize is good @ ' . $this->fileSize . '.');

            //store the file on a s3 disk
            $s3Path = 'libraries/items/imagesOrPdfs';
            $fileName = $this->makeLibItemImageOrPdfFileName($s3Path);

            Log::info('fileName: '.$fileName);
            $storedFileName = $this->uploadedFileContainer->storePubliclyAs($s3Path, $fileName, 's3');
            Log::info('storedFileName: '.$storedFileName);

            if ($storedFileName) {

                //clear any artifacts
                $metadata = [];

//                $userId = $this->form->getTeacherUserId();
                $metadata = SheetMusicParser::fromFile($storedFileName);
dd($metadata);
//                LibItemDoc::updateOrCreate(
//                    [
//                        'library_id' => $this->libraryId,
//                        'lib_item_id' => $this->form->sysId,
//                        'user_id' => $userId,
//                        'url' => $storedFileName,
//                        'shareable' => $this->form->shareable ? 1 : 0,
//                    ],
//                    [
//                        'label' => $this->uploadDescr,
//                    ]
//                );

            } else { //catch (\Exception $e) {
                Log::error('stored file name is missing in '.__METHOD__.' @ line 143.');
            }
        }

            $this->reset('uploadedFileContainer');

            $this->reset('displayViaImageOrPdf');
    }

    #[NoReturn] public function clickImportItems(): void
    {
        $this->reset('displayViaImageOrPdf');
        $this->displayFileImportForm = true;
    }

    #[NoReturn] public function clickVoicing(int $voicingId): void
    {
        $voicing = Voicing::find($voicingId);
        $this->form->voicingId = $voicing->id;
        $this->form->voicingDescr = $voicing->descr;
        $this->reset('searchVoicings');
    }

    #[NoReturn] public function findItem(int $libItemId)
    {
        $this->searching = true;

        $this->form->setLibItem(LibItem::find($libItemId));
    }

    public function removeDoc(int $libItemDocId): void
    {
        $libItemDoc = LibItemDoc::find($libItemDocId);
        $libItemDoc->delete();
        $this->redirect("/library/$this->libraryId/items");
    }

    public function save()
    {
        //early exit; don't save after a search item is clicked
        if ($this->searching) {
            $this->reset('searching');
            return;
        }

        if ($this->saveWorkflow()) {
            return $this->redirect("/library/$this->libraryId/items");
        }
    }

    public function saveAndStay(): void
    {
        Log::info(__METHOD__);
        Log::info('*** libraryId: ' . $this->libraryId);
        $this->saveWorkflow();

        $this->form->resetVars($this->libraryId);
        $this->reset('searchResults');
    }

    private function saveWorkflow(): bool
    {
        //determine if save = updating or adding
        $updating = (bool)$this->form->sysId;

        $this->reset('errorMessage', 'successMessage');

        $saved = $this->form->save($this->libraryId, $this->tagCsv);

        $fTitle = Str::title($this->form->title);

        if ($saved) {
            $message = '"' . $fTitle . ($updating ? '" updated.' : '" saved.');
            session()->flash('successMessage', $message);
            $this->reset('tagCsv');
        } else {
            $this->errorMessage = 'Unable to save "' . $fTitle . '" at this time.';
        }

        return $saved;
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

    public function updatedFormArtistsAuthor($value): void
    {
        $this->updatedFormArtistsType($value, 'author');
    }

    public function updatedFormArtistsChoreographer($value): void
    {
        $this->updatedFormArtistsType($value, 'choreographer');
    }

    public function updatedFormArtistsComposer($value): void
    {
        $this->updatedFormArtistsType($value, 'composer');
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

    public function updatedFormItemType($value): void
    {
        $this->form->setAddToHomeLibraryDefault();
        $this->form->setItemTypeDefault();
    }

    public function updatedFormBookType(): void
    {
        $this->form->setItemTypeDefault();
    }

    /**
     * ex. propertyName: form.medleySelections.0 | value: che faro
     * @param $propertyName
     * @param $value
     * @return void
     */
    public function updated($propertyName, $value): void
    {
        if (str_starts_with($propertyName, 'form.medleySelections.')) {
            // Extract the index from the property name
            $index = str_replace('form.medleySelections.', '', $propertyName);

            if ((int) $index === count($this->form->medleySelections) - 1 && !empty($value)) {

                $this->form->medleySelections[] = '';
                $this->dispatch('focusNewInput', count($this->form->medleySelections) - 1);
            }
        }
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

    public function updatedUploadedFileContainer(): void
    {
        $this->reset('fFileSize');

        if($this->uploadedFileContainer->isValid()) {
            $this->fFileSize = $this->calcFormattedFileSize();
        }else{
            dd($this->uploadedFileContainer->getErrorMessage());
        }
    }

    public function updateShareable(int $libItemDocId): void
    {
        $libItemDoc = LibItemDoc::find($libItemDocId);
        $libItemDoc->update(['shareable' => !$libItemDoc->shareable]);
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

    private function calcFormattedFileSize(): string
    {
        $size = $this->uploadedFileContainer->getSize();
        $suffix = ($size < 1000000) ? 'KB' : 'MB';

        //formatted size
        $fsize = ($suffix === 'KB')
            ? number_format($size / 1000, 0)
            : number_format($this->uploadedFileContainer->getSize() / 1000000, 1);

        return $fsize . $suffix;
    }

    private function makeLibItemDocFileName(string $dir): string
    {
        $extension = $this->uploadedFileContainer->guessExtension();
        $libraryName = Library::find($this->libraryId)->name;
        //initialize $slug
        $baseSlug = Str::slug($libraryName, '-');
        //add lib_item_id
        $baseSlug .= '-'.$this->form->sysId.'-';

        do {
            //add random number for unique id and extension
            $tempName = $baseSlug.strtotime('now').'.'.$extension;

            $exists = LibItemDoc::where('url', $dir.'/'.$tempName)->exists();
        } while ($exists);

        return $tempName;
    }

    private function makeLibItemImageOrPdfFileName(string $dir): string
    {
        $extension = $this->uploadedFileContainer->guessExtension();
        $libraryName = Library::find($this->libraryId)->name;
        //initialize $slug
        $baseSlug = Str::slug($libraryName, '-');
        //add lib_item_id
        $baseSlug .= '-'.$this->form->sysId.'-';

        do {
            //add random number for unique id and extension
            $tempName = $baseSlug.strtotime('now').'.'.$extension;

            $exists = LibItemDoc::where('url', $dir.'/'.$tempName)->exists();
        } while ($exists);

        return $tempName;
    }

    private function search(): void
    {
        $search = new LibraryStackSearchService($this->form);

        $this->searchResults = $search->getResults();
    }

    private function searchVoicing(): void
    {
        $searchValue = '%'.strtolower($this->form->voicingDescr).'%';

        $found = Voicing::query()
            ->where('descr', 'LIKE', $searchValue)
            ->orderBy('descr')
            ->get();

        foreach ($found as $voicing) {
            $this->searchVoicings[] = [
                'id' => $voicing->id,
                'descr' => Str::lower($voicing->descr),
            ];
        }

    }

    private function setTeacherEmail(): string
    {
        if (auth()->user()->isTeacher()) {
            return auth()->user()->email;
        }

        $libLibrarian = LibLibrarian::where('user_id', auth()->id())->first();
        return User::where('id', $libLibrarian->teacherUserId)->first()->email;
    }

    private function updatedFormArtistsType(string $value, string $type): void
    {
        if (strlen($value)) {

            $this->searchResultsArtists[$type] = ArtistSearchService::getResults($value, $type);

        } else {//user has removed the current value

            $this->searchResultsArtists[$type] = null;
            $this->form->artists[$type] = '';
            $this->form->artistIds[$type] = 0;
            if (isset($this->libItem)) {
                $this->libItem->update([$type => 0]);
            }
        }
    }

}
