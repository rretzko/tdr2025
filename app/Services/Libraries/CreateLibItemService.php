<?php

namespace App\Services\Libraries;

use App\Livewire\Forms\LibraryItemForm;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\LibItem;
use App\Models\Schools\Teacher;
use App\Services\ArtistIdService;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;

class CreateLibItemService
{
    public bool $saved = false;
    public string $itemType = 'sheet music';
    public int $libItemId = 0;
    private array $errors = [];
    private string $libTitleId = '';
    private int $teacherId = 0;

    public function __construct(private readonly LibraryItemForm $form, private readonly array $itemTypes)
    {
        $this->teacherId = $this->setTeacherId();
        $this->itemType = $this->form->itemType;
        $this->libTitleId = $this->getLibTitleId();

        $this->errorCheck();

        if (!count($this->errors)) {
            $this->add();
        }

    }

    private function addArtistIds(): void
    {
        foreach ($this->form->artists as $type => $artistName) {

            $service = new ArtistIdService($artistName);
            $this->form->artistIds[$type] = $service->getId();
        }
    }

    private function addArtists(): array
    {
        $a = [];
        $this->addArtistIds();

        foreach ($this->form->artistIds as $type => $artistId) {

            $column = $type.'_id';

            $a[$column] = $artistId;
        }

        return $a;
    }

    private function setTeacherId(): int
    {
        return Teacher::where('user_id', auth()->id())->first()->id;
    }

    private function getLibTitleId(): int
    {
        //ensure title is properly formatted
        $value = Str::title(strtolower($this->form->title));

        //use existing id if found
        $exists = LibTitle::where('title', $value)->exists();
        if ($exists) {
            return LibTitle::where('title', $value)->first()->id;
        }

        //else create a new row in the lib_titles table and return that id
        $title = Str::title($this->form->title);
        $alpha = MakeAlphaService::alphabetize($title);

        return LibTitle::create(
            [
                'teacher_id' => $this->teacherId,
                'title' => $title,
                'alpha' => $alpha,
            ],
        )->id;
    }

    private function errorCheck(): void
    {
        if (!in_array($this->itemType, $this->itemTypes)) {
            $this->errors[] = 'Invalid item type.';
        }

        if (!strlen($this->form->title)) {
            $this->errors[] = 'No title found.';
        }
    }

    private function add(): void
    {
        //use an existing item
        if ($this->libItemExists()) {
            $this->libItemId = $this->existingLibItemId();
        } else {
            //or create a new item
            $baseItems = [
                'item_type' => $this->itemType,
                'lib_title_id' => $this->libTitleId,
            ];
            $artistItems = $this->addArtists();
            $items = array_merge($baseItems, $artistItems);
            $this->libItemId = LibItem::create($items)->id;
        }

        $this->saved = (bool)$this->libItemId;
    }

    private function libItemExists(): bool
    {
        return LibItem::query()
            ->where('lib_title_id', $this->libTitleId)
            ->where('item_type', $this->itemType)
            ->exists();
    }

    private function existingLibItemId(): int
    {
        return LibItem::query()
            ->where('lib_title_id', $this->libTitleId)
            ->where('item_type', $this->itemType)
            ->first()
            ->id;
    }
}
