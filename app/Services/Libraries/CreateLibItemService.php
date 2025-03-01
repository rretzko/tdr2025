<?php

namespace App\Services\Libraries;

use App\Livewire\Forms\LibraryItemForm;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\LibItem;
use App\Models\Schools\Teacher;
use Illuminate\Support\Str;

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
        $alpha = $this->makeAlpha($title);

        return LibTitle::create(
            [
                'teacher_id' => $this->teacherId,
                'title' => $title,
                'alpha' => $alpha,
            ],
        )->id;
    }

    /**
     * Apply alphabetization rules to $string
     * @param string $string
     * @return string
     */
    private function makeAlpha(string $string): string
    {
        $articles = ['A', 'An', 'And', 'The'];
        $parts = array_map('trim', explode(' ', $string));

        while (in_array($parts[0], $articles)) {
            $article = $parts[0];
            array_shift($parts);
            $string = implode(' ', $parts) . ', ' . $article;
            return $this->makeAlpha($string); //recursion
        }

        return $string;
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
            $this->libItemId = LibItem::create(
                [
                    'item_type' => $this->itemType,
                    'lib_title_id' => $this->libTitleId,
                ]
            )->id;
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
