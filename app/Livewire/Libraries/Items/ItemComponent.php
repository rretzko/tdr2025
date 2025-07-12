<?php

namespace App\Livewire\Libraries\Items;

use App\Models\Libraries\Items\Components\Artist;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\Components\Voicing;
use App\Models\Libraries\LibStack;
use App\Models\Libraries\Items\LibItem;
use App\Services\ArtistSearchService;
use App\Services\Libraries\CreateLibItemService;
use App\Services\Libraries\LibraryStackSearchService;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;

class ItemComponent extends BaseLibraryItemPage
{
    public array $artistTypes = [];
    public array $difficulties = [];
    public string $errorMessage = '';
    public array $itemTypes = [];
    public int $libraryId = 0;
    public LibItem $libItem;
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

    public function mount(): void
    {
        parent::mount();

        $this->difficulties = $this->setDifficulties();
        $this->libraryId = $this->dto['id'];
        $this->form->libraryId = $this->libraryId;

        $this->artistTypes = parent::ARTISTTYPES;
        $this->itemTypes = parent::ITEMTYPES;

        if (isset($this->dto['libItem'])) {
            $this->libItem = $this->dto['libItem'];
            $this->form->setLibItem($this->libItem);
        } else {
            $this->form->resetVars();
        }

    }

    public function render()
    {
        return view('livewire..libraries.items.item-component',
            [
                'bladeForm' => 'components.forms.libraries.itemTypes.' . $this->form->itemTypeBlade() . 'Form',
            ]);
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

        $saved = $this->form->save($this->libraryId, parent::ITEMTYPES);

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
            self::ITEMTYPES,
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

    public function setDifficulties(): array
    {
        return [
            'easy',
            'medium',
            'hard',
            'elementary school',
            'middle school',
            'high school',
            'collegiate',
            'professional'
        ];
    }

    public function updatedFormArtistsArranger($value): void
    {
        if (strlen($value)) {

            $this->searchResultsArtists['arranger'] = ArtistSearchService::getResults($value, 'arranger');

        } else {//user has removed the current value

            $this->form->artists['arranger'] = '';
            $this->form->artistIds['arranger'] = 0;
            $this->libItem->update(['arranger_id' => 0]);
        }
    }

    public function updatedFormTitle(): void
    {
        $this->search();
    }

    public function updatedFormVoicingDescr(): void
    {
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

}
