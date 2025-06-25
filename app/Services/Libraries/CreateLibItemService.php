<?php

namespace App\Services\Libraries;

use App\Livewire\Forms\LibraryItemForm;
use App\Livewire\Forms\ProgramSelectionForm;
use App\Models\Libraries\Items\Components\LibTitle;
use App\Models\Libraries\Items\Components\Voicing;
use App\Models\Libraries\Items\LibItem;
use App\Models\Schools\Teacher;
use App\Models\Libraries\Items\Components\Artist;
use App\Services\ArtistIdService;
use Illuminate\Support\Str;


class CreateLibItemService
{
    public int|null $arrangerId = null;
    public int|null $choreographerId = null;
    public int|null $composerId = null;

    public string $itemType = 'sheet music';
    public int $libItemId = 0;
    private array $errors = [];
    private string $libTitleId = '';
    public int|null $musicId = null;
    public bool $saved = false;
    private int $teacherId = 0;
    private int $voicingId = 0;
    public int|null $wamId = null;
    public int|null $wordsId = null;

    public function __construct(
        private readonly LibraryItemForm|ProgramSelectionForm $form,
        private readonly array $itemTypes
    )
    {
        $this->teacherId = $this->setTeacherId();
        $this->itemType = $this->form->itemType;
        $this->libTitleId = $this->getLibTitleId();
        $this->voicingId = $this->getVoicingId();
        $this->composerId = $this->getArtistId('composer');
        $this->arrangerId = $this->getArtistId('arranger');
        $this->wamId = $this->getArtistId('wam');
        $this->wordsId = $this->getArtistId('words');
        $this->musicId = $this->getArtistId('music');
        $this->choreographerId = $this->getArtistId('choreographer');

        $this->errorCheck();

        if (!count($this->errors)) {
            $this->add();
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
                'voicing_id' => $this->voicingId,
            ];
            $artistItems = $this->addArtistIds();

            $items = array_merge($baseItems, $artistItems);

            $this->libItemId = LibItem::create($items)->id;
        }

        $this->saved = (bool) $this->libItemId;
    }

    private function addArtistIds(): array
    {
        return [
            'composer_id' => $this->composerId,
            'arranger_id' => $this->arrangerId,
            'wam_id' => $this->wamId,
            'words_id' => $this->wordsId,
            'music_id' => $this->musicId,
            'choreographer_id' => $this->choreographerId,
        ];

    }

//    private function addArtists(): array
//    {
//        $a = [];
//        $this->addArtistIds();
//
//        foreach ($this->form->artistIds as $type => $artistId) {
//
//            if ($artistId) {
//                $column = $type.'_id';
//
//                $a[$column] = $artistId;
//            }
//        }
//
//        return $a;
//    }

    private function errorCheck(): void
    {
        if (!in_array($this->itemType, $this->itemTypes)) {
            $this->errors[] = 'Invalid item type.';
        }

        if (!strlen($this->form->title)) {
            $this->errors[] = 'No title found.';
        }

        if (!$this->voicingId) {
            $this->errors[] = 'No voicing found.';
        }
    }

    private function existingLibItemId(): int
    {
        return LibItem::query()
            ->where('lib_title_id', $this->libTitleId)
            ->where('item_type', $this->itemType)
            ->where('voicing_id', $this->voicingId)
            ->where('composer_id', $this->choreographerId)
            ->where('arranger_id', $this->arrangerId)
            ->where('wam_id', $this->wamId)
            ->where('words_id', $this->wordsId)
            ->where('music_id', $this->musicId)
            ->where('choreographer_id', $this->choreographerId)
            ->first()
            ->id;
    }

    private function getArtistId(string $artistType): int|null
    {
        $artistName = $this->form->artists[$artistType];

        if (strlen($artistName)) {

            $artist = Artist::where('artist_name', $artistName)->first();

            if ($artist) {
                return $artist->id;
            }

            $service = new ArtistIdService($artistName);

            return $service->getId();

        }

        return null;

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

    private function getVoicingId(): int
    {
        //check for new voicing
        $descr = strtolower($this->form->voicingDescr) ?? '';

        //early exit
        if (empty($descr) && $this->form->voicingId) {
            return $this->form->voicingId;
        }

        //error condition of no $this->form->voicingId AND no $descr
        if ($descr === '') {
            return 0;
        }

        //check for duplicate value
        if (Voicing::where('descr', $descr)->exists()) {
            return Voicing::where('descr', $descr)->first()->id;
        }

        //else create new voicing object
        return Voicing::create(
            [
                'category' => 'choral',
                'descr' => $descr,
                'created_by' => auth()->id(),
            ]
        )->id;
    }

    private function libItemExists(): bool
    {
        return LibItem::query()
            ->where('lib_title_id', $this->libTitleId)
            ->where('item_type', $this->itemType)
            ->where('voicing_id', $this->voicingId)
            ->where('composer_id', $this->composerId)
            ->where('arranger_id', $this->arrangerId)
            ->where('wam_id', $this->wamId)
            ->where('words_id', $this->wordsId)
            ->where('music_id', $this->musicId)
            ->where('choreographer_id', $this->choreographerId)
            ->exists();
    }

    private function setTeacherId(): int
    {
        return Teacher::where('user_id', auth()->id())->first()->id;
    }


}
